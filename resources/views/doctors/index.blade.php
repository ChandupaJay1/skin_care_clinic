@extends('layouts.app')

@section('title', 'Doctors - Skin Care Clinic')

@section('content')

<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
    <div>
        <a href="{{ url('/') }}"
            class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-rose-600 transition mb-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Back to Dashboard
        </a>
        <h1 class="text-2xl font-bold text-gray-800">Doctors</h1>
        <p class="text-gray-500 text-sm mt-1">{{ $doctors->total() }} doctor(s) registered</p>
    </div>
    @if(auth()->user()->hasRole(['admin']))
    <a href="{{ route('doctors.create') }}"
        class="inline-flex items-center gap-2 bg-rose-500 hover:bg-rose-600 text-white text-sm font-medium px-4 py-2.5 rounded-lg transition">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Register New Doctor
    </a>
    @endif
</div>

{{-- Filters --}}
<div class="bg-white rounded-xl shadow-sm border border-rose-100 p-4 mb-6">
    <form method="GET" action="{{ route('doctors.index') }}" class="flex flex-wrap gap-3">
        <div class="flex-1 min-w-48 relative">
            <svg class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <input type="text" name="search" value="{{ request('search') }}"
                placeholder="Search by name, ID, phone or specialization..."
                class="w-full pl-10 pr-4 py-2.5 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-rose-400 focus:border-transparent">
        </div>
        <select name="status"
            class="border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-rose-400">
            <option value="">All Statuses</option>
            <option value="active"   {{ request('status') == 'active'   ? 'selected' : '' }}>Active</option>
            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
            <option value="on_leave" {{ request('status') == 'on_leave' ? 'selected' : '' }}>On Leave</option>
        </select>
        <button type="submit"
            class="bg-rose-500 hover:bg-rose-600 text-white text-sm font-medium px-5 py-2.5 rounded-lg transition">
            Search
        </button>
        @if(request('search') || request('status'))
        <a href="{{ route('doctors.index') }}"
            class="bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium px-4 py-2.5 rounded-lg transition">
            Clear
        </a>
        @endif
    </form>
</div>

{{-- Doctors Table --}}
<div class="bg-white rounded-xl shadow-sm border border-rose-100 overflow-hidden">
    @if($doctors->count() > 0)
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-rose-50 border-b border-rose-100">
                    <th class="text-left px-6 py-3 text-xs font-semibold text-rose-600 uppercase tracking-wider">Doctor</th>
                    <th class="text-left px-6 py-3 text-xs font-semibold text-rose-600 uppercase tracking-wider">ID</th>
                    <th class="text-left px-6 py-3 text-xs font-semibold text-rose-600 uppercase tracking-wider">Specialization</th>
                    <th class="text-left px-6 py-3 text-xs font-semibold text-rose-600 uppercase tracking-wider">Contact</th>
                    <th class="text-left px-6 py-3 text-xs font-semibold text-rose-600 uppercase tracking-wider">Experience</th>
                    <th class="text-left px-6 py-3 text-xs font-semibold text-rose-600 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($doctors as $doctor)
                @php
                    $statusColors = [
                        'active'   => 'bg-green-100 text-green-700',
                        'inactive' => 'bg-gray-100 text-gray-500',
                        'on_leave' => 'bg-yellow-100 text-yellow-700',
                    ];
                    $statusLabels = [
                        'active'   => 'Active',
                        'inactive' => 'Inactive',
                        'on_leave' => 'On Leave',
                    ];
                @endphp
                <tr class="hover:bg-rose-50/30 transition">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            @if($doctor->profile_photo)
                                <img src="{{ Storage::url($doctor->profile_photo) }}"
                                    class="w-9 h-9 rounded-full object-cover border border-rose-200">
                            @else
                                <div class="w-9 h-9 rounded-full bg-rose-100 flex items-center justify-center text-rose-500 font-semibold text-sm">
                                    {{ strtoupper(substr($doctor->full_name, 0, 1)) }}
                                </div>
                            @endif
                            <div>
                                <p class="font-medium text-gray-800">Dr. {{ $doctor->full_name }}</p>
                                <p class="text-xs text-gray-400">{{ $doctor->qualification }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="font-mono text-xs bg-rose-50 text-rose-700 px-2 py-1 rounded">{{ $doctor->doctor_id }}</span>
                    </td>
                    <td class="px-6 py-4 text-gray-700">{{ $doctor->specialization }}</td>
                    <td class="px-6 py-4">
                        <p class="text-gray-700">{{ $doctor->phone }}</p>
                        @if($doctor->email)
                            <p class="text-xs text-gray-400">{{ $doctor->email }}</p>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-gray-600">
                        {{ $doctor->experience_years }} yr{{ $doctor->experience_years != 1 ? 's' : '' }}
                    </td>
                    <td class="px-6 py-4">
                        <span class="text-xs px-2 py-1 rounded-full font-medium {{ $statusColors[$doctor->status] ?? 'bg-gray-100 text-gray-500' }}">
                            {{ $statusLabels[$doctor->status] ?? ucfirst($doctor->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2 justify-end">
                            <a href="{{ route('doctors.show', $doctor) }}"
                                class="text-rose-500 hover:text-rose-700 transition" title="View">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </a>
                            @if(auth()->user()->isAdmin())
                            <a href="{{ route('doctors.edit', $doctor) }}"
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

    @if($doctors->hasPages())
    <div class="px-6 py-4 border-t border-gray-100">
        {{ $doctors->links() }}
    </div>
    @endif

    @else
    <div class="text-center py-16">
        <div class="w-16 h-16 bg-rose-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                    d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <h3 class="text-gray-600 font-medium mb-1">No doctors found</h3>
        <p class="text-gray-400 text-sm mb-4">
            @if(request('search') || request('status'))
                No results match your filters.
            @else
                Register your first doctor to get started.
            @endif
        </p>
        @if(!request('search') && !request('status'))
        @if(auth()->user()->isAdmin())
        <a href="{{ route('doctors.create') }}"
            class="inline-flex items-center gap-2 bg-rose-500 hover:bg-rose-600 text-white text-sm font-medium px-4 py-2 rounded-lg transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Register First Doctor
        </a>
        @endif
        @endif
    </div>
    @endif
</div>

@endsection
