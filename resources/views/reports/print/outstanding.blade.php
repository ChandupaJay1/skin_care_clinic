<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Outstanding Balances Report — {{ now()->format('d M Y') }}</title>
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
    <a href="{{ route('reports.outstanding') }}"
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
                <p class="text-gray-400 text-xs uppercase tracking-widest mb-1">Outstanding Balances Report</p>
                <p class="text-white text-xl font-bold">As of {{ now()->format('d F Y') }}</p>
                <p class="text-gray-500 text-xs mt-2">Generated: {{ now()->format('d M Y, h:i A') }}</p>
            </div>
        </div>
    </div>

    <div class="p-8 space-y-8">

        {{-- Summary --}}
        <div>
            <h2 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4 pb-2 border-b border-gray-200">Summary</h2>
            <div class="grid grid-cols-4 gap-4">
                <div class="border-2 border-red-200 bg-red-50 rounded-xl p-4 text-center">
                    <p class="text-xl font-bold text-red-600">Rs.{{ number_format($summary['total_outstanding'], 0) }}</p>
                    <p class="text-xs text-red-400 mt-1 font-medium">Total Outstanding</p>
                </div>
                <div class="border border-gray-200 rounded-xl p-4 text-center">
                    <p class="text-3xl font-bold text-gray-800">{{ $summary['patient_count'] }}</p>
                    <p class="text-xs text-gray-500 mt-1 font-medium">Patients Owing</p>
                </div>
                <div class="border border-yellow-200 bg-yellow-50 rounded-xl p-4 text-center">
                    <p class="text-xl font-bold text-yellow-600">Rs.{{ number_format($summary['overdue_7'], 0) }}</p>
                    <p class="text-xs text-yellow-500 mt-1 font-medium">Overdue 7+ Days</p>
                </div>
                <div class="border border-red-200 bg-red-50 rounded-xl p-4 text-center">
                    <p class="text-xl font-bold text-red-600">Rs.{{ number_format($summary['overdue_30'], 0) }}</p>
                    <p class="text-xs text-red-400 mt-1 font-medium">Overdue 30+ Days</p>
                </div>
            </div>
        </div>

        {{-- Outstanding Invoices Table --}}
        @if($invoices->isEmpty())
        <div class="text-center py-12 border border-green-200 bg-green-50 rounded-xl">
            <p class="text-green-600 font-semibold text-lg">✓ All Clear</p>
            <p class="text-green-500 text-sm mt-1">No outstanding balances at this time.</p>
        </div>
        @else
        <div>
            <h2 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4 pb-2 border-b border-gray-200">
                Outstanding Invoices ({{ $invoices->count() }})
            </h2>
            <table class="w-full text-sm border-collapse">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="text-left px-3 py-2.5 text-xs font-semibold text-gray-500 border border-gray-200">Invoice No.</th>
                        <th class="text-left px-3 py-2.5 text-xs font-semibold text-gray-500 border border-gray-200">Patient</th>
                        <th class="text-left px-3 py-2.5 text-xs font-semibold text-gray-500 border border-gray-200">Phone</th>
                        <th class="text-left px-3 py-2.5 text-xs font-semibold text-gray-500 border border-gray-200">Invoice Date</th>
                        <th class="text-right px-3 py-2.5 text-xs font-semibold text-gray-500 border border-gray-200">Age</th>
                        <th class="text-right px-3 py-2.5 text-xs font-semibold text-gray-500 border border-gray-200">Total</th>
                        <th class="text-right px-3 py-2.5 text-xs font-semibold text-gray-500 border border-gray-200">Paid</th>
                        <th class="text-right px-3 py-2.5 text-xs font-semibold text-gray-500 border border-gray-200 bg-red-50">Balance Due</th>
                        <th class="text-left px-3 py-2.5 text-xs font-semibold text-gray-500 border border-gray-200">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($invoices as $inv)
                    @php
                        $ageDays  = $inv->created_at->diffInDays(now());
                        $ageClass = $ageDays >= 30 ? 'text-red-600 font-bold' : ($ageDays >= 7 ? 'text-yellow-600 font-semibold' : 'text-gray-500');
                        $rowBg    = $ageDays >= 30 ? 'bg-red-50/40' : ($ageDays >= 7 ? 'bg-yellow-50/30' : '');
                    @endphp
                    <tr class="{{ $rowBg }}">
                        <td class="px-3 py-2.5 border border-gray-200 font-mono text-xs font-semibold text-rose-600">{{ $inv->invoice_number }}</td>
                        <td class="px-3 py-2.5 border border-gray-200 font-medium text-gray-800 text-sm">{{ $inv->patient->full_name }}</td>
                        <td class="px-3 py-2.5 border border-gray-200 text-xs text-gray-500">{{ $inv->patient->phone }}</td>
                        <td class="px-3 py-2.5 border border-gray-200 text-xs text-gray-500">{{ $inv->created_at->format('d M Y') }}</td>
                        <td class="px-3 py-2.5 border border-gray-200 text-right text-xs {{ $ageClass }}">{{ $ageDays }}d</td>
                        <td class="px-3 py-2.5 border border-gray-200 text-right text-sm text-gray-700">Rs. {{ number_format($inv->total, 2) }}</td>
                        <td class="px-3 py-2.5 border border-gray-200 text-right text-sm text-green-600">Rs. {{ number_format($inv->paid_amount, 2) }}</td>
                        <td class="px-3 py-2.5 border border-gray-200 text-right text-sm font-bold text-red-600 bg-red-50/60">Rs. {{ number_format($inv->balance, 2) }}</td>
                        <td class="px-3 py-2.5 border border-gray-200 text-xs font-semibold {{ $inv->status === 'partial' ? 'text-yellow-600' : 'text-gray-500' }}">{{ $inv->status_label }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="bg-red-50 font-bold border-t-2 border-red-300">
                        <td colspan="7" class="px-3 py-3 border border-gray-200 text-gray-700 text-sm">TOTAL OUTSTANDING</td>
                        <td class="px-3 py-3 border border-gray-200 text-right text-red-700 text-base">Rs. {{ number_format($invoices->sum('balance'), 2) }}</td>
                        <td class="border border-gray-200"></td>
                    </tr>
                </tfoot>
            </table>

            {{-- Age breakdown note --}}
            <div class="mt-4 flex items-center gap-6 text-xs text-gray-500">
                <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded bg-gray-100 border border-gray-300 inline-block"></span> Under 7 days</span>
                <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded bg-yellow-100 border border-yellow-300 inline-block"></span> 7–29 days overdue</span>
                <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded bg-red-100 border border-red-300 inline-block"></span> 30+ days overdue</span>
            </div>
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
