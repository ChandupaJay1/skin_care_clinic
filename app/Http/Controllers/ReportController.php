<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Invoice;
use App\Models\Patient;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    // ── Daily Summary ─────────────────────────────────────────────────────────

    public function daily(Request $request)
    {
        $date = $request->filled('date')
            ? Carbon::parse($request->date)
            : Carbon::today();

        // Appointments
        $appointments = Appointment::with(['patient', 'doctor', 'treatment'])
            ->whereDate('appointment_date', $date)
            ->orderBy('appointment_time')
            ->get();

        $apptStats = [
            'total'     => $appointments->count(),
            'scheduled' => $appointments->where('status', 'scheduled')->count(),
            'completed' => $appointments->where('status', 'completed')->count(),
            'cancelled' => $appointments->where('status', 'cancelled')->count(),
            'no_show'   => $appointments->where('status', 'no_show')->count(),
        ];

        // Revenue
        $invoices = Invoice::with('patient')
            ->whereDate('created_at', $date)
            ->where('status', '!=', 'cancelled')
            ->get();

        $revenueStats = [
            'total_invoiced' => $invoices->sum('total'),
            'total_collected'=> $invoices->sum('paid_amount'),
            'total_balance'  => $invoices->sum('balance'),
            'invoice_count'  => $invoices->count(),
            'paid_count'     => $invoices->where('status', 'paid')->count(),
            'partial_count'  => $invoices->where('status', 'partial')->count(),
        ];

        // Payment method breakdown
        $paymentBreakdown = $invoices->groupBy('payment_method')
            ->map(fn($g) => [
                'count'  => $g->count(),
                'amount' => $g->sum('paid_amount'),
            ]);

        // New patients
        $newPatients = Patient::whereDate('created_at', $date)->get();

        return view('reports.daily', compact(
            'date', 'appointments', 'apptStats',
            'invoices', 'revenueStats', 'paymentBreakdown', 'newPatients'
        ));
    }

    // ── Monthly Revenue ───────────────────────────────────────────────────────

    public function monthly(Request $request)
    {
        $year  = $request->filled('year')  ? (int) $request->year  : now()->year;
        $month = $request->filled('month') ? (int) $request->month : now()->month;

        $startOfMonth = Carbon::create($year, $month, 1)->startOfMonth();
        $endOfMonth   = $startOfMonth->copy()->endOfMonth();

        // Daily revenue for the selected month (for chart)
        $dailyRevenue = Invoice::selectRaw('DATE(created_at) as day, SUM(paid_amount) as collected, SUM(total) as invoiced, COUNT(*) as count')
            ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->where('status', '!=', 'cancelled')
            ->groupBy('day')
            ->orderBy('day')
            ->get()
            ->keyBy('day');

        // Fill all days of month (so chart has no gaps)
        $chartDays      = [];
        $chartCollected = [];
        $chartInvoiced  = [];
        $daysInMonth    = $startOfMonth->daysInMonth;

        for ($d = 1; $d <= $daysInMonth; $d++) {
            $key              = Carbon::create($year, $month, $d)->format('Y-m-d');
            $chartDays[]      = $d;
            $chartCollected[] = isset($dailyRevenue[$key]) ? (float) $dailyRevenue[$key]->collected : 0;
            $chartInvoiced[]  = isset($dailyRevenue[$key]) ? (float) $dailyRevenue[$key]->invoiced  : 0;
        }

        // Monthly totals
        $monthlyInvoices = Invoice::whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->where('status', '!=', 'cancelled')
            ->get();

        $monthStats = [
            'total_invoiced'  => $monthlyInvoices->sum('total'),
            'total_collected' => $monthlyInvoices->sum('paid_amount'),
            'total_balance'   => $monthlyInvoices->sum('balance'),
            'invoice_count'   => $monthlyInvoices->count(),
            'paid_count'      => $monthlyInvoices->where('status', 'paid')->count(),
            'partial_count'   => $monthlyInvoices->where('status', 'partial')->count(),
        ];

        // Top treatments by revenue this month
        $topTreatments = DB::table('invoice_items')
            ->join('invoices', 'invoice_items.invoice_id', '=', 'invoices.id')
            ->join('treatments', 'invoice_items.treatment_id', '=', 'treatments.id')
            ->whereBetween('invoices.created_at', [$startOfMonth, $endOfMonth])
            ->where('invoices.status', '!=', 'cancelled')
            ->selectRaw('treatments.name, SUM(invoice_items.line_total) as revenue, SUM(invoice_items.quantity) as sessions')
            ->groupBy('treatments.id', 'treatments.name')
            ->orderByDesc('revenue')
            ->limit(5)
            ->get();

        // Appointment stats for the month
        $apptStats = [
            'total'     => Appointment::whereBetween('appointment_date', [$startOfMonth, $endOfMonth])->count(),
            'completed' => Appointment::whereBetween('appointment_date', [$startOfMonth, $endOfMonth])->where('status', 'completed')->count(),
            'cancelled' => Appointment::whereBetween('appointment_date', [$startOfMonth, $endOfMonth])->where('status', 'cancelled')->count(),
            'no_show'   => Appointment::whereBetween('appointment_date', [$startOfMonth, $endOfMonth])->where('status', 'no_show')->count(),
        ];

        // New patients this month
        $newPatients = Patient::whereBetween('created_at', [$startOfMonth, $endOfMonth])->count();

        // Last 12 months revenue for trend sparkline
        $last12 = [];
        for ($i = 11; $i >= 0; $i--) {
            $m     = now()->subMonths($i);
            $start = $m->copy()->startOfMonth();
            $end   = $m->copy()->endOfMonth();
            $last12[] = [
                'label'     => $m->format('M y'),
                'collected' => (float) Invoice::whereBetween('created_at', [$start, $end])
                    ->where('status', '!=', 'cancelled')
                    ->sum('paid_amount'),
            ];
        }

        return view('reports.monthly', compact(
            'year', 'month', 'startOfMonth',
            'chartDays', 'chartCollected', 'chartInvoiced',
            'monthStats', 'topTreatments', 'apptStats', 'newPatients', 'last12'
        ));
    }

    // ── Outstanding Balances ──────────────────────────────────────────────────

    public function outstanding(Request $request)
    {
        $query = Invoice::with(['patient', 'items'])
            ->where('balance', '>', 0)
            ->whereIn('status', ['partial', 'draft'])
            ->orderByDesc('balance');

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('invoice_number', 'like', "%{$s}%")
                  ->orWhereHas('patient', fn($p) =>
                      $p->where('full_name', 'like', "%{$s}%")
                        ->orWhere('patient_id', 'like', "%{$s}%")
                  );
            });
        }

        // Age filter
        if ($request->filled('age')) {
            $days = (int) $request->age;
            $query->where('created_at', '<=', now()->subDays($days));
        }

        $invoices = $query->paginate(25)->withQueryString();

        $summary = [
            'total_outstanding' => Invoice::where('balance', '>', 0)->whereIn('status', ['partial', 'draft'])->sum('balance'),
            'patient_count'     => Invoice::where('balance', '>', 0)->whereIn('status', ['partial', 'draft'])->distinct('patient_id')->count('patient_id'),
            'invoice_count'     => Invoice::where('balance', '>', 0)->whereIn('status', ['partial', 'draft'])->count(),
            'overdue_7'         => Invoice::where('balance', '>', 0)->whereIn('status', ['partial', 'draft'])->where('created_at', '<=', now()->subDays(7))->sum('balance'),
            'overdue_30'        => Invoice::where('balance', '>', 0)->whereIn('status', ['partial', 'draft'])->where('created_at', '<=', now()->subDays(30))->sum('balance'),
        ];

        return view('reports.outstanding', compact('invoices', 'summary'));
    }

    // ── Print views ───────────────────────────────────────────────────────────

    public function printDaily(Request $request)
    {
        $date = $request->filled('date') ? Carbon::parse($request->date) : Carbon::today();

        $appointments = Appointment::with(['patient', 'doctor', 'treatment'])
            ->whereDate('appointment_date', $date)->orderBy('appointment_time')->get();

        $apptStats = [
            'total'     => $appointments->count(),
            'scheduled' => $appointments->where('status', 'scheduled')->count(),
            'completed' => $appointments->where('status', 'completed')->count(),
            'cancelled' => $appointments->where('status', 'cancelled')->count(),
            'no_show'   => $appointments->where('status', 'no_show')->count(),
        ];

        $invoices = Invoice::with('patient')
            ->whereDate('created_at', $date)->where('status', '!=', 'cancelled')->get();

        $revenueStats = [
            'total_invoiced'  => $invoices->sum('total'),
            'total_collected' => $invoices->sum('paid_amount'),
            'total_balance'   => $invoices->sum('balance'),
            'invoice_count'   => $invoices->count(),
            'paid_count'      => $invoices->where('status', 'paid')->count(),
            'partial_count'   => $invoices->where('status', 'partial')->count(),
        ];

        $paymentBreakdown = $invoices->groupBy('payment_method')
            ->map(fn($g) => ['count' => $g->count(), 'amount' => $g->sum('paid_amount')]);

        $newPatients = Patient::whereDate('created_at', $date)->get();

        return view('reports.print.daily', compact(
            'date', 'appointments', 'apptStats',
            'invoices', 'revenueStats', 'paymentBreakdown', 'newPatients'
        ));
    }

    public function printMonthly(Request $request)
    {
        $year  = $request->filled('year')  ? (int) $request->year  : now()->year;
        $month = $request->filled('month') ? (int) $request->month : now()->month;

        $startOfMonth = Carbon::create($year, $month, 1)->startOfMonth();
        $endOfMonth   = $startOfMonth->copy()->endOfMonth();

        $monthlyInvoices = Invoice::with(['patient', 'items.treatment'])
            ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->where('status', '!=', 'cancelled')
            ->orderBy('created_at')
            ->get();

        $monthStats = [
            'total_invoiced'  => $monthlyInvoices->sum('total'),
            'total_collected' => $monthlyInvoices->sum('paid_amount'),
            'total_balance'   => $monthlyInvoices->sum('balance'),
            'invoice_count'   => $monthlyInvoices->count(),
            'paid_count'      => $monthlyInvoices->where('status', 'paid')->count(),
            'partial_count'   => $monthlyInvoices->where('status', 'partial')->count(),
        ];

        $topTreatments = DB::table('invoice_items')
            ->join('invoices', 'invoice_items.invoice_id', '=', 'invoices.id')
            ->join('treatments', 'invoice_items.treatment_id', '=', 'treatments.id')
            ->whereBetween('invoices.created_at', [$startOfMonth, $endOfMonth])
            ->where('invoices.status', '!=', 'cancelled')
            ->selectRaw('treatments.name, SUM(invoice_items.line_total) as revenue, SUM(invoice_items.quantity) as sessions')
            ->groupBy('treatments.id', 'treatments.name')
            ->orderByDesc('revenue')
            ->get();

        $apptStats = [
            'total'     => Appointment::whereBetween('appointment_date', [$startOfMonth, $endOfMonth])->count(),
            'scheduled' => Appointment::whereBetween('appointment_date', [$startOfMonth, $endOfMonth])->where('status', 'scheduled')->count(),
            'completed' => Appointment::whereBetween('appointment_date', [$startOfMonth, $endOfMonth])->where('status', 'completed')->count(),
            'cancelled' => Appointment::whereBetween('appointment_date', [$startOfMonth, $endOfMonth])->where('status', 'cancelled')->count(),
            'no_show'   => Appointment::whereBetween('appointment_date', [$startOfMonth, $endOfMonth])->where('status', 'no_show')->count(),
        ];

        $newPatients = Patient::whereBetween('created_at', [$startOfMonth, $endOfMonth])->count();

        $paymentBreakdown = $monthlyInvoices->groupBy('payment_method')
            ->map(fn($g) => ['count' => $g->count(), 'amount' => $g->sum('paid_amount')]);

        return view('reports.print.monthly', compact(
            'year', 'month', 'startOfMonth', 'endOfMonth',
            'monthlyInvoices', 'monthStats', 'topTreatments',
            'apptStats', 'newPatients', 'paymentBreakdown'
        ));
    }

    public function printOutstanding(Request $request)
    {
        $query = Invoice::with(['patient', 'items'])
            ->where('balance', '>', 0)
            ->whereIn('status', ['partial', 'draft'])
            ->orderByDesc('balance');

        if ($request->filled('age')) {
            $query->where('created_at', '<=', now()->subDays((int) $request->age));
        }

        $invoices = $query->get();

        $summary = [
            'total_outstanding' => $invoices->sum('balance'),
            'patient_count'     => $invoices->unique('patient_id')->count(),
            'invoice_count'     => $invoices->count(),
            'overdue_7'         => $invoices->filter(fn($i) => $i->created_at->diffInDays(now()) >= 7)->sum('balance'),
            'overdue_30'        => $invoices->filter(fn($i) => $i->created_at->diffInDays(now()) >= 30)->sum('balance'),
        ];

        return view('reports.print.outstanding', compact('invoices', 'summary'));
    }
}
