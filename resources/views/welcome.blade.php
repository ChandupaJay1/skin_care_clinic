@extends('layouts.app')

@section('title', 'Dashboard - Skin Care Clinic')

@section('content')

@php
    $totalPatients   = \App\Models\Patient::count();
    $activePatients  = \App\Models\Patient::where('is_active', true)->count();
    $todayPatients   = \App\Models\Patient::whereDate('created_at', today())->count();
    $totalTreatments = \App\Models\Treatment::count();
    $totalDoctors    = \App\Models\Doctor::count();
    $activeDoctors   = \App\Models\Doctor::where('status', 'active')->count();
@endphp

{{-- Page Header --}}
<div class="mb-8 text-center">
    <div class="w-16 h-16 bg-rose-500 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg shadow-rose-200">
        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
        </svg>
    </div>
    <h1 class="text-2xl font-bold text-gray-800">Skin Care Clinic</h1>
    <p class="text-gray-400 text-sm mt-1">Clinic Management Dashboard</p>
</div>

{{-- Stats Row --}}
<div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-10">
    <div class="bg-white rounded-xl shadow-sm border border-rose-100 p-4 text-center">
        <p class="text-2xl font-bold text-rose-600">{{ $totalPatients }}</p>
        <p class="text-xs text-gray-500 mt-1">Total Patients</p>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-rose-100 p-4 text-center">
        <p class="text-2xl font-bold text-green-600">{{ $activePatients }}</p>
        <p class="text-xs text-gray-500 mt-1">Active Patients</p>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-rose-100 p-4 text-center">
        <p class="text-2xl font-bold text-blue-600">{{ $todayPatients }}</p>
        <p class="text-xs text-gray-500 mt-1">Registered Today</p>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-rose-100 p-4 text-center">
        <p class="text-2xl font-bold text-purple-600">{{ $totalTreatments }}</p>
        <p class="text-xs text-gray-500 mt-1">Treatments</p>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-rose-100 p-4 text-center">
        <p class="text-2xl font-bold text-teal-600">{{ $totalDoctors }}</p>
        <p class="text-xs text-gray-500 mt-1">Total Doctors</p>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-rose-100 p-4 text-center">
        <p class="text-2xl font-bold text-emerald-600">{{ $activeDoctors }}</p>
        <p class="text-xs text-gray-500 mt-1">Active Doctors</p>
    </div>
</div>

