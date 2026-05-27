@extends('layouts.app')

@section('title', 'Dashboard — Skin Care Clinic')
@section('page_title', 'Dashboard')
@section('page_subtitle', 'Welcome back, ' . auth()->user()->name)

@section('content')

@php
    $totalPatients   = \App\Models\Patient::count();
    $activePatients  = \App\Models\Patient::where('is_active', true)->count();
    $todayPatients   = \App\Models\Patient::whereDate('created_at', today())->count();
    $totalTreatments = \App\Models\Treatment::count();
    $totalDoctors    = \App\Models\Doctor::count();
    $activeDoctors   = \App\Models\Doctor::where('status', 'active')->count();

    $hour = now()->hour;
    $greeting = $hour < 12 ? 'Good morning' : ($hour < 17 ? 'Good afternoon' : 'Good evening');
@endphp

{{-- Greeting banner --}}
<div class="bg-gradient-to-r from-rose-500 to-pink-500 rounded-2xl p-6 mb-6 relative overflow-hidden">
    <div class="absolute -top-8 -right-8 w-40 h-40 bg-white/10 rounded-full"></div>
    <div class="absolute -bottom-10 right-20 w-28 h-28 bg-white/10 rounded-full"></div>
    <div class="relative z-10">
        <p class="text-rose-100 text-sm font-medium mb-1">{{ $greeting }},</p>
        <h2 class="text-white text-xl font-bold mb-1">{{ auth()->user()->name }}</h2>
        <p class="text-rose-100 text-xs">{{ now()->format('l, d F Y') }} &nbsp;·&nbsp; {{ auth()->user()->role_label }}</p>
    </div>
</div>

{{-- Stats grid --}}
<div class="grid grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-4 mb-8">

    <div class="bg-white rounded-2xl border border-gray-100 p-4 shadow-sm">
        <div class="flex items-center justify-between mb-3">
            <div class="w-9 h-9 bg-rose-50 rounded-xl flex items-center justify-center">
                <svg class="w-4.5 h-4.5 w-5 h-5 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
        </div>
        <p class="text-2xl font-bold text-gray-800">{{ $totalPatients }}</p>
        <p class="text-xs text-gray-400 mt-0.5">Total Patients</p>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 p-4 shadow-sm">
        <div class="flex items-center justify-between mb-3">
            <div class="w-9 h-9 bg-green-50 rounded-xl flex items-center justify-center">
                <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
        <p class="text-2xl font-bold text-gray-800">{{ $activePatients }}</p>
        <p class="text-xs text-gray-400 mt-0.5">Active Patients</p>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 p-4 shadow-sm">
        <div class="flex items-center justify-between mb-3">
            <div class="w-9 h-9 bg-blue-50 rounded-xl flex items-center justify-center">
                <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
            </div>
        </div>
        <p class="text-2xl font-bold text-gray-800">{{ $todayPatients }}</p>
        <p class="text-xs text-gray-400 mt-0.5">Registered Today</p>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 p-4 shadow-sm">
        <div class="flex items-center justify-between mb-3">
            <div class="w-9 h-9 bg-purple-50 rounded-xl flex items-center justify-center">
                <svg class="w-5 h-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                </svg>
            </div>
        </div>
        <p class="text-2xl font-bold text-gray-800">{{ $totalTreatments }}</p>
        <p class="text-xs text-gray-400 mt-0.5">Treatments</p>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 p-4 shadow-sm">
        <div class="flex items-center justify-between mb-3">
            <div class="w-9 h-9 bg-teal-50 rounded-xl flex items-center justify-center">
                <svg class="w-5 h-5 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
        <p class="text-2xl font-bold text-gray-800">{{ $totalDoctors }}</p>
        <p class="text-xs text-gray-400 mt-0.5">Total Doctors</p>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 p-4 shadow-sm">
        <div class="flex items-center justify-between mb-3">
            <div class="w-9 h-9 bg-emerald-50 rounded-xl flex items-center justify-center">
                <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                </svg>
            </div>
        </div>
        <p class="text-2xl font-bold text-gray-800">{{ $activeDoctors }}</p>
        <p class="text-xs text-gray-400 mt-0.5">Active Doctors</p>
    </div>

</div>

