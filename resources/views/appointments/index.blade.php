@extends('layouts.app')
@section('title', 'Appointments — ' . $date->format('d M Y'))
@section('page_title', 'Appointments')
@section('page_subtitle', $date->format('l, d F Y'))

@section('content')

{{-- Date nav + actions --}}
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">

    <div class="flex items-center gap-2">
        <a href="{{ route('appointments.index', ['date' => $date->copy()->subDay()->format('Y-m-d')]) }}"
            class="w-9 h-9 flex items-center justify-center rounded-xl border border-gray-200 bg-white hover:bg-gray-50 transition text-gray-500">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>

        <form method="GET" action="{{ route('appointments.index') }}" class="flex items-center gap-2">
            <input type="date" name="date" value="{{ $date->format('Y-m-d') }}"
                onchange="this.form.submit()"
                class="border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-rose-400 bg-white">
            @if(request('doctor_id'))
                <input type="hidden" name="doctor_id" value="{{ request('doctor_id') }}">
            @endif
        </form>

        <a href="{{ route('appointments.index', ['date' => $date->copy()->addDay()->format('Y-m-d')]) }}"
            class="w-9 h-9 flex items-center justify-center rounded-xl border border-gray-200 bg-white hover:bg-gray-50 transition text-gray-500">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
        </a>

        @if(!$date->isToday())
        <a href="{{ route('appointments.index') }}"
            class="text-xs text-rose-500 hover:text-rose-600 font-medium px-3 py-2 rounded-xl border border-rose-200 bg-rose-50 transition">
            Today
        </a>
        @endif
    </div>

    <div class="flex items-center gap-2">
        <a href="{{ route('appointments.history') }}"
            class="flex items-center gap-2 text-sm text-gray-600 border border-gray-200 bg-white hover:bg-gray-50 px-4 py-2 rounded-xl transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            History
        </a>
        @if(auth()->user()->hasRole(['admin','receptionist']))
        <a href="{{ route('appointments.create') }}"
            class="flex items-center gap-2 text-sm font-semibold text-white bg-rose-500 hover:bg-rose-600 px-4 py-2 rounded-xl transition shadow-sm shadow-rose-200">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            New Appointment
        </a>
        @endif
    </div>
</div>

{{-- Stats row --}}
<div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-6">
    @foreach([
        ['label'=>'Total',     'val'=>$stats['total'],     'color'=>'text-gray-700',  'bg'=>'bg-gray-50',   'border'=>'border-gray-100'],
        ['label'=>'Scheduled', 'val'=>$stats['scheduled'], 'color'=>'text-blue-600',  'bg'=>'bg-blue-50',   'border'=>'border-blue-100'],
        ['label'=>'Completed', 'val'=>$stats['completed'], 'color'=>'text-green-600', 'bg'=>'bg-green-50',  'border'=>'border-green-100'],
        ['label'=>'Cancelled', 'val'=>$stats['cancelled'], 'color'=>'text-red-500',   'bg'=>'bg-red-50',    'border'=>'border-red-100'],
    ] as $s)
    <div class="bg-white rounded-2xl border {{ $s['border'] }} p-4 shadow-sm">
        <p class="text-2xl font-bold {{ $s['color'] }}">{{ $s['val'] }}</p>
        <p class="text-xs text-gray-400 mt-0.5">{{ $s['label'] }}</p>
    </div>
    @endforeach
</div>

{{-- Doctor filter --}}
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 mb-6">
    <form method="GET" action="{{ route('appointments.index') }}" class="flex flex-wrap gap-3 items-center">
        <input type="hidden" name="date" value="{{ $date->format('Y-m-d') }}">
        <select name="doctor_id" onchange="this.form.submit()"
            class="border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-rose-400 bg-white">
            <option value="">All Doctors</option>
            @foreach($doctors as $d)
                <option value="{{ $d->id }}" {{ request('doctor_id') == $d->id ? 'selected' : '' }}>
                    Dr. {{ $d->full_name }}
                </option>
            @endforeach
        </select>
        <select name="status" onchange="this.form.submit()"
            class="border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-rose-400 bg-white">
            <option value="">All Statuses</option>
            <option value="scheduled"  {{ request('status')=='scheduled'  ? 'selected':'' }}>Scheduled</option>
            <option value="completed"  {{ request('status')=='completed'  ? 'selected':'' }}>Completed</option>
            <option value="cancelled"  {{ request('status')=='cancelled'  ? 'selected':'' }}>Cancelled</option>
            <option value="no_show"    {{ request('status')=='no_show'    ? 'selected':'' }}>No Show</option>
        </select>
        @if(request('doctor_id') || request('status'))
        <a href="{{ route('appointments.index', ['date'=>$date->format('Y-m-d')]) }}"
            class="text-xs text-gray-500 hover:text-rose-500 px-3 py-2 rounded-xl border border-gray-200 bg-white transition">
            Clear
        </a>
        @endif
    </form>
</div>

