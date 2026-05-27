@extends('layouts.app')
@section('title', 'Daily Summary — ' . $date->format('d M Y'))
@section('page_title', 'Daily Summary')
@section('page_subtitle', $date->format('l, d F Y'))

@section('content')

{{-- Date navigator --}}
<div class="flex items-center gap-3 mb-6">
    <a href="{{ route('reports.daily', ['date' => $date->copy()->subDay()->format('Y-m-d')]) }}"
        class="w-9 h-9 flex items-center justify-center rounded-xl border border-gray-200 bg-white hover:bg-gray-50 transition text-gray-500">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
    </a>
    <form method="GET" action="{{ route('reports.daily') }}">
        <input type="date" name="date" value="{{ $date->format('Y-m-d') }}"
            onchange="this.form.submit()"
            class="border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-rose-400 bg-white">
    </form>
    <a href="{{ route('reports.daily', ['date' => $date->copy()->addDay()->format('Y-m-d')]) }}"
        class="w-9 h-9 flex items-center justify-center rounded-xl border border-gray-200 bg-white hover:bg-gray-50 transition text-gray-500">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
    </a>
    @if(!$date->isToday())
    <a href="{{ route('reports.daily') }}"
        class="text-xs text-rose-500 hover:text-rose-600 font-medium px-3 py-2 rounded-xl border border-rose-200 bg-rose-50 transition">
        Today
    </a>
    @endif

    <a href="{{ route('reports.daily.print', ['date' => $date->format('Y-m-d')]) }}" target="_blank"
        class="ml-auto flex items-center gap-2 text-sm text-white bg-gray-800 hover:bg-gray-900 px-4 py-2 rounded-xl transition shadow-sm no-print">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
        </svg>
        Print Report
    </a>
</div>

