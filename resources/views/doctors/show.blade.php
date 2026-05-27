@extends('layouts.app')

@section('title', 'Dr. ' . $doctor->full_name . ' - Skin Care Clinic')

@section('content')

@php
    $statusColors = ['active'=>'bg-green-100 text-green-700','inactive'=>'bg-gray-100 text-gray-500','on_leave'=>'bg-yellow-100 text-yellow-700'];
    $statusLabels = ['active'=>'Active','inactive'=>'Inactive','on_leave'=>'On Leave'];
@endphp

<div class="mb-6">
    <a href="{{ route('doctors.index') }}"
        class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-rose-600 transition mb-3">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        Back to Doctors
    </a>
    <nav class="text-sm text-gray-500 mb-2">
        <a href="{{ route('doctors.index') }}" class="hover:text-rose-600">Doctors</a>
        <span class="mx-2">/</span>
        <span class="text-gray-700">Dr. {{ $doctor->full_name }}</span>
    </nav>
</div>

{{-- Profile Header --}}
<div class="bg-white rounded-xl shadow-sm border border-rose-100 overflow-hidden mb-6">
    <div class="bg-gradient-to-r from-rose-500 to-rose-400 h-24"></div>
    <div class="px-6 pb-6">
        <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4 -mt-12">
            <div class="flex items-end gap-4">
                <div class="w-24 h-24 rounded-full border-4 border-white bg-rose-100 overflow-hidden flex-shrink-0 shadow-md">
                    @if($doctor->profile_photo)
                        <img src="{{ Storage::url($doctor->profile_photo) }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-rose-400 text-3xl font-bold">
                            {{ strtoupper(substr($doctor->full_name, 0, 1)) }}
                        </div>
                    @endif
                </div>
                <div class="mb-1">
                    <h1 class="text-xl font-bold text-gray-800">Dr. {{ $doctor->full_name }}</h1>
                    <p class="text-gray-500 text-sm">{{ $doctor->specialization }} &middot; {{ $doctor->qualification }}</p>
                    <div class="flex items-center gap-2 mt-1">
                        <span class="font-mono text-xs bg-rose-50 text-rose-700 px-2 py-0.5 rounded">{{ $doctor->doctor_id }}</span>
                        <span class="text-xs px-2 py-0.5 rounded-full font-medium {{ $statusColors[$doctor->status] ?? '' }}">
                            {{ $statusLabels[$doctor->status] ?? ucfirst($doctor->status) }}
                        </span>
                    </div>
                </div>
            </div>
            <div class="flex gap-2 sm:mb-1">
                <a href="{{ route('doctors.edit', $doctor) }}"
                    class="inline-flex items-center gap-2 bg-rose-500 hover:bg-rose-600 text-white text-sm font-medium px-4 py-2 rounded-lg transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Edit
                </a>
                @if($doctor->status !== 'inactive')
                <form action="{{ route('doctors.destroy', $doctor) }}" method="POST"
                    onsubmit="return confirm('Deactivate Dr. {{ $doctor->full_name }}?')">
                    @csrf @method('DELETE')
                    <button type="submit"
                        class="inline-flex items-center gap-2 bg-white border border-gray-300 text-gray-600 hover:bg-gray-50 text-sm font-medium px-4 py-2 rounded-lg transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                        </svg>
                        Deactivate
                    </button>
                </form>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

    {{-- Personal Details --}}
    <div class="bg-white rounded-xl shadow-sm border border-rose-100 overflow-hidden">
        <div class="bg-rose-50 px-6 py-4 border-b border-rose-100">
            <h2 class="text-base font-semibold text-rose-700">Personal Details</h2>
        </div>
        <div class="p-6 space-y-4">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Date of Birth</p>
                    <p class="text-sm text-gray-800 font-medium">{{ $doctor->date_of_birth->format('d M Y') }}</p>
                    <p class="text-xs text-gray-400">Age {{ $doctor->age }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Gender</p>
                    <p class="text-sm text-gray-800 font-medium">{{ ucfirst($doctor->gender) }}</p>
                </div>
            </div>
            <div>
                <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">NIC</p>
                <p class="text-sm font-mono text-gray-800">{{ $doctor->nic }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Phone</p>
                <p class="text-sm text-gray-800">{{ $doctor->phone }}</p>
            </div>
            @if($doctor->email)
            <div>
                <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Email</p>
                <p class="text-sm text-gray-800">{{ $doctor->email }}</p>
            </div>
            @endif
        </div>
    </div>

    {{-- Professional Details --}}
    <div class="bg-white rounded-xl shadow-sm border border-rose-100 overflow-hidden">
        <div class="bg-rose-50 px-6 py-4 border-b border-rose-100">
            <h2 class="text-base font-semibold text-rose-700">Professional Details</h2>
        </div>
        <div class="p-6 space-y-4">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Specialization</p>
                    <p class="text-sm text-gray-800 font-medium">{{ $doctor->specialization }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Qualification</p>
                    <p class="text-sm text-gray-800 font-medium">{{ $doctor->qualification }}</p>
                </div>
            </div>
            <div>
                <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Registration Number</p>
                <p class="text-sm font-mono text-gray-800">{{ $doctor->registration_number }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Experience</p>
                <p class="text-sm text-gray-800">{{ $doctor->experience_years }} year{{ $doctor->experience_years != 1 ? 's' : '' }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Registered On</p>
                <p class="text-sm text-gray-800">{{ $doctor->created_at->format('d M Y') }}</p>
            </div>
        </div>
    </div>

    @if($doctor->bio)
    {{-- Bio --}}
    <div class="bg-white rounded-xl shadow-sm border border-rose-100 overflow-hidden lg:col-span-2">
        <div class="bg-rose-50 px-6 py-4 border-b border-rose-100">
            <h2 class="text-base font-semibold text-rose-700">Bio / Notes</h2>
        </div>
        <div class="p-6">
            <p class="text-sm text-gray-700 leading-relaxed">{{ $doctor->bio }}</p>
        </div>
    </div>
    @endif

</div>

@endsection