{{-- Modules + Quick Actions --}}
<div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

    {{-- Module cards --}}
    <div class="xl:col-span-2 space-y-4">
        <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-widest">Modules</h3>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">

            {{-- Patients --}}
            <a href="{{ route('patients.index') }}"
                class="group bg-white rounded-2xl border border-gray-100 p-5 hover:border-rose-200 hover:shadow-md transition shadow-sm">
                <div class="w-10 h-10 bg-rose-50 group-hover:bg-rose-500 rounded-xl flex items-center justify-center mb-4 transition">
                    <svg class="w-5 h-5 text-rose-500 group-hover:text-white transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <p class="text-sm font-semibold text-gray-800 mb-0.5">Patients</p>
                <p class="text-xs text-gray-400 mb-3">{{ $totalPatients }} records</p>
                <span class="text-xs text-rose-500 font-medium flex items-center gap-1">
                    Open <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </span>
            </a>

            {{-- Doctors --}}
            <a href="{{ route('doctors.index') }}"
                class="group bg-white rounded-2xl border border-gray-100 p-5 hover:border-teal-200 hover:shadow-md transition shadow-sm">
                <div class="w-10 h-10 bg-teal-50 group-hover:bg-teal-500 rounded-xl flex items-center justify-center mb-4 transition">
                    <svg class="w-5 h-5 text-teal-500 group-hover:text-white transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <p class="text-sm font-semibold text-gray-800 mb-0.5">Doctors</p>
                <p class="text-xs text-gray-400 mb-3">{{ $totalDoctors }} registered</p>
                <span class="text-xs text-teal-500 font-medium flex items-center gap-1">
                    Open <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </span>
            </a>

            {{-- Treatments --}}
            <a href="{{ route('treatments.index') }}"
                class="group bg-white rounded-2xl border border-gray-100 p-5 hover:border-purple-200 hover:shadow-md transition shadow-sm">
                <div class="w-10 h-10 bg-purple-50 group-hover:bg-purple-500 rounded-xl flex items-center justify-center mb-4 transition">
                    <svg class="w-5 h-5 text-purple-500 group-hover:text-white transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                    </svg>
                </div>
                <p class="text-sm font-semibold text-gray-800 mb-0.5">Treatments</p>
                <p class="text-xs text-gray-400 mb-3">{{ $totalTreatments }} available</p>
                <span class="text-xs text-purple-500 font-medium flex items-center gap-1">
                    Open <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </span>
            </a>

        </div>

        {{-- Coming soon row --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="bg-white rounded-2xl border border-dashed border-gray-200 p-5 opacity-60">
                <div class="w-10 h-10 bg-gray-50 rounded-xl flex items-center justify-center mb-4">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <p class="text-sm font-semibold text-gray-500 mb-0.5">Appointments</p>
                <span class="text-xs bg-gray-100 text-gray-400 px-2 py-0.5 rounded-full">Coming Soon</span>
            </div>
            <div class="bg-white rounded-2xl border border-dashed border-gray-200 p-5 opacity-60">
                <div class="w-10 h-10 bg-gray-50 rounded-xl flex items-center justify-center mb-4">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <p class="text-sm font-semibold text-gray-500 mb-0.5">Reports</p>
                <span class="text-xs bg-gray-100 text-gray-400 px-2 py-0.5 rounded-full">Coming Soon</span>
            </div>
        </div>
    </div>

    {{-- Quick Actions sidebar --}}
    <div class="space-y-4">
        <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-widest">Quick Actions</h3>

        <div class="space-y-3">

            @if(auth()->user()->hasRole(['admin', 'receptionist']))
            <a href="{{ route('patients.create') }}"
                class="flex items-center gap-4 bg-gradient-to-r from-rose-500 to-rose-600 rounded-2xl p-4 hover:shadow-lg hover:shadow-rose-200 transition group">
                <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-white">New Patient</p>
                    <p class="text-xs text-rose-100">Register a patient</p>
                </div>
                <svg class="w-4 h-4 text-white/60 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
            @endif

            @if(auth()->user()->isAdmin())
            <a href="{{ route('doctors.create') }}"
                class="flex items-center gap-4 bg-gradient-to-r from-teal-500 to-teal-600 rounded-2xl p-4 hover:shadow-lg hover:shadow-teal-200 transition group">
                <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-white">New Doctor</p>
                    <p class="text-xs text-teal-100">Register a doctor</p>
                </div>
                <svg class="w-4 h-4 text-white/60 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
            @endif

            @if(auth()->user()->hasRole(['admin', 'doctor']))
            <a href="{{ route('treatments.create') }}"
                class="flex items-center gap-4 bg-gradient-to-r from-purple-500 to-purple-600 rounded-2xl p-4 hover:shadow-lg hover:shadow-purple-200 transition group">
                <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-white">New Treatment</p>
                    <p class="text-xs text-purple-100">Add a treatment</p>
                </div>
                <svg class="w-4 h-4 text-white/60 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
            @endif

        </div>

        {{-- Recent patients mini list --}}
        @php $recentPatients = \App\Models\Patient::latest()->take(4)->get(); @endphp
        @if($recentPatients->count())
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden mt-2">
            <div class="px-4 py-3 border-b border-gray-50 flex items-center justify-between">
                <p class="text-xs font-semibold text-gray-600">Recent Patients</p>
                <a href="{{ route('patients.index') }}" class="text-xs text-rose-500 hover:text-rose-600 font-medium">View all</a>
            </div>
            <div class="divide-y divide-gray-50">
                @foreach($recentPatients as $rp)
                <a href="{{ route('patients.show', $rp) }}"
                    class="flex items-center gap-3 px-4 py-2.5 hover:bg-gray-50 transition">
                    <div class="w-7 h-7 rounded-full bg-rose-100 flex items-center justify-center text-rose-500 text-xs font-bold flex-shrink-0">
                        {{ strtoupper(substr($rp->full_name, 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-medium text-gray-700 truncate">{{ $rp->full_name }}</p>
                        <p class="text-xs text-gray-400">{{ $rp->patient_id }}</p>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
        @endif

    </div>

</div>

@endsection
