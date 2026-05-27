@extends('layouts.app')

@section('title', 'Patients - Skin Care Clinic')
@section('page_title', 'Patients')
@section('page_subtitle', $patients->total() . ' patient(s) registered')

@section('content')

<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
    <div>
        <h1 class="text-xl font-bold text-gray-800">Patients</h1>
        <p class="text-gray-400 text-sm mt-0.5">{{ $patients->total() }} patient(s) registered</p>
    </div>
    @if(auth()->user()->hasRole(['admin', 'receptionist']))
    <a href="{{ route('patients.create') }}"
        class="inline-flex items-center gap-2 bg-rose-500 hover:bg-rose-600 text-white text-sm font-medium px-4 py-2.5 rounded-lg transition">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Register New Patient
    </a>
    @endif
</div>

{{-- Search --}}
<div class="bg-white rounded-xl shadow-sm border border-rose-100 p-4 mb-6">
    <form method="GET" action="{{ route('patients.index') }}" class="flex gap-3">
        <div class="flex-1 relative">
            <svg class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <input type="text" name="search" value="{{ request('search') }}"
                placeholder="Search by name, patient ID, phone or NIC..."
                class="w-full pl-10 pr-4 py-2.5 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-rose-400 focus:border-transparent">
        </div>
        <button type="submit"
            class="bg-rose-500 hover:bg-rose-600 text-white text-sm font-medium px-5 py-2.5 rounded-lg transition">
            Search
        </button>
        @if(request('search'))
        <a href="{{ route('patients.index') }}"
            class="bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium px-4 py-2.5 rounded-lg transition">
            Clear
        </a>
        @endif
    </form>
</div>

{{-- Patients Table --}}
<div class="bg-white rounded-xl shadow-sm border border-rose-100 overflow-hidden">
    @if($patients->count() > 0)
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-rose-50 border-b border-rose-100">
                    <th class="text-left px-6 py-3 text-xs font-semibold text-rose-600 uppercase tracking-wider">Patient</th>
                    <th class="text-left px-6 py-3 text-xs font-semibold text-rose-600 uppercase tracking-wider">ID</th>
                    <th class="text-left px-6 py-3 text-xs font-semibold text-rose-600 uppercase tracking-wider">Contact</th>
                    <th class="text-left px-6 py-3 text-xs font-semibold text-rose-600 uppercase tracking-wider">Skin Type</th>
                    <th class="text-left px-6 py-3 text-xs font-semibold text-rose-600 uppercase tracking-wider">Status</th>
                    <th class="text-left px-6 py-3 text-xs font-semibold text-rose-600 uppercase tracking-wider">Registered</th>
                    <th class="px-6 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($patients as $patient)
                <tr class="hover:bg-rose-50/30 transition">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            @if($patient->profile_photo)
                                <img src="{{ Storage::url($patient->profile_photo) }}"
                                    class="w-9 h-9 rounded-full object-cover border border-rose-200">
                            @else
                                <div class="w-9 h-9 rounded-full bg-rose-100 flex items-center justify-center text-rose-500 font-semibold text-sm">
                                    {{ strtoupper(substr($patient->full_name, 0, 1)) }}
                                </div>
                            @endif
                            <div>
                                <p class="font-medium text-gray-800">{{ $patient->full_name }}</p>
                                <p class="text-xs text-gray-400">{{ ucfirst($patient->gender) }}, {{ $patient->age }} yrs</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="font-mono text-xs bg-rose-50 text-rose-700 px-2 py-1 rounded">{{ $patient->patient_id }}</span>
                    </td>
                    <td class="px-6 py-4">
                        <p class="text-gray-700">{{ $patient->phone }}</p>
                        @if($patient->email)
                            <p class="text-xs text-gray-400">{{ $patient->email }}</p>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        @php
                            $skinColors = [
                                'normal'      => 'bg-green-100 text-green-700',
                                'dry'         => 'bg-yellow-100 text-yellow-700',
                                'oily'        => 'bg-blue-100 text-blue-700',
                                'combination' => 'bg-purple-100 text-purple-700',
                                'sensitive'   => 'bg-red-100 text-red-700',
                            ];
                        @endphp
                        <span class="text-xs px-2 py-1 rounded-full font-medium {{ $skinColors[$patient->skin_type] ?? 'bg-gray-100 text-gray-700' }}">
                            {{ ucfirst($patient->skin_type) }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        @if($patient->is_active)
                            <span class="text-xs px-2 py-1 rounded-full bg-green-100 text-green-700 font-medium">Active</span>
                        @else
                            <span class="text-xs px-2 py-1 rounded-full bg-gray-100 text-gray-500 font-medium">Inactive</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-gray-400 text-xs">
                        {{ $patient->created_at->format('d M Y') }}
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2 justify-end">
                            <a href="{{ route('patients.show', $patient) }}"
                                class="text-rose-500 hover:text-rose-700 transition" title="View">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </a>
                            @if(auth()->user()->hasRole(['admin', 'receptionist']))
                            <a href="{{ route('patients.edit', $patient) }}"
                                class="text-blue-500 hover:text-blue-700 transition" title="Edit">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </a>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($patients->hasPages())
    <div class="px-6 py-4 border-t border-gray-100">
        {{ $patients->links() }}
    </div>
    @endif

    @else
    <div class="text-center py-16">
        <div class="w-16 h-16 bg-rose-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
        </div>
        <h3 class="text-gray-600 font-medium mb-1">No patients found</h3>
        <p class="text-gray-400 text-sm mb-4">
            @if(request('search'))
                No results for "{{ request('search') }}"
            @else
                Register your first patient to get started.
            @endif
        </p>
        @if(!request('search'))
        @if(auth()->user()->hasRole(['admin', 'receptionist']))
        <a href="{{ route('patients.create') }}"
            class="inline-flex items-center gap-2 bg-rose-500 hover:bg-rose-600 text-white text-sm font-medium px-4 py-2 rounded-lg transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Register First Patient
        </a>
        @endif
        @endif
    </div>
    @endif
</div>

@endsection
