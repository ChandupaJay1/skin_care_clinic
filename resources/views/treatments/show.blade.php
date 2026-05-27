@extends('layouts.app')

@section('title', $treatment->name . ' - Skin Care Clinic')

@section('content')

<div class="mb-6">
    <a href="{{ route('treatments.index') }}"
        class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-rose-600 transition mb-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        Back to Treatments
    </a>
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">{{ $treatment->name }}</h1>
            <p class="text-gray-400 text-sm mt-1">Added {{ $treatment->created_at->format('d M Y') }}</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('treatments.edit', $treatment) }}"
                class="inline-flex items-center gap-2 bg-blue-500 hover:bg-blue-600 text-white text-sm font-medium px-4 py-2 rounded-lg transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Edit
            </a>
            <form action="{{ route('treatments.destroy', $treatment) }}" method="POST"
                onsubmit="return confirm('Delete treatment \'{{ addslashes($treatment->name) }}\'? This cannot be undone.')">
                @csrf
                @method('DELETE')
                <button type="submit"
                    class="inline-flex items-center gap-2 bg-red-500 hover:bg-red-600 text-white text-sm font-medium px-4 py-2 rounded-lg transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                    Delete
                </button>
            </form>
        </div>
    </div>
</div>

<div class="max-w-2xl">
    <div class="bg-white rounded-xl shadow-sm border border-rose-100 p-6">

        {{-- Icon + Name --}}
        <div class="flex items-center gap-4 mb-6 pb-6 border-b border-gray-100">
            <div class="w-14 h-14 bg-rose-100 rounded-xl flex items-center justify-center flex-shrink-0">
                <svg class="w-7 h-7 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                </svg>
            </div>
            <div>
                <h2 class="text-xl font-semibold text-gray-800">{{ $treatment->name }}</h2>
                <p class="text-xs text-gray-400 mt-0.5">Treatment #{{ $treatment->id }}</p>
            </div>
        </div>

        {{-- Description --}}
        <div>
            <p class="text-xs font-semibold text-rose-600 uppercase tracking-wider mb-2">Description</p>
            @if($treatment->description)
                <p class="text-gray-700 text-sm leading-relaxed">{{ $treatment->description }}</p>
            @else
                <p class="text-gray-400 text-sm italic">No description provided.</p>
            @endif
        </div>

        {{-- Meta --}}
        <div class="mt-6 pt-6 border-t border-gray-100 grid grid-cols-2 gap-4 text-sm">
            <div>
                <p class="text-xs text-gray-400 mb-0.5">Created</p>
                <p class="text-gray-700">{{ $treatment->created_at->format('d M Y, h:i A') }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-400 mb-0.5">Last Updated</p>
                <p class="text-gray-700">{{ $treatment->updated_at->format('d M Y, h:i A') }}</p>
            </div>
        </div>

    </div>
</div>

@endsection
