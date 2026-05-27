@extends('layouts.app')
@section('title', 'Appointment History')
@section('page_title', 'Appointment History')
@section('page_subtitle', 'Day-by-day record of all appointments')

@section('content')

{{-- Filters --}}
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 mb-6">
    <form method="GET" action="{{ route('appointments.history') }}" class="flex flex-wrap gap-3 items-end">

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
        <div>
            <label class="block text-xs text-gray-500 mb-1">Doctor</label>
            <select name="doctor_id"
                class="border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-rose-400 bg-white">
                <option value="">All Doctors</option>
                @foreach($doctors as $d)
                    <option value="{{ $d->id }}" {{ request('doctor_id') == $d->id ? 'selected' : '' }}>
                        Dr. {{ $d->full_name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-xs text-gray-500 mb-1">Status</label>
            <select name="status"
                class="border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-rose-400 bg-white">
                <option value="">All</option>
                <option value="scheduled"  {{ request('status')=='scheduled'  ? 'selected':'' }}>Scheduled</option>
                <option value="completed"  {{ request('status')=='completed'  ? 'selected':'' }}>Completed</option>
                <option value="cancelled"  {{ request('status')=='cancelled'  ? 'selected':'' }}>Cancelled</option>
                <option value="no_show"    {{ request('status')=='no_show'    ? 'selected':'' }}>No Show</option>
            </select>
        </div>
        <div class="flex-1 min-w-40">
            <label class="block text-xs text-gray-500 mb-1">Search</label>
            <input type="text" name="search" value="{{ request('search') }}"
                placeholder="Patient name, ID or booking no…"
                class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-rose-400 bg-white">
        </div>

        <button type="submit"
            class="bg-rose-500 hover:bg-rose-600 text-white text-sm font-medium px-5 py-2 rounded-xl transition self-end">
            Filter
        </button>
        @if(request()->hasAny(['from','to','doctor_id','status','search']))
        <a href="{{ route('appointments.history') }}"
            class="text-sm text-gray-500 border border-gray-200 bg-white hover:bg-gray-50 px-4 py-2 rounded-xl transition self-end">
            Clear
        </a>
        @endif
    </form>
</div>

{{-- Results --}}
@if($appointments->isEmpty())
<div class="bg-white rounded-2xl border border-dashed border-gray-200 py-16 text-center">
    <p class="text-gray-400 text-sm">No appointments found for the selected filters.</p>
</div>
@else

{{-- Group by date --}}
@php $grouped = $appointments->getCollection()->groupBy(fn($a) => $a->appointment_date->format('Y-m-d')); @endphp

@foreach($grouped as $dateKey => $dayAppts)
@php $dayDate = \Carbon\Carbon::parse($dateKey); @endphp

<div class="mb-6">
    {{-- Day header --}}
    <div class="flex items-center gap-3 mb-3">
        <div class="bg-rose-500 text-white rounded-xl px-3 py-1.5 text-center min-w-14">
            <p class="text-xs font-medium leading-tight">{{ $dayDate->format('M') }}</p>
            <p class="text-lg font-bold leading-tight">{{ $dayDate->format('d') }}</p>
        </div>
        <div>
            <p class="text-sm font-semibold text-gray-800">{{ $dayDate->format('l') }}</p>
            <p class="text-xs text-gray-400">{{ $dayAppts->count() }} appointment{{ $dayAppts->count()!=1?'s':'' }}</p>
        </div>
        <a href="{{ route('appointments.index', ['date' => $dateKey]) }}"
            class="ml-auto text-xs text-rose-500 hover:text-rose-600 font-medium flex items-center gap-1">
            View day
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="divide-y divide-gray-50">
            @foreach($dayAppts->sortBy('appointment_time') as $appt)
            <div class="flex items-center gap-4 px-5 py-3.5 hover:bg-gray-50/50 transition">

                <div class="w-16 flex-shrink-0">
                    <p class="text-sm font-bold text-gray-700">{{ $appt->formatted_time }}</p>
                </div>

                <div class="flex items-center gap-2.5 flex-1 min-w-0">
                    <div class="w-7 h-7 rounded-full bg-rose-100 flex items-center justify-center text-rose-500 text-xs font-bold flex-shrink-0">
                        {{ strtoupper(substr($appt->patient->full_name, 0, 1)) }}
                    </div>
                    <div class="min-w-0">
                        <p class="text-sm font-medium text-gray-800 truncate">{{ $appt->patient->full_name }}</p>
                        <p class="text-xs text-gray-400 truncate">Dr. {{ $appt->doctor->full_name }}
                            @if($appt->treatment) · {{ $appt->treatment->name }} @endif
                        </p>
                    </div>
                </div>

                <span class="hidden sm:block font-mono text-xs text-gray-400">{{ $appt->booking_number }}</span>

                <span class="text-xs px-2.5 py-1 rounded-full font-medium {{ $appt->status_color }}">
                    {{ $appt->status_label }}
                </span>

                <a href="{{ route('appointments.show', $appt) }}"
                    class="w-7 h-7 flex items-center justify-center rounded-lg text-gray-400 hover:text-rose-500 hover:bg-rose-50 transition flex-shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endforeach

{{-- Pagination --}}
@if($appointments->hasPages())
<div class="mt-4">
    {{ $appointments->links() }}
</div>
@endif

@endif

@endsection
