@extends('layouts.app')

@section('title', $patient->full_name . ' - Skin Care Clinic')

@section('content')

<div class="mb-6">
    <nav class="text-sm text-gray-500 mb-2">
        <a href="{{ route('patients.index') }}" class="hover:text-rose-600">Patients</a>
        <span class="mx-2">/</span>
        <span class="text-gray-700">{{ $patient->full_name }}</span>
    </nav>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- Left: Profile Card --}}
    <div class="lg:col-span-1 space-y-5">

        {{-- Profile --}}
        <div class="bg-white rounded-xl shadow-sm border border-rose-100 overflow-hidden">
            <div class="bg-gradient-to-br from-rose-400 to-rose-600 px-6 py-8 text-center">
                @if($patient->profile_photo)
                    <img src="{{ Storage::url($patient->profile_photo) }}"
                        class="w-24 h-24 rounded-full object-cover border-4 border-white mx-auto mb-3">
                @else
                    <div class="w-24 h-24 rounded-full bg-white/20 flex items-center justify-center mx-auto mb-3 border-4 border-white">
                        <span class="text-3xl font-bold text-white">{{ strtoupper(substr($patient->full_name, 0, 1)) }}</span>
                    </div>
                @endif
                <h2 class="text-white font-bold text-lg">{{ $patient->full_name }}</h2>
                <p class="text-rose-100 text-sm mt-1 font-mono">{{ $patient->patient_id }}</p>
                <div class="mt-3">
                    @if($patient->is_active)
                        <span class="bg-green-400/20 text-green-100 text-xs px-3 py-1 rounded-full border border-green-300/30">Active</span>
                    @else
                        <span class="bg-gray-400/20 text-gray-100 text-xs px-3 py-1 rounded-full">Inactive</span>
                    @endif
                </div>
            </div>

            <div class="p-5 space-y-3">
                <div class="flex items-center gap-3 text-sm">
                    <svg class="w-4 h-4 text-rose-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <span class="text-gray-600">{{ $patient->date_of_birth->format('d M Y') }} ({{ $patient->age }} yrs)</span>
                </div>
                <div class="flex items-center gap-3 text-sm">
                    <svg class="w-4 h-4 text-rose-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    <span class="text-gray-600">{{ ucfirst($patient->gender) }}</span>
                </div>
                <div class="flex items-center gap-3 text-sm">
                    <svg class="w-4 h-4 text-rose-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                    </svg>
                    <span class="text-gray-600">{{ $patient->phone }}</span>
                </div>
                @if($patient->email)
                <div class="flex items-center gap-3 text-sm">
                    <svg class="w-4 h-4 text-rose-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    <span class="text-gray-600 break-all">{{ $patient->email }}</span>
                </div>
                @endif
                <div class="flex items-start gap-3 text-sm">
                    <svg class="w-4 h-4 text-rose-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <span class="text-gray-600">{{ $patient->address }}</span>
                </div>
            </div>

            <div class="px-5 pb-5 flex gap-2">
                <a href="{{ route('patients.edit', $patient) }}"
                    class="flex-1 text-center bg-rose-500 hover:bg-rose-600 text-white text-sm font-medium py-2 rounded-lg transition">
                    Edit Details
                </a>
            </div>
        </div>

        {{-- Barcode Card --}}
        <div class="bg-white rounded-xl shadow-sm border border-rose-100 overflow-hidden">
            <div class="bg-rose-50 px-5 py-3 border-b border-rose-100 flex items-center justify-between">
                <h3 class="font-semibold text-rose-700 text-sm flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                    </svg>
                    Patient Barcode
                </h3>
                <span class="text-xs text-gray-400 font-mono">{{ $patient->barcode_value ?? $patient->patient_id }}</span>
            </div>

            <div class="p-5">
                @if($patient->barcode_svg)
                    {{-- Barcode Display --}}
                    <div id="barcode-container" class="bg-white border border-gray-100 rounded-lg p-4 text-center">
                        {!! $patient->barcode_svg !!}
                        <p class="text-xs font-mono text-gray-500 mt-2 tracking-widest">{{ $patient->barcode_value }}</p>
                    </div>

                    {{-- Actions --}}
                    <div class="flex gap-2 mt-4">
                        <button onclick="printBarcode()"
                            class="flex-1 flex items-center justify-center gap-2 bg-rose-500 hover:bg-rose-600 text-white text-xs font-medium py-2 rounded-lg transition">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                            </svg>
                            Print
                        </button>
                        <form action="{{ route('patients.barcode.regenerate', $patient) }}" method="POST">
                            @csrf
                            <button type="submit"
                                class="flex items-center justify-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-medium px-3 py-2 rounded-lg transition"
                                title="Regenerate Barcode">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                </svg>
                                Regenerate
                            </button>
                        </form>
                    </div>

                @else
                    {{-- No barcode yet --}}
                    <div class="text-center py-6">
                        <div class="w-12 h-12 bg-rose-100 rounded-full flex items-center justify-center mx-auto mb-3">
                            <svg class="w-6 h-6 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                            </svg>
                        </div>
                        <p class="text-sm text-gray-500 mb-3">No barcode generated yet</p>
                        <form action="{{ route('patients.barcode.regenerate', $patient) }}" method="POST">
                            @csrf
                            <button type="submit"
                                class="bg-rose-500 hover:bg-rose-600 text-white text-sm font-medium px-4 py-2 rounded-lg transition">
                                Generate Barcode
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        </div>

    </div>

    {{-- Right: Details --}}
    <div class="lg:col-span-2 space-y-6">

        {{-- NIC & Skin Info --}}
        <div class="bg-white rounded-xl shadow-sm border border-rose-100 p-6">
            <h3 class="font-semibold text-gray-700 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Medical Information
            </h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">NIC Number</p>
                    <p class="text-gray-800 font-medium font-mono">{{ $patient->nic }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Skin Type</p>
                    @php
                        $skinColors = [
                            'normal'      => 'bg-green-100 text-green-700',
                            'dry'         => 'bg-yellow-100 text-yellow-700',
                            'oily'        => 'bg-blue-100 text-blue-700',
                            'combination' => 'bg-purple-100 text-purple-700',
                            'sensitive'   => 'bg-red-100 text-red-700',
                        ];
                    @endphp
                    <span class="text-sm px-3 py-1 rounded-full font-medium {{ $skinColors[$patient->skin_type] ?? 'bg-gray-100 text-gray-700' }}">
                        {{ ucfirst($patient->skin_type) }}
                    </span>
                </div>
                @if($patient->known_allergies)
                <div class="sm:col-span-2">
                    <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Known Allergies</p>
                    <p class="text-gray-800">{{ $patient->known_allergies }}</p>
                </div>
                @endif
                @if($patient->medical_history)
                <div class="sm:col-span-2">
                    <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Medical History</p>
                    <p class="text-gray-800 whitespace-pre-line">{{ $patient->medical_history }}</p>
                </div>
                @endif
            </div>
        </div>

        {{-- Emergency Contact --}}
        <div class="bg-white rounded-xl shadow-sm border border-rose-100 p-6">
            <h3 class="font-semibold text-gray-700 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                </svg>
                Emergency Contact
            </h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Name</p>
                    <p class="text-gray-800 font-medium">{{ $patient->emergency_contact_name }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Phone</p>
                    <p class="text-gray-800 font-medium">{{ $patient->emergency_contact_phone }}</p>
                </div>
            </div>
        </div>

        {{-- Registration Info --}}
        <div class="bg-rose-50 rounded-xl border border-rose-100 p-4 text-sm text-gray-500 flex items-center gap-3">
            <svg class="w-4 h-4 text-rose-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            Registered on {{ $patient->created_at->format('d M Y, h:i A') }}
            &nbsp;·&nbsp; Last updated {{ $patient->updated_at->diffForHumans() }}
        </div>

    </div>
</div>

{{-- Print Barcode Modal (hidden, print only) --}}
<div id="print-area" class="hidden">
    <div style="text-align:center; font-family:sans-serif; padding:20px;">
        <h2 style="font-size:14px; margin-bottom:4px;">Skin Care Clinic</h2>
        <p style="font-size:12px; color:#666; margin-bottom:12px;">Patient ID Card</p>
        <p style="font-size:13px; font-weight:bold; margin-bottom:8px;">{{ $patient->full_name }}</p>
        @if($patient->barcode_svg)
            {!! $patient->barcode_svg !!}
        @endif
        <p style="font-size:11px; font-family:monospace; margin-top:6px; letter-spacing:2px;">{{ $patient->barcode_value }}</p>
        <p style="font-size:10px; color:#999; margin-top:4px;">{{ $patient->phone }}</p>
    </div>
</div>

<script>
function printBarcode() {
    const printContent = document.getElementById('print-area').innerHTML;
    const win = window.open('', '_blank', 'width=400,height=300');
    win.document.write(`
        <html>
        <head>
            <title>Barcode - {{ $patient->patient_id }}</title>
            <style>
                body { margin: 0; display: flex; justify-content: center; align-items: center; min-height: 100vh; }
                svg { max-width: 250px; }
                @media print { body { margin: 10px; } }
            </style>
        </head>
        <body>${printContent}</body>
        </html>
    `);
    win.document.close();
    win.focus();
    setTimeout(() => { win.print(); win.close(); }, 300);
}
</script>

@endsection
