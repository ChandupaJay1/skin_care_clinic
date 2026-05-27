@extends('layouts.app')

@section('title', 'Edit Treatment - Skin Care Clinic')

@section('content')

<div class="mb-6">
    <a href="{{ route('treatments.show', $treatment) }}"
        class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-rose-600 transition mb-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        Back to Treatment
    </a>
    <h1 class="text-2xl font-bold text-gray-800">Edit Treatment</h1>
    <p class="text-gray-500 text-sm mt-1">Update the details for "{{ $treatment->name }}".</p>
</div>

<div class="max-w-2xl">
    <div class="bg-white rounded-xl shadow-sm border border-rose-100 p-6">
        <form action="{{ route('treatments.update', $treatment) }}" method="POST">
            @csrf
            @method('PUT')

            {{-- Name --}}
            <div class="mb-5">
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1.5">
                    Treatment Name <span class="text-red-500">*</span>
                </label>
                <input type="text" id="name" name="name" value="{{ old('name', $treatment->name) }}"
                    placeholder="e.g. Chemical Peel, Laser Therapy..."
                    class="w-full px-4 py-2.5 text-sm border {{ $errors->has('name') ? 'border-red-400 bg-red-50' : 'border-gray-300' }} rounded-lg focus:outline-none focus:ring-2 focus:ring-rose-400 focus:border-transparent">
                @error('name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Description --}}
            <div class="mb-6">
                <label for="description" class="block text-sm font-medium text-gray-700 mb-1.5">
                    Description
                    <span class="text-gray-400 font-normal">(optional)</span>
                </label>
                <textarea id="description" name="description" rows="4"
                    placeholder="Brief description of the treatment, what it involves, benefits..."
                    class="w-full px-4 py-2.5 text-sm border {{ $errors->has('description') ? 'border-red-400 bg-red-50' : 'border-gray-300' }} rounded-lg focus:outline-none focus:ring-2 focus:ring-rose-400 focus:border-transparent resize-none">{{ old('description', $treatment->description) }}</textarea>
                @error('description')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
                <p class="text-xs text-gray-400 mt-1">Max 1000 characters.</p>
            </div>

            {{-- Actions --}}
            <div class="flex items-center gap-3">
                <button type="submit"
                    class="bg-rose-500 hover:bg-rose-600 text-white text-sm font-medium px-6 py-2.5 rounded-lg transition">
                    Update Treatment
                </button>
                <a href="{{ route('treatments.show', $treatment) }}"
                    class="bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium px-5 py-2.5 rounded-lg transition">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>

@endsection