{{-- Appointments --}}
@if($appointments->isEmpty())
<div class="bg-white rounded-2xl border border-dashed border-gray-200 py-16 text-center">
    <div class="w-14 h-14 bg-gray-50 rounded-2xl flex items-center justify-center mx-auto mb-4">
        <svg class="w-7 h-7 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
        </svg>
    </div>
    <p class="text-gray-500 font-medium mb-1">No appointments for {{ $date->format('d M Y') }}</p>
    <p class="text-gray-400 text-sm mb-4">Nothing scheduled for this day.</p>
    @if(auth()->user()->hasRole(['admin','receptionist']))
    <a href="{{ route('appointments.create') }}"
        class="inline-flex items-center gap-2 bg-rose-500 hover:bg-rose-600 text-white text-sm font-medium px-4 py-2 rounded-xl transition">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Book First Appointment
    </a>
    @endif
</div>
@else

{{-- Group by doctor --}}
@foreach($byDoctor as $doctorId => $doctorAppts)
@php $doc = $doctorAppts->first()->doctor; @endphp

<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden mb-4">
    {{-- Doctor header --}}
    <div class="flex items-center gap-3 px-5 py-3 bg-gray-50 border-b border-gray-100">
        <div class="w-8 h-8 rounded-full bg-teal-100 flex items-center justify-center text-teal-600 font-bold text-sm flex-shrink-0">
            {{ strtoupper(substr($doc->full_name, 0, 1)) }}
        </div>
        <div>
            <p class="text-sm font-semibold text-gray-800">Dr. {{ $doc->full_name }}</p>
            <p class="text-xs text-gray-400">{{ $doc->specialization }}</p>
        </div>
        <span class="ml-auto text-xs bg-teal-50 text-teal-600 font-medium px-2.5 py-1 rounded-full">
            {{ $doctorAppts->count() }} appointment{{ $doctorAppts->count() != 1 ? 's' : '' }}
        </span>
    </div>

    {{-- Appointment rows --}}
    <div class="divide-y divide-gray-50">
        @foreach($doctorAppts->sortBy('appointment_time') as $appt)
        <div class="flex items-center gap-4 px-5 py-3.5 hover:bg-gray-50/50 transition">

            {{-- Time --}}
            <div class="w-16 flex-shrink-0 text-center">
                <p class="text-sm font-bold text-gray-800">{{ $appt->formatted_time }}</p>
            </div>

            {{-- Patient --}}
            <div class="flex items-center gap-2.5 flex-1 min-w-0">
                <div class="w-8 h-8 rounded-full bg-rose-100 flex items-center justify-center text-rose-500 text-xs font-bold flex-shrink-0">
                    {{ strtoupper(substr($appt->patient->full_name, 0, 1)) }}
                </div>
                <div class="min-w-0">
                    <p class="text-sm font-medium text-gray-800 truncate">{{ $appt->patient->full_name }}</p>
                    <p class="text-xs text-gray-400">{{ $appt->patient->patient_id }}
                        @if($appt->treatment) · {{ $appt->treatment->name }} @endif
                    </p>
                </div>
            </div>

            {{-- Booking no --}}
            <span class="hidden sm:block font-mono text-xs bg-gray-50 text-gray-500 px-2 py-1 rounded-lg border border-gray-100">
                {{ $appt->booking_number }}
            </span>

            {{-- Status --}}
            <span class="text-xs px-2.5 py-1 rounded-full font-medium {{ $appt->status_color }}">
                {{ $appt->status_label }}
            </span>

            {{-- Actions --}}
            <div class="flex items-center gap-1.5 flex-shrink-0">
                <a href="{{ route('appointments.show', $appt) }}"
                    class="w-7 h-7 flex items-center justify-center rounded-lg text-gray-400 hover:text-rose-500 hover:bg-rose-50 transition" title="View">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                </a>
                <a href="{{ route('appointments.receipt', $appt) }}" target="_blank"
                    class="w-7 h-7 flex items-center justify-center rounded-lg text-gray-400 hover:text-gray-700 hover:bg-gray-100 transition" title="Print Receipt">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                    </svg>
                </a>
                @if(auth()->user()->hasRole(['admin','receptionist']) && $appt->status === 'scheduled')
                <form method="POST" action="{{ route('appointments.status', $appt) }}">
                    @csrf @method('PATCH')
                    <input type="hidden" name="status" value="completed">
                    <button type="submit" title="Mark Completed"
                        class="w-7 h-7 flex items-center justify-center rounded-lg text-gray-400 hover:text-green-600 hover:bg-green-50 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </button>
                </form>
                <form method="POST" action="{{ route('appointments.status', $appt) }}">
                    @csrf @method('PATCH')
                    <input type="hidden" name="status" value="cancelled">
                    <button type="submit" title="Cancel"
                        onclick="return confirm('Cancel this appointment?')"
                        class="w-7 h-7 flex items-center justify-center rounded-lg text-gray-400 hover:text-red-500 hover:bg-red-50 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </form>
                @endif
            </div>
        </div>
        @endforeach
    </div>
</div>
@endforeach

@endif

@endsection
