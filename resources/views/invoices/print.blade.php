<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice — {{ $invoice->invoice_number }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @verbatim
        @media print {
            body  { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .no-print { display: none !important; }
            @page { margin: 14mm; size: A4; }
        }
        @endverbatim
    </style>
</head>
<body class="bg-gray-100 min-h-screen flex items-start justify-center py-8 px-4">

{{-- Toolbar --}}
<div class="no-print fixed top-4 right-4 flex gap-2 z-50">
    <button onclick="window.print()"
        class="flex items-center gap-2 bg-rose-500 hover:bg-rose-600 text-white text-sm font-semibold px-4 py-2 rounded-xl shadow-lg transition">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
        </svg>
        Print
    </button>
    <a href="{{ route('invoices.show', $invoice) }}"
        class="flex items-center gap-2 bg-white border border-gray-200 text-gray-600 hover:bg-gray-50 text-sm font-medium px-4 py-2 rounded-xl shadow transition">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
        </svg>
        Close
    </a>
</div>

{{-- Invoice paper --}}
<div class="bg-white w-full max-w-2xl rounded-2xl shadow-xl overflow-hidden">

    {{-- Header --}}
    <div class="bg-gradient-to-r from-rose-600 to-pink-500 px-8 py-7">
        <div class="flex items-start justify-between">
            <div>
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-white font-bold text-base leading-tight">Skin Care Clinic</p>
                        <p class="text-rose-100 text-xs">Clinic Management System</p>
                    </div>
                </div>
                <p class="text-rose-100 text-xs">Date: {{ $invoice->created_at->format('d M Y') }}</p>
            </div>
            <div class="text-right">
                <p class="text-rose-100 text-xs font-medium mb-1">INVOICE</p>
                <p class="text-white text-xl font-bold font-mono tracking-wide">{{ $invoice->invoice_number }}</p>
                @php
                    $statusColors = [
                        'paid'      => 'bg-green-100 text-green-700',
                        'partial'   => 'bg-yellow-100 text-yellow-700',
                        'cancelled' => 'bg-red-100 text-red-700',
                        'draft'     => 'bg-gray-100 text-gray-600',
                    ];
                @endphp
                <span class="inline-block mt-2 text-xs font-semibold px-3 py-1 rounded-full {{ $statusColors[$invoice->status] ?? 'bg-gray-100 text-gray-600' }}">
                    {{ $invoice->status_label }}
                </span>
            </div>
        </div>
    </div>

    <div class="px-8 py-6 space-y-6">

        {{-- Patient & Payment info --}}
        <div class="grid grid-cols-2 gap-6">
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest mb-2">Billed To</p>
                <p class="text-sm font-bold text-gray-800">{{ $invoice->patient->full_name }}</p>
                <p class="text-xs text-gray-500 mt-0.5">{{ $invoice->patient->patient_id }}</p>
                <p class="text-xs text-gray-500">{{ $invoice->patient->phone }}</p>
                @if($invoice->patient->email)
                <p class="text-xs text-gray-500">{{ $invoice->patient->email }}</p>
                @endif
            </div>
            <div class="text-right">
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest mb-2">Payment Info</p>
                <p class="text-sm font-medium text-gray-700">{{ $invoice->payment_method_label }}</p>
                @if($invoice->appointment)
                <p class="text-xs text-gray-500 mt-0.5">
                    Appt: #{{ $invoice->appointment->booking_number }}
                    — {{ $invoice->appointment->appointment_date->format('d M Y') }}
                </p>
                @if($invoice->appointment->doctor)
                <p class="text-xs text-gray-500">Dr. {{ $invoice->appointment->doctor->full_name }}</p>
                @endif
                @endif
            </div>
        </div>

        {{-- Items table --}}
        <div>
            <table class="w-full text-sm border-collapse">
                <thead>
                    <tr class="bg-gray-50 border-y border-gray-200">
                        <th class="text-left px-4 py-2.5 text-xs font-semibold text-gray-500 uppercase tracking-wide">#</th>
                        <th class="text-left px-4 py-2.5 text-xs font-semibold text-gray-500 uppercase tracking-wide">Description</th>
                        <th class="text-center px-4 py-2.5 text-xs font-semibold text-gray-500 uppercase tracking-wide">Qty</th>
                        <th class="text-right px-4 py-2.5 text-xs font-semibold text-gray-500 uppercase tracking-wide">Unit Price</th>
                        <th class="text-right px-4 py-2.5 text-xs font-semibold text-gray-500 uppercase tracking-wide">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($invoice->items as $i => $item)
                    <tr class="border-b border-gray-100">
                        <td class="px-4 py-3 text-xs text-gray-400">{{ $i + 1 }}</td>
                        <td class="px-4 py-3">
                            <p class="font-medium text-gray-800">{{ $item->description }}</p>
                            @if($item->treatment && $item->treatment->name !== $item->description)
                            <p class="text-xs text-gray-400">{{ $item->treatment->name }}</p>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-center text-gray-600">{{ $item->quantity }}</td>
                        <td class="px-4 py-3 text-right text-gray-600">Rs. {{ number_format($item->unit_price, 2) }}</td>
                        <td class="px-4 py-3 text-right font-semibold text-gray-800">Rs. {{ number_format($item->line_total, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Totals --}}
        <div class="flex justify-end">
            <div class="w-64 space-y-2">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">Subtotal</span>
                    <span class="font-medium text-gray-800">Rs. {{ number_format($invoice->subtotal, 2) }}</span>
                </div>
                @if($invoice->discount > 0)
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">
                        Discount{{ $invoice->discount_percent > 0 ? ' ('.$invoice->discount_percent.'%)' : '' }}
                    </span>
                    <span class="font-medium text-red-500">- Rs. {{ number_format($invoice->discount, 2) }}</span>
                </div>
                @endif
                <div class="flex justify-between text-base font-bold border-t border-gray-200 pt-2">
                    <span class="text-gray-700">Total</span>
                    <span class="text-gray-900">Rs. {{ number_format($invoice->total, 2) }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">Paid Amount</span>
                    <span class="font-semibold text-green-600">Rs. {{ number_format($invoice->paid_amount, 2) }}</span>
                </div>
                <div class="flex justify-between text-base font-bold border-t-2 border-gray-300 pt-2">
                    <span class="text-gray-700">Balance Due</span>
                    <span class="{{ $invoice->balance > 0 ? 'text-red-600' : 'text-green-600' }}">
                        Rs. {{ number_format($invoice->balance, 2) }}
                    </span>
                </div>
            </div>
        </div>

        {{-- Patient barcode --}}
        @if($invoice->patient->barcode_svg)
        <div class="border-t border-dashed border-gray-200 pt-5 flex items-center justify-between">
            <div>
                <p class="text-xs text-gray-400 uppercase tracking-widest mb-2">Patient Barcode</p>
                {!! $invoice->patient->barcode_svg !!}
                <p class="text-xs font-mono text-gray-400 mt-1">{{ $invoice->patient->barcode_value }}</p>
            </div>
            @if($invoice->notes)
            <div class="max-w-xs text-right">
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest mb-1">Notes</p>
                <p class="text-xs text-gray-600 leading-relaxed">{{ $invoice->notes }}</p>
            </div>
            @endif
        </div>
        @elseif($invoice->notes)
        <div class="border-t border-dashed border-gray-200 pt-5">
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest mb-1">Notes</p>
            <p class="text-xs text-gray-600 leading-relaxed">{{ $invoice->notes }}</p>
        </div>
        @endif

        {{-- Footer --}}
        <div class="border-t border-gray-100 pt-4 text-center">
            <p class="text-xs text-gray-400">Thank you for choosing Skin Care Clinic</p>
            <p class="text-xs text-gray-300 mt-0.5">Printed: {{ now()->format('d M Y, h:i A') }}</p>
        </div>

    </div>
</div>

</body>
</html>
