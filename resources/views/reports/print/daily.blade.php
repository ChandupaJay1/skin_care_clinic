<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daily Summary Report — {{ $date->format('d M Y') }}</title>
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
    <a href="{{ route('reports.daily', ['date' => $date->format('Y-m-d')]) }}"
        class="flex items-center gap-2 bg-white border border-gray-200 text-gray-600 hover:bg-gray-50 text-sm px-4 py-2 rounded-xl shadow transition">
        ← Back
    </a>
</div>

<div class="max-w-4xl mx-auto bg-white shadow-xl rounded-2xl overflow-hidden">

    {{-- Report Header --}}
    <div class="bg-gradient-to-r from-gray-800 to-gray-700 px-8 py-6">
        <div class="flex items-start justify-between">
            <div>
                <div class="flex items-center gap-3 mb-2">
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
            </div>
            <div class="text-right">
                <p class="text-gray-400 text-xs uppercase tracking-widest mb-1">Daily Summary Report</p>
                <p class="text-white text-xl font-bold">{{ $date->format('d F Y') }}</p>
                <p class="text-gray-400 text-xs mt-1">{{ $date->format('l') }}</p>
                <p class="text-gray-500 text-xs mt-2">Generated: {{ now()->format('d M Y, h:i A') }}</p>
            </div>
        </div>
    </div>

    <div class="p-8 space-y-8">

        {{-- Summary KPIs --}}
        <div>
            <h2 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4 pb-2 border-b border-gray-200">Summary</h2>
            <div class="grid grid-cols-4 gap-4">
                <div class="border border-gray-200 rounded-xl p-4 text-center">
                    <p class="text-3xl font-bold text-gray-800">{{ $apptStats['total'] }}</p>
                    <p class="text-xs text-gray-500 mt-1 font-medium">Appointments</p>
                    <div class="mt-2 space-y-0.5">
                        <p class="text-xs text-green-600">{{ $apptStats['completed'] }} completed</p>
                        @if($apptStats['cancelled'])<p class="text-xs text-red-500">{{ $apptStats['cancelled'] }} cancelled</p>@endif
                        @if($apptStats['no_show'])<p class="text-xs text-yellow-600">{{ $apptStats['no_show'] }} no-show</p>@endif
                    </div>
                </div>
                <div class="border border-gray-200 rounded-xl p-4 text-center">
                    <p class="text-2xl font-bold text-gray-800">Rs.{{ number_format($revenueStats['total_collected'], 0) }}</p>
                    <p class="text-xs text-gray-500 mt-1 font-medium">Collected</p>
                    <p class="text-xs text-gray-400 mt-1">of Rs.{{ number_format($revenueStats['total_invoiced'], 0) }}</p>
                </div>
                <div class="border border-gray-200 rounded-xl p-4 text-center">
                    <p class="text-3xl font-bold text-gray-800">{{ $revenueStats['invoice_count'] }}</p>
                    <p class="text-xs text-gray-500 mt-1 font-medium">Invoices</p>
                    <div class="mt-2 space-y-0.5">
                        <p class="text-xs text-green-600">{{ $revenueStats['paid_count'] }} paid</p>
                        @if($revenueStats['partial_count'])<p class="text-xs text-yellow-600">{{ $revenueStats['partial_count'] }} partial</p>@endif
                    </div>
                </div>
                <div class="border border-gray-200 rounded-xl p-4 text-center">
                    <p class="text-3xl font-bold text-gray-800">{{ $newPatients->count() }}</p>
                    <p class="text-xs text-gray-500 mt-1 font-medium">New Patients</p>
                    @if($revenueStats['total_balance'] > 0)
                    <p class="text-xs text-red-500 mt-2">Rs.{{ number_format($revenueStats['total_balance'], 0) }} outstanding</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- Payment Breakdown --}}
        @if($paymentBreakdown->isNotEmpty())
        <div>
            <h2 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4 pb-2 border-b border-gray-200">Payment Method Breakdown</h2>
            <table class="w-full text-sm border-collapse">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="text-left px-4 py-2.5 text-xs font-semibold text-gray-500 border border-gray-200">Method</th>
                        <th class="text-center px-4 py-2.5 text-xs font-semibold text-gray-500 border border-gray-200">Invoices</th>
                        <th class="text-right px-4 py-2.5 text-xs font-semibold text-gray-500 border border-gray-200">Amount Collected</th>
                    </tr>
                </thead>
                <tbody>
                    @php $labels = ['cash'=>'Cash','card'=>'Card','bank_transfer'=>'Bank Transfer','other'=>'Other']; @endphp
                    @foreach($paymentBreakdown as $method => $data)
                    <tr>
                        <td class="px-4 py-2.5 border border-gray-200 font-medium text-gray-700">{{ $labels[$method] ?? ucfirst($method) }}</td>
                        <td class="px-4 py-2.5 border border-gray-200 text-center text-gray-600">{{ $data['count'] }}</td>
                        <td class="px-4 py-2.5 border border-gray-200 text-right font-semibold text-gray-800">Rs. {{ number_format($data['amount'], 2) }}</td>
                    </tr>
                    @endforeach
                    <tr class="bg-gray-50 font-bold">
                        <td class="px-4 py-2.5 border border-gray-200 text-gray-700">Total</td>
                        <td class="px-4 py-2.5 border border-gray-200 text-center text-gray-700">{{ $revenueStats['invoice_count'] }}</td>
                        <td class="px-4 py-2.5 border border-gray-200 text-right text-gray-800">Rs. {{ number_format($revenueStats['total_collected'], 2) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        @endif

        {{-- Appointments --}}
        @if($appointments->isNotEmpty())
        <div>
            <h2 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4 pb-2 border-b border-gray-200">Appointments ({{ $appointments->count() }})</h2>
            <table class="w-full text-sm border-collapse">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="text-left px-4 py-2.5 text-xs font-semibold text-gray-500 border border-gray-200">Time</th>
                        <th class="text-left px-4 py-2.5 text-xs font-semibold text-gray-500 border border-gray-200">Booking #</th>
                        <th class="text-left px-4 py-2.5 text-xs font-semibold text-gray-500 border border-gray-200">Patient</th>
                        <th class="text-left px-4 py-2.5 text-xs font-semibold text-gray-500 border border-gray-200">Doctor</th>
                        <th class="text-left px-4 py-2.5 text-xs font-semibold text-gray-500 border border-gray-200">Treatment</th>
                        <th class="text-left px-4 py-2.5 text-xs font-semibold text-gray-500 border border-gray-200">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($appointments as $appt)
                    <tr class="{{ $loop->even ? 'bg-gray-50/50' : '' }}">
                        <td class="px-4 py-2.5 border border-gray-200 font-mono text-xs font-semibold text-gray-700">{{ $appt->formatted_time }}</td>
                        <td class="px-4 py-2.5 border border-gray-200 font-mono text-xs text-gray-600">{{ $appt->booking_number }}</td>
                        <td class="px-4 py-2.5 border border-gray-200 font-medium text-gray-800">{{ $appt->patient->full_name }}</td>
                        <td class="px-4 py-2.5 border border-gray-200 text-gray-600">Dr. {{ $appt->doctor->full_name }}</td>
                        <td class="px-4 py-2.5 border border-gray-200 text-gray-500 text-xs">{{ $appt->treatment?->name ?? '—' }}</td>
                        <td class="px-4 py-2.5 border border-gray-200 text-xs font-semibold">
                            @php $sc=['scheduled'=>'text-blue-600','completed'=>'text-green-600','cancelled'=>'text-red-500','no_show'=>'text-yellow-600']; @endphp
                            <span class="{{ $sc[$appt->status] ?? 'text-gray-500' }}">{{ $appt->status_label }}</span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif

        {{-- Invoices --}}
        @if($invoices->isNotEmpty())
        <div>
            <h2 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4 pb-2 border-b border-gray-200">Invoices Issued ({{ $invoices->count() }})</h2>
            <table class="w-full text-sm border-collapse">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="text-left px-4 py-2.5 text-xs font-semibold text-gray-500 border border-gray-200">Invoice No.</th>
                        <th class="text-left px-4 py-2.5 text-xs font-semibold text-gray-500 border border-gray-200">Patient</th>
                        <th class="text-right px-4 py-2.5 text-xs font-semibold text-gray-500 border border-gray-200">Total</th>
                        <th class="text-right px-4 py-2.5 text-xs font-semibold text-gray-500 border border-gray-200">Paid</th>
                        <th class="text-right px-4 py-2.5 text-xs font-semibold text-gray-500 border border-gray-200">Balance</th>
                        <th class="text-left px-4 py-2.5 text-xs font-semibold text-gray-500 border border-gray-200">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($invoices as $inv)
                    <tr class="{{ $loop->even ? 'bg-gray-50/50' : '' }}">
                        <td class="px-4 py-2.5 border border-gray-200 font-mono text-xs font-semibold text-rose-600">{{ $inv->invoice_number }}</td>
                        <td class="px-4 py-2.5 border border-gray-200 font-medium text-gray-800">{{ $inv->patient->full_name }}</td>
                        <td class="px-4 py-2.5 border border-gray-200 text-right text-gray-800">Rs. {{ number_format($inv->total, 2) }}</td>
                        <td class="px-4 py-2.5 border border-gray-200 text-right text-green-600 font-semibold">Rs. {{ number_format($inv->paid_amount, 2) }}</td>
                        <td class="px-4 py-2.5 border border-gray-200 text-right font-semibold {{ $inv->balance > 0 ? 'text-red-500' : 'text-gray-400' }}">Rs. {{ number_format($inv->balance, 2) }}</td>
                        <td class="px-4 py-2.5 border border-gray-200 text-xs font-semibold {{ $inv->status === 'paid' ? 'text-green-600' : ($inv->status === 'partial' ? 'text-yellow-600' : 'text-gray-500') }}">{{ $inv->status_label }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="bg-gray-100 font-bold">
                        <td colspan="2" class="px-4 py-2.5 border border-gray-200 text-gray-700">TOTAL</td>
                        <td class="px-4 py-2.5 border border-gray-200 text-right text-gray-800">Rs. {{ number_format($revenueStats['total_invoiced'], 2) }}</td>
                        <td class="px-4 py-2.5 border border-gray-200 text-right text-green-700">Rs. {{ number_format($revenueStats['total_collected'], 2) }}</td>
                        <td class="px-4 py-2.5 border border-gray-200 text-right {{ $revenueStats['total_balance'] > 0 ? 'text-red-600' : 'text-gray-500' }}">Rs. {{ number_format($revenueStats['total_balance'], 2) }}</td>
                        <td class="border border-gray-200"></td>
                    </tr>
                </tfoot>
            </table>
        </div>
        @endif

        {{-- New Patients --}}
        @if($newPatients->isNotEmpty())
        <div>
            <h2 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4 pb-2 border-b border-gray-200">New Patients Registered ({{ $newPatients->count() }})</h2>
            <table class="w-full text-sm border-collapse">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="text-left px-4 py-2.5 text-xs font-semibold text-gray-500 border border-gray-200">Patient ID</th>
                        <th class="text-left px-4 py-2.5 text-xs font-semibold text-gray-500 border border-gray-200">Name</th>
                        <th class="text-left px-4 py-2.5 text-xs font-semibold text-gray-500 border border-gray-200">Gender</th>
                        <th class="text-left px-4 py-2.5 text-xs font-semibold text-gray-500 border border-gray-200">Age</th>
                        <th class="text-left px-4 py-2.5 text-xs font-semibold text-gray-500 border border-gray-200">Phone</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($newPatients as $np)
                    <tr class="{{ $loop->even ? 'bg-gray-50/50' : '' }}">
                        <td class="px-4 py-2.5 border border-gray-200 font-mono text-xs text-rose-600 font-semibold">{{ $np->patient_id }}</td>
                        <td class="px-4 py-2.5 border border-gray-200 font-medium text-gray-800">{{ $np->full_name }}</td>
                        <td class="px-4 py-2.5 border border-gray-200 text-gray-600">{{ ucfirst($np->gender) }}</td>
                        <td class="px-4 py-2.5 border border-gray-200 text-gray-600">{{ $np->age }} yrs</td>
                        <td class="px-4 py-2.5 border border-gray-200 text-gray-600">{{ $np->phone }}</td>
                    </tr>
                    @endforeach
                </tbody>
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
