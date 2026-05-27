@extends('layouts.app')
@section('title', 'Monthly Revenue — ' . $startOfMonth->format('F Y'))
@section('page_title', 'Monthly Revenue')
@section('page_subtitle', $startOfMonth->format('F Y'))

@section('content')

{{-- Month selector --}}
<div class="flex flex-wrap items-center gap-3 mb-6">
    @php
        $prevMonth = $startOfMonth->copy()->subMonth();
        $nextMonth = $startOfMonth->copy()->addMonth();
    @endphp
    <a href="{{ route('reports.monthly', ['year'=>$prevMonth->year,'month'=>$prevMonth->month]) }}"
        class="w-9 h-9 flex items-center justify-center rounded-xl border border-gray-200 bg-white hover:bg-gray-50 transition text-gray-500">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
    </a>

    <form method="GET" action="{{ route('reports.monthly') }}" class="flex gap-2">
        <select name="month" onchange="this.form.submit()"
            class="border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-rose-400 bg-white">
            @foreach(range(1,12) as $m)
            <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>{{ \Carbon\Carbon::create(null,$m)->format('F') }}</option>
            @endforeach
        </select>
        <select name="year" onchange="this.form.submit()"
            class="border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-rose-400 bg-white">
            @foreach(range(now()->year, now()->year - 3) as $y)
            <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
            @endforeach
        </select>
    </form>

    @if(!($year == now()->year && $month == now()->month))
    <a href="{{ route('reports.monthly') }}"
        class="text-xs text-rose-500 hover:text-rose-600 font-medium px-3 py-2 rounded-xl border border-rose-200 bg-rose-50 transition">
        This Month
    </a>
    @endif

    <a href="{{ route('reports.monthly', ['year'=>$nextMonth->year,'month'=>$nextMonth->month]) }}"
        class="w-9 h-9 flex items-center justify-center rounded-xl border border-gray-200 bg-white hover:bg-gray-50 transition text-gray-500">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
    </a>

    <a href="{{ route('reports.monthly.print', ['year'=>$year,'month'=>$month]) }}" target="_blank"
        class="ml-auto flex items-center gap-2 text-sm text-white bg-gray-800 hover:bg-gray-900 px-4 py-2 rounded-xl transition shadow-sm">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
        </svg>
        Print Report
    </a>
</div>