{{-- Top stats --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
        <div class="w-9 h-9 bg-blue-50 rounded-xl flex items-center justify-center mb-3">
            <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
        </div>
        <p class="text-2xl font-bold text-gray-800">{{ $apptStats['total'] }}</p>
        <p class="text-xs text-gray-400 mt-0.5">Total Appointments</p>
        <div class="flex gap-2 mt-2 flex-wrap">
            <span class="text-xs bg-green-100 text-green-700 px-2 py-0.5 rounded-full">{{ $apptStats['completed'] }} done</span>
            @if($apptStats['cancelled'] > 0)
            <span class="text-xs bg-red-100 text-red-600 px-2 py-0.5 rounded-full">{{ $apptStats['cancelled'] }} cancelled</span>
            @endif
            @if($apptStats['no_show'] > 0)
            <span class="text-xs bg-yellow-100 text-yellow-700 px-2 py-0.5 rounded-full">{{ $apptStats['no_show'] }} no-show</span>
            @endif
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
        <div class="w-9 h-9 bg-green-50 rounded-xl flex items-center justify-center mb-3">
            <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <p class="text-2xl font-bold text-gray-800">Rs. {{ number_format($revenueStats['total_collected'], 2) }}</p>
        <p class="text-xs text-gray-400 mt-0.5">Revenue Collected</p>
        <p class="text-xs text-gray-400 mt-1">of Rs. {{ number_format($revenueStats['total_invoiced'], 2) }} invoiced</p>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
        <div class="w-9 h-9 bg-orange-50 rounded-xl flex items-center justify-center mb-3">
            <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
        </div>
        <p class="text-2xl font-bold text-gray-800">{{ $revenueStats['invoice_count'] }}</p>
        <p class="text-xs text-gray-400 mt-0.5">Invoices Issued</p>
        <div class="flex gap-2 mt-2">
            <span class="text-xs bg-green-100 text-green-700 px-2 py-0.5 rounded-full">{{ $revenueStats['paid_count'] }} paid</span>
            @if($revenueStats['partial_count'] > 0)
            <span class="text-xs bg-yellow-100 text-yellow-700 px-2 py-0.5 rounded-full">{{ $revenueStats['partial_count'] }} partial</span>
            @endif
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
        <div class="w-9 h-9 bg-rose-50 rounded-xl flex items-center justify-center mb-3">
            <svg class="w-5 h-5 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
            </svg>
        </div>
        <p class="text-2xl font-bold text-gray-800">{{ $newPatients->count() }}</p>
        <p class="text-xs text-gray-400 mt-0.5">New Patients</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">

    {{-- Appointments list --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
            <h3 class="text-sm font-semibold text-gray-700">Appointments</h3>
            <a href="{{ route('appointments.index', ['date' => $date->format('Y-m-d')]) }}"
                class="text-xs text-rose-500 hover:text-rose-600 font-medium">View full →</a>
        </div>
        @if($appointments->isEmpty())
        <div class="px-5 py-8 text-center text-gray-400 text-sm">No appointments for this day.</div>
        @else
        <div class="divide-y divide-gray-50">
            @foreach($appointments as $appt)
            @php
                $sc = ['scheduled'=>'bg-blue-100 text-blue-700','completed'=>'bg-green-100 text-green-700','cancelled'=>'bg-red-100 text-red-600','no_show'=>'bg-yellow-100 text-yellow-700'];
            @endphp
            <div class="flex items-center gap-3 px-5 py-3">
                <span class="text-xs font-bold text-gray-600 w-14 flex-shrink-0">{{ $appt->formatted_time }}</span>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-800 truncate">{{ $appt->patient->full_name }}</p>
                    <p class="text-xs text-gray-400 truncate">Dr. {{ $appt->doctor->full_name }}{{ $appt->treatment ? ' · '.$appt->treatment->name : '' }}</p>
                </div>
                <span class="text-xs px-2 py-0.5 rounded-full font-medium {{ $sc[$appt->status] ?? 'bg-gray-100 text-gray-500' }}">
                    {{ $appt->status_label }}
                </span>
            </div>
            @endforeach
        </div>
        @endif
    </div>

    {{-- Revenue & payment breakdown --}}
    <div class="space-y-4">

        {{-- Payment method breakdown --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100">
                <h3 class="text-sm font-semibold text-gray-700">Payment Breakdown</h3>
            </div>
            @if($paymentBreakdown->isEmpty())
            <div class="px-5 py-6 text-center text-gray-400 text-sm">No payments recorded.</div>
            @else
            <div class="divide-y divide-gray-50">
                @foreach($paymentBreakdown as $method => $data)
                @php
                    $labels = ['cash'=>'Cash','card'=>'Card','bank_transfer'=>'Bank Transfer','other'=>'Other'];
                    $colors = ['cash'=>'bg-green-100 text-green-700','card'=>'bg-blue-100 text-blue-700','bank_transfer'=>'bg-purple-100 text-purple-700','other'=>'bg-gray-100 text-gray-600'];
                @endphp
                <div class="flex items-center justify-between px-5 py-3">
                    <div class="flex items-center gap-2">
                        <span class="text-xs px-2 py-0.5 rounded-full font-medium {{ $colors[$method] ?? 'bg-gray-100 text-gray-600' }}">
                            {{ $labels[$method] ?? ucfirst($method) }}
                        </span>
                        <span class="text-xs text-gray-400">{{ $data['count'] }} invoice{{ $data['count'] != 1 ? 's' : '' }}</span>
                    </div>
                    <span class="text-sm font-semibold text-gray-800">Rs. {{ number_format($data['amount'], 2) }}</span>
                </div>
                @endforeach
            </div>
            @endif
        </div>

        {{-- Balance outstanding today --}}
        @if($revenueStats['total_balance'] > 0)
        <div class="bg-red-50 rounded-2xl border border-red-100 p-5">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 bg-red-100 rounded-xl flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-semibold text-red-700">Outstanding Balance Today</p>
                    <p class="text-xl font-bold text-red-600">Rs. {{ number_format($revenueStats['total_balance'], 2) }}</p>
                </div>
                <a href="{{ route('reports.outstanding') }}" class="ml-auto text-xs text-red-500 hover:text-red-600 font-medium">View all →</a>
            </div>
        </div>
        @endif

        {{-- New patients --}}
        @if($newPatients->count())
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100">
                <h3 class="text-sm font-semibold text-gray-700">New Patients Today</h3>
            </div>
            <div class="divide-y divide-gray-50">
                @foreach($newPatients as $np)
                <a href="{{ route('patients.show', $np) }}"
                    class="flex items-center gap-3 px-5 py-3 hover:bg-gray-50 transition">
                    <div class="w-7 h-7 rounded-full bg-rose-100 flex items-center justify-center text-rose-500 text-xs font-bold flex-shrink-0">
                        {{ strtoupper(substr($np->full_name, 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-800 truncate">{{ $np->full_name }}</p>
                        <p class="text-xs text-gray-400">{{ $np->patient_id }} · {{ ucfirst($np->gender) }}, {{ $np->age }} yrs</p>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
        @endif

    </div>
</div>

{{-- Invoices table --}}
@if($invoices->count())
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
        <h3 class="text-sm font-semibold text-gray-700">Invoices Issued Today</h3>
        <span class="text-xs text-gray-400">{{ $invoices->count() }} invoice{{ $invoices->count() != 1 ? 's' : '' }}</span>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100">
                    <th class="text-left px-5 py-2.5 text-xs text-gray-400 font-semibold">Invoice</th>
                    <th class="text-left px-5 py-2.5 text-xs text-gray-400 font-semibold">Patient</th>
                    <th class="text-right px-5 py-2.5 text-xs text-gray-400 font-semibold">Total</th>
                    <th class="text-right px-5 py-2.5 text-xs text-gray-400 font-semibold">Paid</th>
                    <th class="text-right px-5 py-2.5 text-xs text-gray-400 font-semibold">Balance</th>
                    <th class="text-left px-5 py-2.5 text-xs text-gray-400 font-semibold">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @foreach($invoices as $inv)
                <tr class="hover:bg-gray-50/50">
                    <td class="px-5 py-3">
                        <a href="{{ route('invoices.show', $inv) }}" class="font-mono text-xs text-rose-600 hover:text-rose-700 font-semibold">
                            {{ $inv->invoice_number }}
                        </a>
                    </td>
                    <td class="px-5 py-3 text-sm text-gray-700">{{ $inv->patient->full_name }}</td>
                    <td class="px-5 py-3 text-right text-sm font-semibold text-gray-800">Rs. {{ number_format($inv->total, 2) }}</td>
                    <td class="px-5 py-3 text-right text-sm font-semibold text-green-600">Rs. {{ number_format($inv->paid_amount, 2) }}</td>
                    <td class="px-5 py-3 text-right text-sm font-semibold {{ $inv->balance > 0 ? 'text-red-500' : 'text-gray-400' }}">
                        Rs. {{ number_format($inv->balance, 2) }}
                    </td>
                    <td class="px-5 py-3">
                        <span class="text-xs px-2.5 py-1 rounded-full font-medium {{ $inv->status_color }}">{{ $inv->status_label }}</span>
                    </td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="bg-gray-50 border-t-2 border-gray-200 font-bold">
                    <td colspan="2" class="px-5 py-3 text-sm text-gray-600">Total</td>
                    <td class="px-5 py-3 text-right text-sm text-gray-800">Rs. {{ number_format($revenueStats['total_invoiced'], 2) }}</td>
                    <td class="px-5 py-3 text-right text-sm text-green-600">Rs. {{ number_format($revenueStats['total_collected'], 2) }}</td>
                    <td class="px-5 py-3 text-right text-sm {{ $revenueStats['total_balance'] > 0 ? 'text-red-500' : 'text-gray-400' }}">
                        Rs. {{ number_format($revenueStats['total_balance'], 2) }}
                    </td>
                    <td></td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
@endif

@endsection
