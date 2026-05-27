<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Patient;
use App\Models\Treatment;
use App\Models\Appointment;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function index(Request $request)
    {
        $query = Invoice::with(['patient', 'items'])
            ->latest();

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

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('from')) {
            $query->whereDate('created_at', '>=', $request->from);
        }

        if ($request->filled('to')) {
            $query->whereDate('created_at', '<=', $request->to);
        }

        $invoices = $query->paginate(20)->withQueryString();

        $stats = [
            'total'    => Invoice::count(),
            'paid'     => Invoice::where('status', 'paid')->count(),
            'partial'  => Invoice::where('status', 'partial')->count(),
            'revenue'  => Invoice::where('status', '!=', 'cancelled')->sum('paid_amount'),
        ];

        return view('invoices.index', compact('invoices', 'stats'));
    }

    public function create(Request $request)
    {
        $patients   = Patient::where('is_active', true)->orderBy('full_name')->get();
        $treatments = Treatment::where('is_active', true)->orderBy('name')->get();

        $selectedPatient    = $request->filled('patient_id') ? Patient::find($request->patient_id) : null;
        $selectedAppointment = $request->filled('appointment_id') ? Appointment::with('treatment')->find($request->appointment_id) : null;

        return view('invoices.create', compact('patients', 'treatments', 'selectedPatient', 'selectedAppointment'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'patient_id'       => 'required|exists:patients,id',
            'appointment_id'   => 'nullable|exists:appointments,id',
            'discount'         => 'nullable|numeric|min:0',
            'discount_percent' => 'nullable|numeric|min:0|max:100',
            'paid_amount'      => 'required|numeric|min:0',
            'payment_method'   => 'required|in:cash,card,bank_transfer,other',
            'notes'            => 'nullable|string|max:1000',
            'items'            => 'required|array|min:1',
            'items.*.description' => 'required|string|max:255',
            'items.*.quantity'    => 'required|integer|min:1',
            'items.*.unit_price'  => 'required|numeric|min:0',
            'items.*.treatment_id' => 'nullable|exists:treatments,id',
        ]);

        // Calculate totals
        $subtotal = 0;
        foreach ($request->items as $item) {
            $subtotal += $item['quantity'] * $item['unit_price'];
        }

        $discountAmt     = floatval($request->discount ?? 0);
        $discountPercent = floatval($request->discount_percent ?? 0);

        // Percent discount takes priority if both given
        if ($discountPercent > 0) {
            $discountAmt = round($subtotal * $discountPercent / 100, 2);
        }

        $total      = max(0, $subtotal - $discountAmt);
        $paidAmount = min(floatval($request->paid_amount), $total);
        $balance    = $total - $paidAmount;

        $status = 'draft';
        if ($paidAmount >= $total) {
            $status = 'paid';
        } elseif ($paidAmount > 0) {
            $status = 'partial';
        }

        $invoice = Invoice::create([
            'invoice_number'   => Invoice::generateInvoiceNumber(),
            'patient_id'       => $request->patient_id,
            'appointment_id'   => $request->appointment_id ?: null,
            'subtotal'         => $subtotal,
            'discount'         => $discountAmt,
            'discount_percent' => $discountPercent,
            'total'            => $total,
            'paid_amount'      => $paidAmount,
            'balance'          => $balance,
            'payment_method'   => $request->payment_method,
            'status'           => $status,
            'notes'            => $request->notes,
        ]);

        foreach ($request->items as $item) {
            $lineTotal = $item['quantity'] * $item['unit_price'];
            $invoice->items()->create([
                'treatment_id' => $item['treatment_id'] ?: null,
                'description'  => $item['description'],
                'quantity'     => $item['quantity'],
                'unit_price'   => $item['unit_price'],
                'line_total'   => $lineTotal,
            ]);
        }

        return redirect()->route('invoices.show', $invoice)
            ->with('success', "Invoice {$invoice->invoice_number} created successfully.");
    }

    public function show(Invoice $invoice)
    {
        $invoice->load(['patient', 'items.treatment', 'appointment.doctor']);
        return view('invoices.show', compact('invoice'));
    }

    public function print(Invoice $invoice)
    {
        $invoice->load(['patient', 'items.treatment', 'appointment.doctor']);
        return view('invoices.print', compact('invoice'));
    }

    public function destroy(Invoice $invoice)
    {
        $invoice->update(['status' => 'cancelled']);
        return back()->with('success', 'Invoice cancelled.');
    }

    /**
     * JSON — appointments for a patient (used by create form)
     */
    public function patientAppointments(Request $request)
    {
        $request->validate(['patient_id' => 'required|exists:patients,id']);

        $appts = Appointment::where('patient_id', $request->patient_id)
            ->with('treatment')
            ->orderBy('appointment_date', 'desc')
            ->get()
            ->map(fn($a) => [
                'id'               => $a->id,
                'booking_number'   => $a->booking_number,
                'appointment_date' => $a->appointment_date->format('d M Y'),
                'formatted_time'   => $a->formatted_time,
                'treatment_id'     => $a->treatment_id,
                'treatment_name'   => $a->treatment?->name,
            ]);

        return response()->json($appts);
    }
}