{{-- Stats row --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
        <p class="text-2xl font-bold text-gray-800">Rs. {{ number_format($monthStats['total_collected'], 2) }}</p>
        <p class="text-xs text-gray-400 mt-0.5">Revenue Collected</p>
        <p class="text-xs text-gray-400 mt-1">of Rs. {{ number_format($monthStats['total_invoiced'], 2) }} invoiced</p>
    </div>
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
        <p class="text-2xl font-bold text-gray-800">{{ $monthStats['invoice_count'] }}</p>
        <p class="text-xs text-gray-400 mt-0.5">Invoices</p>
        <div class="flex gap-1.5 mt-2 flex-wrap">
            <span class="text-xs bg-green-100 text-green-700 px-2 py-0.5 rounded-full">{{ $monthStats['paid_count'] }} paid</span>
            @if($monthStats['partial_count'])
            <span class="text-xs bg-yellow-100 text-yellow-700 px-2 py-0.5 rounded-full">{{ $monthStats['partial_count'] }} partial</span>
            @endif
        </div>
    </div>
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
        <p class="text-2xl font-bold text-gray-800">{{ $apptStats['total'] }}</p>
        <p class="text-xs text-gray-400 mt-0.5">Appointments</p>
        <div class="flex gap-1.5 mt-2 flex-wrap">
            <span class="text-xs bg-green-100 text-green-700 px-2 py-0.5 rounded-full">{{ $apptStats['completed'] }} done</span>
            @if($apptStats['no_show'])
            <span class="text-xs bg-yellow-100 text-yellow-700 px-2 py-0.5 rounded-full">{{ $apptStats['no_show'] }} no-show</span>
            @endif
        </div>
    </div>
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
        <p class="text-2xl font-bold text-gray-800">{{ $newPatients }}</p>
        <p class="text-xs text-gray-400 mt-0.5">New Patients</p>
        @if($monthStats['total_balance'] > 0)
        <p class="text-xs text-red-500 mt-1">Rs. {{ number_format($monthStats['total_balance'], 2) }} outstanding</p>
        @endif
    </div>
</div>

{{-- Daily revenue chart --}}
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 mb-6">
    <h3 class="text-sm font-semibold text-gray-700 mb-4">Daily Revenue — {{ $startOfMonth->format('F Y') }}</h3>
    <div class="relative" style="height: 220px;">
        <canvas id="revenueChart"></canvas>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">

    {{-- Top treatments --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100">
            <h3 class="text-sm font-semibold text-gray-700">Top Treatments by Revenue</h3>
        </div>
        @if($topTreatments->isEmpty())
        <div class="px-5 py-8 text-center text-gray-400 text-sm">No treatment data for this month.</div>
        @else
        @php $maxRevenue = $topTreatments->max('revenue'); @endphp
        <div class="divide-y divide-gray-50 p-5 space-y-3">
            @foreach($topTreatments as $i => $t)
            <div class="pt-3 first:pt-0">
                <div class="flex items-center justify-between mb-1.5">
                    <span class="text-sm font-medium text-gray-700">{{ $t->name }}</span>
                    <div class="text-right">
                        <span class="text-sm font-bold text-gray-800">Rs. {{ number_format($t->revenue, 2) }}</span>
                        <span class="text-xs text-gray-400 ml-2">{{ $t->sessions }} session{{ $t->sessions != 1 ? 's' : '' }}</span>
                    </div>
                </div>
                <div class="w-full bg-gray-100 rounded-full h-1.5">
                    <div class="bg-rose-500 h-1.5 rounded-full transition-all"
                        style="width: {{ $maxRevenue > 0 ? round($t->revenue / $maxRevenue * 100) : 0 }}%"></div>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>

    {{-- 12-month trend --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <h3 class="text-sm font-semibold text-gray-700 mb-4">12-Month Revenue Trend</h3>
        <div class="relative" style="height: 180px;">
            <canvas id="trendChart"></canvas>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
const days      = @json($chartDays);
const collected = @json($chartCollected);
const invoiced  = @json($chartInvoiced);

new Chart(document.getElementById('revenueChart'), {
    type: 'bar',
    data: {
        labels: days,
        datasets: [
            {
                label: 'Collected',
                data: collected,
                backgroundColor: 'rgba(244,63,94,0.85)',
                borderRadius: 4,
                order: 1,
            },
            {
                label: 'Invoiced',
                data: invoiced,
                type: 'line',
                borderColor: 'rgba(244,63,94,0.3)',
                backgroundColor: 'transparent',
                borderWidth: 2,
                pointRadius: 0,
                tension: 0.4,
                order: 0,
            }
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: true, position: 'top', labels: { boxWidth: 12, font: { size: 11 } } } },
        scales: {
            x: { grid: { display: false }, ticks: { font: { size: 10 } } },
            y: { grid: { color: '#f3f4f6' }, ticks: { font: { size: 10 }, callback: v => 'Rs.' + v.toLocaleString() } }
        }
    }
});

const trend = @json($last12);
new Chart(document.getElementById('trendChart'), {
    type: 'line',
    data: {
        labels: trend.map(t => t.label),
        datasets: [{
            label: 'Collected',
            data: trend.map(t => t.collected),
            borderColor: 'rgba(244,63,94,1)',
            backgroundColor: 'rgba(244,63,94,0.08)',
            borderWidth: 2,
            pointRadius: 3,
            pointBackgroundColor: 'rgba(244,63,94,1)',
            fill: true,
            tension: 0.4,
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: {
            x: { grid: { display: false }, ticks: { font: { size: 10 } } },
            y: { grid: { color: '#f3f4f6' }, ticks: { font: { size: 10 }, callback: v => 'Rs.' + v.toLocaleString() } }
        }
    }
});
</script>

@endsection
