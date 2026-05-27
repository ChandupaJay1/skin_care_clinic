@extends('layouts.app')
@section('title', 'Invoices')
@section('page_title', 'Invoices')
@section('page_subtitle', 'Invoice history & management')

@section('content')

{{-- Stats --}}
<div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4">
        <p class="text-2xl font-bold text-gray-800">{{ $stats['total'] }}</p>
        <p class="text-xs text-gray-400 mt-0.5">Total Invoices</p>
    </div>
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4">
        <p class="text-2xl font-bold text-green-600">{{ $stats['paid'] }}</p>
        <p class="text-xs text-gray-400 mt-0.5">Paid</p>
    </div>
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4">
        <p class="text-2xl font-bold text-yellow-600">{{ $stats['partial'] }}</p>
        <p class="text-xs text-gray-400 mt-0.5">Partial</p>
    </div>
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4">
        <p class="text-2xl font-bold text-rose-600">Rs. {{ number_format($stats['revenue'], 2) }}</p>
        <p class="text-xs text-gray-400 mt-0.5">Total Revenue</p>
    </div>
</div>

{{-- Filters --}}
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 mb-6">
    <form method="GET" action="{{ route('invoices.index') }}" class="flex flex-wrap gap-3 items-end">
        <div class="flex-1 min-w-48">
            <label class="block text-xs text-gray-500 mb-1">Search</label>
            <input type="text" name="search" value="{{ request('search') }}"
                placeholder="Invoice no, patient name or ID…"
                class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-rose-400 bg-white">
        </div>
        <div>
            <label class="block text-xs text-gray-500 mb-1">Status</label>
            <select name="status"
                class="border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-rose-400 bg-white">
                <option value="">All</option>
                <option value="draft"     {{ request('status')=='draft'     ? 'selected':'' }}>Draft</option>
                <option value="paid"      {{ request('status')=='paid'      ? 'selected':'' }}>Paid</option>
                <option value="partial"   {{ request('status')=='partial'   ? 'selected':'' }}>Partial</option>
                <option value="cancelled" {{ request('status')=='cancelled' ? 'selected':'' }}>Cancelled</option>
            </select>
        </div>
        <div>
            <label class="block text-xs text-gray-500 mb-1">From</label>
            <input type="date" name="from" value="{{ request('from') }}"
                class="border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-rose-400 bg-white">
        </div>
        <div>
            <label class="block text-xs text-gray-500 mb-1">To</label>
            <input type="date" name="to" value="{{ request('to') }}"
                class="border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-rose-400 bg-white">
        </div>
        <button type="submit"
            class="bg-rose-500 hover:bg-rose-600 text-white text-sm font-medium px-5 py-2 rounded-xl transition self-end">
            Filter
        </button>
        @if(request()->hasAny(['search','status','from','to']))
        <a href="{{ route('invoices.index') }}"
            class="text-sm text-gray-500 border border-gray-200 bg-white hover:bg-gray-50 px-4 py-2 rounded-xl transition self-end">
            Clear
        </a>
        @endif

        @if(auth()->user()->hasRole(['admin','receptionist']))
        <a href="{{ route('invoices.create') }}"
            class="ml-auto flex items-center gap-2 bg-rose-500 hover:bg-rose-600 text-white text-sm font-semibold px-4 py-2 rounded-xl transition shadow-sm shadow-rose-200 self-end">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            New Invoice
        </a>
        @endif
    </form>
</div>

{{-- Table --}}
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    @if($invoices->isEmpty())
    <div class="text-center py-16">
        <div class="w-14 h-14 bg-gray-50 rounded-2xl flex items-center justify-center mx-auto mb-4">
            <svg class="w-7 h-7 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
        </div>
        <p class="text-gray-500 font-medium mb-1">No invoices found</p>
        <p class="text-gray-400 text-sm mb-4">Create your first invoice to get started.</p>
        @if(auth()->user()->hasRole(['admin','receptionist']))
        <a href="{{ route('invoices.create') }}"
            class="inline-flex items-center gap-2 bg-rose-500 hover:bg-rose-600 text-white text-sm font-medium px-4 py-2 rounded-xl transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            New Invoice
        </a>
        @endif
    </div>
    @else
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100">
                    <th class="text-left px-5 py-3 text-xs font-semibold text-gray-400 uppercase tracking-wide">Invoice</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-gray-400 uppercase tracking-wide">Patient</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-gray-400 uppercase tracking-wide">Items</th>
                    <th class="text-right px-5 py-3 text-xs font-semibold text-gray-400 uppercase tracking-wide">Total</th>
                    <th class="text-right px-5 py-3 text-xs font-semibold text-gray-400 uppercase tracking-wide">Paid</th>
                    <th class="text-right px-5 py-3 text-xs font-semibold text-gray-400 uppercase tracking-wide">Balance</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-gray-400 uppercase tracking-wide">Status</th>
                    <th class="px-5 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @foreach($invoices as $inv)
                <tr class="hover:bg-gray-50/50 transition">
                    <td class="px-5 py-3.5">
                        <p class="font-mono text-xs font-semibold text-rose-600">{{ $inv->invoice_number }}</p>
                        <p class="text-xs text-gray-400 mt-0.5">{{ $inv->created_at->format('d M Y') }}</p>
                    </td>
                    <td class="px-5 py-3.5">
                        <p class="font-medium text-gray-800 text-sm">{{ $inv->patient->full_name }}</p>
                        <p class="text-xs text-gray-400">{{ $inv->patient->patient_id }}</p>
                    </td>
                    <td class="px-5 py-3.5 text-xs text-gray-500">
                        {{ $inv->items->count() }} item{{ $inv->items->count() != 1 ? 's' : '' }}
                    </td>
                    <td class="px-5 py-3.5 text-right font-semibold text-gray-800 text-sm">
                        Rs. {{ number_format($inv->total, 2) }}
                    </td>
                    <td class="px-5 py-3.5 text-right text-green-600 font-semibold text-sm">
                        Rs. {{ number_format($inv->paid_amount, 2) }}
                    </td>
                    <td class="px-5 py-3.5 text-right font-semibold text-sm {{ $inv->balance > 0 ? 'text-red-500' : 'text-gray-400' }}">
                        Rs. {{ number_format($inv->balance, 2) }}
                    </td>
                    <td class="px-5 py-3.5">
                        <span class="text-xs px-2.5 py-1 rounded-full font-medium {{ $inv->status_color }}">
                            {{ $inv->status_label }}
                        </span>
                    </td>
                    <td class="px-5 py-3.5">
                        <div class="flex items-center gap-1.5 justify-end">
                            <a href="{{ route('invoices.show', $inv) }}"
                                class="w-7 h-7 flex items-center justify-center rounded-lg text-gray-400 hover:text-rose-500 hover:bg-rose-50 transition" title="View">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </a>
                            <a href="{{ route('invoices.print', $inv) }}" target="_blank"
                                class="w-7 h-7 flex items-center justify-center rounded-lg text-gray-400 hover:text-gray-700 hover:bg-gray-100 transition" title="Print">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                                </svg>
                            </a>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @if($invoices->hasPages())
    <div class="px-5 py-4 border-t border-gray-100">
        {{ $invoices->links() }}
    </div>
    @endif
    @endif
</div>

@endsection
