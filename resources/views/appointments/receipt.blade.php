<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt — {{ $appointment->booking_number }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @media print {
            body { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .no-print { display: none !important; }
            @page { margin: 12mm; size: 80mm auto; }
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen flex items-start justify-center py-8 px-4">

{{-- Print / Close buttons --}}
<div class="no-print fixed top-4 right-4 flex gap-2 z-50">
    <button onclick="window.print()"
        class="flex items-center gap-2 bg-rose-500 hover:bg-rose-600 text-white text-sm font-semibold px-4 py-2 rounded-xl shadow-lg transition">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
        </svg>
        Print
    </button>
    <a href="{{ route('appointments.show', $appointment) }}"
        class="flex items-center gap-2 bg-white border border-gray-200 text-gray-600 hover:bg-gray-50 text-sm font-medium px-4 py-2 rounded-xl shadow transition">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
        </svg>
        Close
    </a>
</div>

{{-- Receipt card --}}
<div class="bg-white w-full max-w-sm rounded-2xl shadow-xl overflow-hidden" id="receipt">

    {{-- Header --}}
    <div class="bg-gradient-to-br from-rose-600 to-pink-500 px-6 pt-6 pb-8 text-center relative">
        <div class="absolute inset-x-0 bottom-0 h-4 bg-white" style="border-radius: 100% 100% 0 0 / 100% 100% 0 0;"></div>
        <div class="w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center mx-auto mb-3">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
            </svg>
        </div>
        <h1 class="text-white font-bold text-base leading-tight">Skin Care Clinic</h1>
        <p class="text-rose-100 text-xs mt-0.5">Appointment Receipt</p>
    </div>

    <div class="px-6 pt-2 pb-6 space-y-5">

        {{-- Booking number --}}
        <div class="text-center">
            <p class="text-xs text-gray-400 uppercase tracking-widest mb-1">Booking Number</p>
            <p class="text-2xl font-bold font-mono text-gray-800 tracking-wider">{{ $appointment->booking_number }}</p>
            @php
                $statusColors = [
                    'scheduled' => 'bg-blue-100 text-blue-700',
                    'completed' => 'bg-green-100 text-green-700',
                    'cancelled' => 'bg-red-100 text-red-700',
                    'no_show'   => 'bg-yellow-100 text-yellow-700',
                ];
            @endphp
            <span class="inline-block mt-2 text-xs font-semibold px-3 py-1 rounded-full {{ $statusColors[$appointment->status] ?? 'bg-gray-100 text-gray-600' }}">
                {{ $appointment->status_label }}
            </span>
        </div>

        {{-- Divider --}}
        <div class="border-t border-dashed border-gray-200"></div>

        {{-- Patient info --}}
        <div>
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest mb-3">Patient</p>
            <div class="space-y-2">
                <div class="flex justify-between items-start">
                    <span class="text-xs text-gray-500">Name</span>
                    <span class="text-xs font-semibold text-gray-800 text-right max-w-[60%]">{{ $appointment->patient->full_name }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-xs text-gray-500">Patient ID</span>
                    <span class="text-xs font-mono font-semibold text-rose-600">{{ $appointment->patient->patient_id }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-xs text-gray-500">Age</span>
                    <span class="text-xs font-semibold text-gray-800">{{ $appointment->patient->age }} years</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-xs text-gray-500">Gender</span>
                    <span class="text-xs font-semibold text-gray-800">{{ ucfirst($appointment->patient->gender) }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-xs text-gray-500">Phone</span>
                    <span class="text-xs font-semibold text-gray-800">{{ $appointment->patient->phone }}</span>
                </div>
            </div>
        </div>

        {{-- Divider --}}
        <div class="border-t border-dashed border-gray-200"></div>

        {{-- Appointment info --}}
        <div>
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest mb-3">Appointment</p>
            <div class="space-y-2">
                <div class="flex justify-between">
                    <span class="text-xs text-gray-500">Date</span>
                    <span class="text-xs font-semibold text-gray-800">{{ $appointment->appointment_date->format('d M Y') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-xs text-gray-500">Day</span>
                    <span class="text-xs font-semibold text-gray-800">{{ $appointment->appointment_date->format('l') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-xs text-gray-500">Time</span>
                    <span class="text-xs font-semibold text-gray-800">{{ $appointment->formatted_time }}</span>
                </div>
                <div class="flex justify-between items-start">
                    <span class="text-xs text-gray-500">Doctor</span>
                    <span class="text-xs font-semibold text-gray-800 text-right max-w-[60%]">Dr. {{ $appointment->doctor->full_name }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-xs text-gray-500">Specialization</span>
                    <span class="text-xs font-semibold text-gray-800 text-right max-w-[60%]">{{ $appointment->doctor->specialization }}</span>
                </div>
                @if($appointment->treatment)
                <div class="flex justify-between items-start">
                    <span class="text-xs text-gray-500">Treatment</span>
                    <span class="text-xs font-semibold text-gray-800 text-right max-w-[60%]">{{ $appointment->treatment->name }}</span>
                </div>
                @endif
                @if($appointment->notes)
                <div class="flex justify-between items-start gap-4">
                    <span class="text-xs text-gray-500 flex-shrink-0">Notes</span>
                    <span class="text-xs text-gray-700 text-right leading-relaxed">{{ $appointment->notes }}</span>
                </div>
                @endif
            </div>
        </div>

        {{-- Divider --}}
        <div class="border-t border-dashed border-gray-200"></div>

        {{-- Patient barcode --}}
        @if($appointment->patient->barcode_svg)
        <div class="text-center">
            <p class="text-xs text-gray-400 uppercase tracking-widest mb-3">Patient Barcode</p>
            <div class="flex justify-center">
                {!! $appointment->patient->barcode_svg !!}
            </div>
            <p class="text-xs font-mono text-gray-500 mt-1">{{ $appointment->patient->barcode_value }}</p>
        </div>
        @endif

        {{-- Footer --}}
        <div class="border-t border-dashed border-gray-200 pt-4 text-center space-y-1">
            <p class="text-xs text-gray-400">Issued: {{ now()->format('d M Y, h:i A') }}</p>
            <p class="text-xs text-gray-300">Thank you for choosing Skin Care Clinic</p>
        </div>

    </div>
</div>

</body>
</html>