{{-- Section: Modules --}}
<h2 class="text-sm font-semibold text-gray-400 uppercase tracking-widest mb-4">Modules</h2>
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5 mb-10">

    {{-- Patients --}}
    <a href="{{ route('patients.index') }}"
        class="bg-white rounded-xl shadow-sm border border-rose-100 p-6 hover:shadow-md hover:border-rose-300 transition group">
        <div class="flex items-start justify-between mb-4">
            <div class="w-12 h-12 bg-rose-100 rounded-xl flex items-center justify-center group-hover:bg-rose-500 transition">
                <svg class="w-6 h-6 text-rose-500 group-hover:text-white transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
            <span class="text-xs bg-rose-50 text-rose-600 font-medium px-2 py-1 rounded-full">{{ $totalPatients }} total</span>
        </div>
        <h3 class="font-semibold text-gray-800 mb-1">Patient Management</h3>
        <p class="text-sm text-gray-500 mb-4">Register, view and manage all patient records.</p>
        <div class="text-rose-500 text-sm font-medium flex items-center gap-1">
            View Patients
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
        </div>
    </a>

    {{-- Doctors --}}
    <a href="{{ route('doctors.index') }}"
        class="bg-white rounded-xl shadow-sm border border-rose-100 p-6 hover:shadow-md hover:border-rose-300 transition group">
        <div class="flex items-start justify-between mb-4">
            <div class="w-12 h-12 bg-teal-100 rounded-xl flex items-center justify-center group-hover:bg-teal-500 transition">
                <svg class="w-6 h-6 text-teal-500 group-hover:text-white transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <span class="text-xs bg-teal-50 text-teal-600 font-medium px-2 py-1 rounded-full">{{ $totalDoctors }} total</span>
        </div>
        <h3 class="font-semibold text-gray-800 mb-1">Doctor Management</h3>
        <p class="text-sm text-gray-500 mb-4">Register and manage doctor profiles and specializations.</p>
        <div class="text-teal-500 text-sm font-medium flex items-center gap-1">
            View Doctors
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
        </div>
    </a>

    {{-- Treatments --}}
    <a href="{{ route('treatments.index') }}"
        class="bg-white rounded-xl shadow-sm border border-rose-100 p-6 hover:shadow-md hover:border-rose-300 transition group">
        <div class="flex items-start justify-between mb-4">
            <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center group-hover:bg-purple-500 transition">
                <svg class="w-6 h-6 text-purple-500 group-hover:text-white transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                </svg>
            </div>
            <span class="text-xs bg-purple-50 text-purple-600 font-medium px-2 py-1 rounded-full">{{ $totalTreatments }} total</span>
        </div>
        <h3 class="font-semibold text-gray-800 mb-1">Treatments</h3>
        <p class="text-sm text-gray-500 mb-4">Create and manage skin care treatments offered at the clinic.</p>
        <div class="text-purple-500 text-sm font-medium flex items-center gap-1">
            View Treatments
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
        </div>
    </a>

    {{-- Appointments (Coming Soon) --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 opacity-50 cursor-not-allowed">
        <div class="w-12 h-12 bg-gray-100 rounded-xl flex items-center justify-center mb-4">
            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
        </div>
        <h3 class="font-semibold text-gray-600 mb-1">Appointments</h3>
        <p class="text-sm text-gray-400 mb-4">Schedule and manage patient appointments.</p>
        <span class="text-xs bg-gray-100 text-gray-500 px-2 py-1 rounded-full">Coming Soon</span>
    </div>

    {{-- Before & After (Coming Soon) --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 opacity-50 cursor-not-allowed">
        <div class="w-12 h-12 bg-gray-100 rounded-xl flex items-center justify-center mb-4">
            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
        </div>
        <h3 class="font-semibold text-gray-600 mb-1">Before & After History</h3>
        <p class="text-sm text-gray-400 mb-4">Track patient treatment progress with photos.</p>
        <span class="text-xs bg-gray-100 text-gray-500 px-2 py-1 rounded-full">Coming Soon</span>
    </div>

    {{-- Reports (Coming Soon) --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 opacity-50 cursor-not-allowed">
        <div class="w-12 h-12 bg-gray-100 rounded-xl flex items-center justify-center mb-4">
            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
        </div>
        <h3 class="font-semibold text-gray-600 mb-1">Reports</h3>
        <p class="text-sm text-gray-400 mb-4">Generate clinic reports and analytics.</p>
        <span class="text-xs bg-gray-100 text-gray-500 px-2 py-1 rounded-full">Coming Soon</span>
    </div>

</div>

{{-- Section: Quick Actions --}}
<h2 class="text-sm font-semibold text-gray-400 uppercase tracking-widest mb-4">Quick Actions</h2>
<div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

    @if(auth()->user()->hasRole(['admin', 'receptionist']))
    <a href="{{ route('patients.create') }}"
        class="bg-gradient-to-br from-rose-500 to-rose-600 rounded-xl p-5 hover:shadow-lg hover:shadow-rose-200 transition flex items-center gap-4">
        <div class="w-11 h-11 bg-white/20 rounded-xl flex items-center justify-center flex-shrink-0">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
        </div>
        <div>
            <p class="font-semibold text-white text-sm">Register New Patient</p>
            <p class="text-rose-100 text-xs mt-0.5">Add a new patient to the system</p>
        </div>
        <svg class="w-4 h-4 text-white/70 ml-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
    </a>
    @endif

    @if(auth()->user()->isAdmin())
    <a href="{{ route('doctors.create') }}"
        class="bg-gradient-to-br from-teal-500 to-teal-600 rounded-xl p-5 hover:shadow-lg hover:shadow-teal-200 transition flex items-center gap-4">
        <div class="w-11 h-11 bg-white/20 rounded-xl flex items-center justify-center flex-shrink-0">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
        </div>
        <div>
            <p class="font-semibold text-white text-sm">Register New Doctor</p>
            <p class="text-teal-100 text-xs mt-0.5">Add a new doctor to the system</p>
        </div>
        <svg class="w-4 h-4 text-white/70 ml-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
    </a>
    @endif

</div>

@endsection
