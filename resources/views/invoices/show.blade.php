@extends('layouts.app')
@section('title', 'Invoice ' . $invoice->invoice_number)
@section('page_title', 'Invoice Detail')
@section('page_subtitle', $invoice->invoice_number)

@section('content')
<div class="max-w-3xl mx-auto space-y-5">

{{-- Header --}}
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="bg-gradient-to-r from-rose-500 to-pink-500 px-6 py-5">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-rose-100 text-xs font-medium mb-1">Invoice Number</p>
                <p class="text-white text-2xl font-bold font-mono tracking-wide">{{ $invoice->invoice_number }}</p>
                <p class="text-rose-100 text-xs mt-1">{{ $invoice->created_at->format('d M Y, h:i A') }}</p>
            </div>
            <span class="text-xs px-3 py-1.5 rounded-full font-semibold {{ $invoice->status_color }} border">
                {{ $invoice->status_label }}
            </span>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 gap-5">

    {{-- Patient --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-3">Patient</p>
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-full bg-rose-100 flex items-center justify-center text-rose-500 font-bold">
                {{ strtoupper(substr($invoice->patient->full_name, 0, 1)) }}
            </div>
            <div>
                <p class="text-sm font-semibold text-gray-800">{{ $invoice->patient->full_name }}</p>
                <p class="text-xs text-gray-400">{{ $invoice->patient->patient_id }} · {{ $invoice->patient->phone }}</p>
            </div>
        </div>
    </div>

    {{-- Payment info --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-3">Payment</p>
        <div class="space-y-1.5">
            <div class="flex justify-between text-sm">
                <span class="text-gray-500">Method</span>
                <span class="font-medium text-gray-800">{{ $invoice->payment_method_label }}</span>
            </div>
            @if($invoice->appointment)
            <div class="flex justify-between text-sm">
                <span class="text-gray-500">Appointment</span>
                <a href="{{ route('appointments.show', $invoice->appointment) }}"
                    class="font-medium text-rose-500 hover:text-rose-600">#{{ $invoice->appointment->booking_number }}</a>
            </div>
            @endif
        </div>
    </div>

</div>

{{-- Items table --}}
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="bg-gray-50 px-5 py-3 border-b border-gray-100">
        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Items</p>
    </div>
    <table class="w-full text-sm">
        <thead>
            <tr class="border-b border-gray-100">
                <th class="text-left px-5 py-2.5 text-xs text-gray-400 font-semibold">Description</th>
                <th class="text-center px-3 py-2.5 text-xs text-gray-400 font-semibold">Qty</th>
                <th class="text-right px-5 py-2.5 text-xs text-gray-400 font-semibold">Unit Price</th>
                <th class="text-right px-5 py-2.5 text-xs text-gray-400 font-semibold">Total</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @foreach($invoice->items as $item)
            <tr>
                <td class="px-5 py-3">
                    <p class="font-medium text-gray-800">{{ $item->description }}</p>
                    @if($item->treatment)
                    <p class="text-xs text-gray-400">{{ $item->treatment->name }}</p>
                    @endif
                </td>
                <td class="px-3 py-3 text-center text-gray-600">{{ $item->quantity }}</td>
                <td class="px-5 py-3 text-right text-gray-600">Rs. {{ number_format($item->unit_price, 2) }}</td>
                <td class="px-5 py-3 text-right font-semibold text-gray-800">Rs. {{ number_format($item->line_total, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Totals --}}
    <div class="border-t border-gray-100 px-5 py-4 space-y-2 bg-gray-50/50">
        <div class="flex justify-between text-sm">
            <span class="text-gray-500">Subtotal</span>
            <span class="font-medium text-gray-800">Rs. {{ number_format($invoice->subtotal, 2) }}</span>
        </div>
        @if($invoice->discount > 0)
        <div class="flex justify-between text-sm">
            <span class="text-gray-500">Discount
                {{ $invoice->discount_percent > 0 ? '('.$invoice->discount_percent.'%)' : '' }}
            </span>
            <span class="font-medium text-red-500">- Rs. {{ number_format($invoice->discount, 2) }}</span>
        </div>
        @endif
        <div class="flex justify-between text-base font-bold border-t border-gray-200 pt-2">
            <span class="text-gray-700">Total</span>
            <span class="text-gray-900">Rs. {{ number_format($invoice->total, 2) }}</span>
        </div>
        <div class="flex justify-between text-sm">
            <span class="text-gray-500">Paid</span>
            <span class="font-semibold text-green-600">Rs. {{ number_format($invoice->paid_amount, 2) }}</span>
        </div>
        <div class="flex justify-between text-base font-bold border-t border-gray-200 pt-2">
            <span class="text-gray-700">Balance Due</span>
            <span class="{{ $invoice->balance > 0 ? 'text-red-600' : 'text-green-600' }}">
                Rs. {{ number_format($invoice->balance, 2) }}
            </span>
        </div>
    </div>
</div>

@if($invoice->notes)
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-2">Notes</p>
    <p class="text-sm text-gray-700 leading-relaxed">{{ $invoice->notes }}</p>
</div>
@endif

{{-- Actions --}}
<div class="flex items-center gap-3">
    <a href="{{ route('invoices.index') }}"
        class="text-sm text-gray-500 hover:text-rose-500 flex items-center gap-1.5 transition">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        Back to Invoices
    </a>

    <div class="ml-auto flex items-center gap-2">
        @if(auth()->user()->isAdmin() && $invoice->status !== 'cancelled')
        <form method="POST" action="{{ route('invoices.destroy', $invoice) }}"
            onsubmit="return confirm('Cancel this invoice?')">
            @csrf @method('DELETE')
            <button type="submit"
                class="text-xs font-medium text-red-500 border border-red-200 bg-white hover:bg-red-50 px-3 py-2 rounded-xl transition">
                Cancel Invoice
            </button>
        </form>
        @endif

        <a href="{{ route('invoices.print', $invoice) }}" target="_blank"
            class="flex items-center gap-2 bg-gray-800 hover:bg-gray-900 text-white text-sm font-semibold px-4 py-2 rounded-xl transition shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
            </svg>
            Print Invoice
        </a>
    </div>
</div>

</div>
@endsection
