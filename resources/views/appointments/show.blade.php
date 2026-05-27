@extends('layouts.app')
@section('title', 'Appointment ' . $appointment->booking_number)
@section('page_title', 'Appointment Detail')
@section('page_subtitle', $appointment->booking_number)

@section('content')
<div class="max-w-2xl mx-auto space-y-5">

{{-- Header card --}}
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="bg-gradient-to-r from-rose-500 to-pink-500 px-6 py-5">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-rose-100 text-xs font-medium mb-1">Booking Number</p>
                <p class="text-white text-2xl font-bold font-mono tracking-wide">{{ $appointment->booking_number }}</p>
            </div>
            <span class="text-xs px-3 py-1.5 rounded-full font-semibold {{ $appointment->status_color }} border">
                {{ $appointment->status_label }}
            </span>
        </div>
        <div class="flex items-center gap-4 mt-4 text-rose-100 text-sm">
            <span class="flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                {{ $appointment->appointment_date->format('d M Y') }}
            </span>
            <span class="flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                {{ $appointment->formatted_time }}
            </span>
        </div>
    </div>
</div>

{{-- Details --}}
<div class="grid grid-cols-1 sm:grid-cols-2 gap-5">

    {{-- Patient --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-3">Patient</p>
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-full bg-rose-100 flex items-center justify-center text-rose-500 font-bold">
                {{ strtoupper(substr($appointment->patient->full_name, 0, 1)) }}
            </div>
            <div>
                <p class="text-sm font-semibold text-gray-800">{{ $appointment->patient->full_name }}</p>
                <p class="text-xs text-gray-400">{{ $appointment->patient->patient_id }} · {{ $appointment->patient->phone }}</p>
            </div>
        </div>
        <a href="{{ route('patients.show', $appointment->patient) }}"
            class="mt-3 inline-flex items-center gap-1 text-xs text-rose-500 hover:text-rose-600 font-medium">
            View patient profile
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
        </a>
    </div>

    {{-- Doctor --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-3">Doctor</p>
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-full bg-teal-100 flex items-center justify-center text-teal-600 font-bold">
                {{ strtoupper(substr($appointment->doctor->full_name, 0, 1)) }}
            </div>
            <div>
                <p class="text-sm font-semibold text-gray-800">Dr. {{ $appointment->doctor->full_name }}</p>
                <p class="text-xs text-gray-400">{{ $appointment->doctor->specialization }}</p>
            </div>
        </div>
    </div>

    @if($appointment->treatment)
    {{-- Treatment --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-3">Treatment</p>
        <p class="text-sm font-semibold text-gray-800">{{ $appointment->treatment->name }}</p>
    </div>
    @endif

    @if($appointment->notes)
    {{-- Notes --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 {{ $appointment->treatment ? '' : 'sm:col-span-2' }}">
        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-3">Notes</p>
        <p class="text-sm text-gray-700 leading-relaxed">{{ $appointment->notes }}</p>
    </div>
    @endif

</div>

{{-- Actions --}}
@if(auth()->user()->hasRole(['admin','receptionist']))
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-3">Update Status</p>
    <div class="flex flex-wrap gap-2">
        @foreach(['scheduled'=>['bg-blue-500','Scheduled'], 'completed'=>['bg-green-500','Completed'], 'cancelled'=>['bg-red-500','Cancelled'], 'no_show'=>['bg-yellow-500','No Show']] as $s => [$color, $label])
        @if($appointment->status !== $s)
        <form method="POST" action="{{ route('appointments.status', $appointment) }}">
            @csrf @method('PATCH')
            <input type="hidden" name="status" value="{{ $s }}">
            <button type="submit"
                class="text-xs font-medium text-white {{ $color }} hover:opacity-90 px-3 py-1.5 rounded-lg transition">
                Mark {{ $label }}
            </button>
        </form>
        @endif
        @endforeach

        <a href="{{ route('appointments.edit', $appointment) }}"
            class="text-xs font-medium text-gray-600 border border-gray-200 bg-white hover:bg-gray-50 px-3 py-1.5 rounded-lg transition ml-auto">
            Edit Appointment
        </a>
    </div>
</div>
@endif

<div class="flex items-center gap-3">
    <a href="{{ route('appointments.index', ['date' => $appointment->appointment_date->format('Y-m-d')]) }}"
        class="text-sm text-gray-500 hover:text-rose-500 flex items-center gap-1.5 transition">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        Back to {{ $appointment->appointment_date->format('d M Y') }}
    </a>

    <a href="{{ route('appointments.receipt', $appointment) }}" target="_blank"
        class="ml-auto flex items-center gap-2 bg-gray-800 hover:bg-gray-900 text-white text-sm font-semibold px-4 py-2 rounded-xl transition shadow-sm">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
        </svg>
        Print Receipt
    </a>
</div>

</div>
@endsection
