<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monthly Revenue Report — {{ $startOfMonth->format('F Y') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @media print {
            body { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .no-print { display: none !important; }
            @page { margin: 15mm; size: A4 portrait; }
            table { page-break-inside: auto; }
            tr { page-break-inside: avoid; }
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen py-8 px-4">

{{-- Toolbar --}}
<div class="no-print fixed top-4 right-4 flex gap-2 z-50">
    <button onclick="window.print()"
        class="flex items-center gap-2 bg-gray-800 hover:bg-gray-900 text-white text-sm font-semibold px-4 py-2 rounded-xl shadow-lg transition">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
        </svg>
        Print
    </button>
    <a href="{{ route('reports.monthly', ['year'=>$year,'month'=>$month]) }}"
        class="flex items-center gap-2 bg-white border border-gray-200 text-gray-600 hover:bg-gray-50 text-sm px-4 py-2 rounded-xl shadow transition">
        ← Back
    </a>
</div>

<div class="max-w-4xl mx-auto bg-white shadow-xl rounded-2xl overflow-hidden">

    {{-- Report Header --}}
    <div class="bg-gradient-to-r from-gray-800 to-gray-700 px-8 py-6">
        <div class="flex items-start justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-rose-500 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-white font-bold text-base leading-tight">Skin Care Clinic</p>
                    <p class="text-gray-400 text-xs">Clinic Management System</p>
                </div>
            </div>
            <div class="text-right">
                <p class="text-gray-400 text-xs uppercase tracking-widest mb-1">Monthly Revenue Report</p>
                <p class="text-white text-xl font-bold">{{ $startOfMonth->format('F Y') }}</p>
                <p class="text-gray-400 text-xs mt-1">{{ $startOfMonth->format('d M') }} — {{ $endOfMonth->format('d M Y') }}</p>
                <p class="text-gray-500 text-xs mt-2">Generated: {{ now()->format('d M Y, h:i A') }}</p>
            </div>
        </div>
    </div>

    <div class="p-8 space-y-8">

        {{-- KPI Summary --}}
        <div>
            <h2 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4 pb-2 border-b border-gray-200">Monthly Summary</h2>
            <div class="grid grid-cols-4 gap-4">
                <div class="border border-gray-200 rounded-xl p-4 text-center">
                    <p class="text-xl font-bold text-gray-800">Rs.{{ number_format($monthStats['total_collected'], 0) }}</p>
                    <p class="text-xs text-gray-500 mt-1 font-medium">Collected</p>
                    <p class="text-xs text-gray-400 mt-0.5">of Rs.{{ number_format($monthStats['total_invoiced'], 0) }}</p>
                </div>
                <div class="border border-gray-200 rounded-xl p-4 text-center">
                    <p class="text-3xl font-bold text-gray-800">{{ $monthStats['invoice_count'] }}</p>
                    <p class="text-xs text-gray-500 mt-1 font-medium">Invoices</p>
                    <p class="text-xs text-green-600 mt-0.5">{{ $monthStats['paid_count'] }} paid · {{ $monthStats['partial_count'] }} partial</p>
                </div>
                <div class="border border-gray-200 rounded-xl p-4 text-center">
                    <p class="text-3xl font-bold text-gray-800">{{ $apptStats['total'] }}</p>
                    <p class="text-xs text-gray-500 mt-1 font-medium">Appointments</p>
                    <p class="text-xs text-green-600 mt-0.5">{{ $apptStats['completed'] }} completed</p>
                </div>
                <div class="border border-gray-200 rounded-xl p-4 text-center">
                    <p class="text-3xl font-bold text-gray-800">{{ $newPatients }}</p>
                    <p class="text-xs text-gray-500 mt-1 font-medium">New Patients</p>
                    @if($monthStats['total_balance'] > 0)
                    <p class="text-xs text-red-500 mt-0.5">Rs.{{ number_format($monthStats['total_balance'], 0) }} outstanding</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- Appointment Stats --}}
        <div>
            <h2 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4 pb-2 border-b border-gray-200">Appointment Breakdown</h2>
            <table class="w-full text-sm border-collapse">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="text-left px-4 py-2.5 text-xs font-semibold text-gray-500 border border-gray-200">Status</th>
                        <th class="text-right px-4 py-2.5 text-xs font-semibold text-gray-500 border border-gray-200">Count</th>
                        <th class="text-right px-4 py-2.5 text-xs font-semibold text-gray-500 border border-gray-200">% of Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach(['completed'=>'Completed','scheduled'=>'Scheduled','cancelled'=>'Cancelled','no_show'=>'No Show'] as $key => $label)
                    <tr>
                        <td class="px-4 py-2.5 border border-gray-200 font-medium text-gray-700">{{ $label }}</td>
                        <td class="px-4 py-2.5 border border-gray-200 text-right text-gray-800">{{ $apptStats[$key] }}</td>
                        <td class="px-4 py-2.5 border border-gray-200 text-right text-gray-500">
                            {{ $apptStats['total'] > 0 ? round($apptStats[$key] / $apptStats['total'] * 100, 1) : 0 }}%
                        </td>
                    </tr>
                    @endforeach
                    <tr class="bg-gray-50 font-bold">
                        <td class="px-4 py-2.5 border border-gray-200 text-gray-700">Total</td>
                        <td class="px-4 py-2.5 border border-gray-200 text-right text-gray-800">{{ $apptStats['total'] }}</td>
                        <td class="px-4 py-2.5 border border-gray-200 text-right text-gray-500">100%</td>
                    </tr>
                </tbody>
            </table>
        </div>

        {{-- Payment Method Breakdown --}}
        @if($paymentBreakdown->isNotEmpty())
        <div>
            <h2 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4 pb-2 border-b border-gray-200">Payment Method Breakdown</h2>
            <table class="w-full text-sm border-collapse">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="text-left px-4 py-2.5 text-xs font-semibold text-gray-500 border border-gray-200">Method</th>
                        <th class="text-right px-4 py-2.5 text-xs font-semibold text-gray-500 border border-gray-200">Invoices</th>
                        <th class="text-right px-4 py-2.5 text-xs font-semibold text-gray-500 border border-gray-200">Amount</th>
                        <th class="text-right px-4 py-2.5 text-xs font-semibold text-gray-500 border border-gray-200">% of Revenue</th>
                    </tr>
                </thead>
                <tbody>
                    @php $labels = ['cash'=>'Cash','card'=>'Card','bank_transfer'=>'Bank Transfer','other'=>'Other']; @endphp
                    @foreach($paymentBreakdown as $method => $data)
                    <tr>
                        <td class="px-4 py-2.5 border border-gray-200 font-medium text-gray-700">{{ $labels[$method] ?? ucfirst($method) }}</td>
                        <td class="px-4 py-2.5 border border-gray-200 text-right text-gray-600">{{ $data['count'] }}</td>
                        <td class="px-4 py-2.5 border border-gray-200 text-right font-semibold text-gray-800">Rs. {{ number_format($data['amount'], 2) }}</td>
                        <td class="px-4 py-2.5 border border-gray-200 text-right text-gray-500">
                            {{ $monthStats['total_collected'] > 0 ? round($data['amount'] / $monthStats['total_collected'] * 100, 1) : 0 }}%
                        </td>
                    </tr>
                    @endforeach
                    <tr class="bg-gray-50 font-bold">
                        <td class="px-4 py-2.5 border border-gray-200 text-gray-700">Total</td>
                        <td class="px-4 py-2.5 border border-gray-200 text-right text-gray-700">{{ $monthStats['invoice_count'] }}</td>
                        <td class="px-4 py-2.5 border border-gray-200 text-right text-gray-800">Rs. {{ number_format($monthStats['total_collected'], 2) }}</td>
                        <td class="px-4 py-2.5 border border-gray-200 text-right text-gray-500">100%</td>
                    </tr>
                </tbody>
            </table>
        </div>
        @endif

        {{-- Top Treatments --}}
        @if($topTreatments->isNotEmpty())
        <div>
            <h2 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4 pb-2 border-b border-gray-200">Top Treatments by Revenue</h2>
            <table class="w-full text-sm border-collapse">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="text-left px-4 py-2.5 text-xs font-semibold text-gray-500 border border-gray-200">#</th>
                        <th class="text-left px-4 py-2.5 text-xs font-semibold text-gray-500 border border-gray-200">Treatment</th>
                        <th class="text-right px-4 py-2.5 text-xs font-semibold text-gray-500 border border-gray-200">Sessions</th>
                        <th class="text-right px-4 py-2.5 text-xs font-semibold text-gray-500 border border-gray-200">Revenue</th>
                        <th class="text-right px-4 py-2.5 text-xs font-semibold text-gray-500 border border-gray-200">% of Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($topTreatments as $i => $t)
                    <tr class="{{ $loop->even ? 'bg-gray-50/50' : '' }}">
                        <td class="px-4 py-2.5 border border-gray-200 text-gray-400 text-xs">{{ $i + 1 }}</td>
                        <td class="px-4 py-2.5 border border-gray-200 font-medium text-gray-800">{{ $t->name }}</td>
                        <td class="px-4 py-2.5 border border-gray-200 text-right text-gray-600">{{ $t->sessions }}</td>
                        <td class="px-4 py-2.5 border border-gray-200 text-right font-semibold text-gray-800">Rs. {{ number_format($t->revenue, 2) }}</td>
                        <td class="px-4 py-2.5 border border-gray-200 text-right text-gray-500">
                            {{ $monthStats['total_invoiced'] > 0 ? round($t->revenue / $monthStats['total_invoiced'] * 100, 1) : 0 }}%
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif

        {{-- Invoice List --}}
        @if($monthlyInvoices->isNotEmpty())
        <div>
            <h2 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4 pb-2 border-b border-gray-200">All Invoices ({{ $monthlyInvoices->count() }})</h2>
            <table class="w-full text-sm border-collapse">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="text-left px-3 py-2.5 text-xs font-semibold text-gray-500 border border-gray-200">Invoice No.</th>
                        <th class="text-left px-3 py-2.5 text-xs font-semibold text-gray-500 border border-gray-200">Date</th>
                        <th class="text-left px-3 py-2.5 text-xs font-semibold text-gray-500 border border-gray-200">Patient</th>
                        <th class="text-right px-3 py-2.5 text-xs font-semibold text-gray-500 border border-gray-200">Total</th>
                        <th class="text-right px-3 py-2.5 text-xs font-semibold text-gray-500 border border-gray-200">Paid</th>
                        <th class="text-right px-3 py-2.5 text-xs font-semibold text-gray-500 border border-gray-200">Balance</th>
                        <th class="text-left px-3 py-2.5 text-xs font-semibold text-gray-500 border border-gray-200">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($monthlyInvoices as $inv)
                    <tr class="{{ $loop->even ? 'bg-gray-50/50' : '' }}">
                        <td class="px-3 py-2 border border-gray-200 font-mono text-xs font-semibold text-rose-600">{{ $inv->invoice_number }}</td>
                        <td class="px-3 py-2 border border-gray-200 text-xs text-gray-500">{{ $inv->created_at->format('d M') }}</td>
                        <td class="px-3 py-2 border border-gray-200 text-sm font-medium text-gray-800">{{ $inv->patient->full_name }}</td>
                        <td class="px-3 py-2 border border-gray-200 text-right text-sm text-gray-800">Rs. {{ number_format($inv->total, 2) }}</td>
                        <td class="px-3 py-2 border border-gray-200 text-right text-sm text-green-600 font-semibold">Rs. {{ number_format($inv->paid_amount, 2) }}</td>
                        <td class="px-3 py-2 border border-gray-200 text-right text-sm font-semibold {{ $inv->balance > 0 ? 'text-red-500' : 'text-gray-400' }}">Rs. {{ number_format($inv->balance, 2) }}</td>
                        <td class="px-3 py-2 border border-gray-200 text-xs font-semibold {{ $inv->status === 'paid' ? 'text-green-600' : ($inv->status === 'partial' ? 'text-yellow-600' : 'text-gray-500') }}">{{ $inv->status_label }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="bg-gray-100 font-bold">
                        <td colspan="3" class="px-3 py-2.5 border border-gray-200 text-gray-700">TOTAL</td>
                        <td class="px-3 py-2.5 border border-gray-200 text-right text-gray-800">Rs. {{ number_format($monthStats['total_invoiced'], 2) }}</td>
                        <td class="px-3 py-2.5 border border-gray-200 text-right text-green-700">Rs. {{ number_format($monthStats['total_collected'], 2) }}</td>
                        <td class="px-3 py-2.5 border border-gray-200 text-right {{ $monthStats['total_balance'] > 0 ? 'text-red-600' : 'text-gray-500' }}">Rs. {{ number_format($monthStats['total_balance'], 2) }}</td>
                        <td class="border border-gray-200"></td>
                    </tr>
                </tfoot>
            </table>
        </div>
        @endif

        {{-- Footer --}}
        <div class="border-t border-gray-200 pt-4 flex items-center justify-between text-xs text-gray-400">
            <span>Skin Care Clinic — Confidential</span>
            <span>Generated by {{ auth()->user()->name }} on {{ now()->format('d M Y, h:i A') }}</span>
        </div>

    </div>
</div>

</body>
</html>
