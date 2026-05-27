@extends('layouts.app')
@section('title', 'Outstanding Balances')
@section('page_title', 'Outstanding Balances')
@section('page_subtitle', 'Patients with unpaid or partial invoices')

@section('content')

{{-- Summary cards --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-red-50 rounded-2xl border border-red-100 shadow-sm p-5">
        <p class="text-2xl font-bold text-red-600">Rs. {{ number_format($summary['total_outstanding'], 2) }}</p>
        <p class="text-xs text-red-400 mt-0.5">Total Outstanding</p>
    </div>
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
        <p class="text-2xl font-bold text-gray-800">{{ $summary['patient_count'] }}</p>
        <p class="text-xs text-gray-400 mt-0.5">Patients Owing</p>
    </div>
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
        <p class="text-2xl font-bold text-yellow-600">Rs. {{ number_format($summary['overdue_7'], 2) }}</p>
        <p class="text-xs text-gray-400 mt-0.5">Overdue 7+ Days</p>
    </div>
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
        <p class="text-2xl font-bold text-red-600">Rs. {{ number_format($summary['overdue_30'], 2) }}</p>
        <p class="text-xs text-gray-400 mt-0.5">Overdue 30+ Days</p>
    </div>
</div>

{{-- Filters --}}
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 mb-6">
    <form method="GET" action="{{ route('reports.outstanding') }}" class="flex flex-wrap gap-3 items-end">
        <div class="flex-1 min-w-48">
            <label class="block text-xs text-gray-500 mb-1">Search</label>
            <input type="text" name="search" value="{{ request('search') }}"
                placeholder="Patient name, ID or invoice no…"
                class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-rose-400 bg-white">
        </div>
        <div>
            <label class="block text-xs text-gray-500 mb-1">Overdue by</label>
            <select name="age"
                class="border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-rose-400 bg-white">
                <option value="">All</option>
                <option value="7"  {{ request('age')=='7'  ? 'selected':'' }}>7+ days</option>
                <option value="14" {{ request('age')=='14' ? 'selected':'' }}>14+ days</option>
                <option value="30" {{ request('age')=='30' ? 'selected':'' }}>30+ days</option>
                <option value="60" {{ request('age')=='60' ? 'selected':'' }}>60+ days</option>
            </select>
        </div>
        <button type="submit"
            class="bg-rose-500 hover:bg-rose-600 text-white text-sm font-medium px-5 py-2 rounded-xl transition self-end">
            Filter
        </button>
        @if(request()->hasAny(['search','age']))
        <a href="{{ route('reports.outstanding') }}"
            class="text-sm text-gray-500 border border-gray-200 bg-white hover:bg-gray-50 px-4 py-2 rounded-xl transition self-end">
            Clear
        </a>
        @endif
        <a href="{{ route('reports.outstanding.print', request()->only(['age'])) }}" target="_blank"
            class="ml-auto flex items-center gap-2 text-sm text-white bg-gray-800 hover:bg-gray-900 px-4 py-2 rounded-xl transition shadow-sm self-end">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
            </svg>
            Print Report
        </a>
    </form>
</div>

{{-- Table --}}
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    @if($invoices->isEmpty())
    <div class="text-center py-16">
        <div class="w-14 h-14 bg-green-50 rounded-2xl flex items-center justify-center mx-auto mb-4">
            <svg class="w-7 h-7 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <p class="text-gray-500 font-medium mb-1">All clear!</p>
        <p class="text-gray-400 text-sm">No outstanding balances found.</p>
    </div>
    @else
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100">
                    <th class="text-left px-5 py-3 text-xs font-semibold text-gray-400 uppercase tracking-wide">Invoice</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-gray-400 uppercase tracking-wide">Patient</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-gray-400 uppercase tracking-wide">Date</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-gray-400 uppercase tracking-wide">Age</th>
                    <th class="text-right px-5 py-3 text-xs font-semibold text-gray-400 uppercase tracking-wide">Total</th>
                    <th class="text-right px-5 py-3 text-xs font-semibold text-gray-400 uppercase tracking-wide">Paid</th>
                    <th class="text-right px-5 py-3 text-xs font-semibold text-gray-400 uppercase tracking-wide">Balance</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-gray-400 uppercase tracking-wide">Status</th>
                    <th class="px-5 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @foreach($invoices as $inv)
                @php
                    $ageDays = $inv->created_at->diffInDays(now());
                    $ageColor = $ageDays >= 30 ? 'text-red-600 font-semibold' : ($ageDays >= 7 ? 'text-yellow-600 font-semibold' : 'text-gray-500');
                @endphp
                <tr class="hover:bg-gray-50/50 transition">
                    <td class="px-5 py-3.5">
                        <span class="font-mono text-xs font-semibold text-rose-600">{{ $inv->invoice_number }}</span>
                    </td>
                    <td class="px-5 py-3.5">
                        <a href="{{ route('patients.show', $inv->patient) }}"
                            class="font-medium text-gray-800 hover:text-rose-600 transition text-sm">
                            {{ $inv->patient->full_name }}
                        </a>
                        <p class="text-xs text-gray-400">{{ $inv->patient->patient_id }} · {{ $inv->patient->phone }}</p>
                    </td>
                    <td class="px-5 py-3.5 text-xs text-gray-500">
                        {{ $inv->created_at->format('d M Y') }}
                    </td>
                    <td class="px-5 py-3.5 text-xs {{ $ageColor }}">
                        {{ $ageDays }}d ago
                    </td>
                    <td class="px-5 py-3.5 text-right text-sm font-semibold text-gray-800">
                        Rs. {{ number_format($inv->total, 2) }}
                    </td>
                    <td class="px-5 py-3.5 text-right text-sm font-semibold text-green-600">
                        Rs. {{ number_format($inv->paid_amount, 2) }}
                    </td>
                    <td class="px-5 py-3.5 text-right text-sm font-bold text-red-600">
                        Rs. {{ number_format($inv->balance, 2) }}
                    </td>
                    <td class="px-5 py-3.5">
                        <span class="text-xs px-2.5 py-1 rounded-full font-medium {{ $inv->status_color }}">
                            {{ $inv->status_label }}
                        </span>
                    </td>
                    <td class="px-5 py-3.5">
                        <a href="{{ route('invoices.show', $inv) }}"
                            class="w-7 h-7 flex items-center justify-center rounded-lg text-gray-400 hover:text-rose-500 hover:bg-rose-50 transition" title="View">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="bg-red-50 border-t-2 border-red-200 font-bold">
                    <td colspan="6" class="px-5 py-3 text-sm text-gray-600">Total Outstanding</td>
                    <td class="px-5 py-3 text-right text-sm text-red-600">
                        Rs. {{ number_format($invoices->sum('balance'), 2) }}
                    </td>
                    <td colspan="2"></td>
                </tr>
            </tfoot>
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
