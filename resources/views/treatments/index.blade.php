@extends('layouts.app')

@section('title', 'Treatments - Skin Care Clinic')

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
        <h1 class="text-2xl font-bold text-gray-800">Treatments</h1>
        <p class="text-gray-500 text-sm mt-1">{{ $treatments->total() }} treatment(s) available</p>
    </div>
    <a href="{{ route('treatments.create') }}"
        class="inline-flex items-center gap-2 bg-rose-500 hover:bg-rose-600 text-white text-sm font-medium px-4 py-2.5 rounded-lg transition">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Add Treatment
    </a>
</div>

{{-- Search --}}
<div class="bg-white rounded-xl shadow-sm border border-rose-100 p-4 mb-6">
    <form method="GET" action="{{ route('treatments.index') }}" class="flex gap-3">
        <div class="flex-1 relative">
            <svg class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <input type="text" name="search" value="{{ request('search') }}"
                placeholder="Search by name or description..."
                class="w-full pl-10 pr-4 py-2.5 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-rose-400 focus:border-transparent">
        </div>
        <button type="submit"
            class="bg-rose-500 hover:bg-rose-600 text-white text-sm font-medium px-5 py-2.5 rounded-lg transition">
            Search
        </button>
        @if(request('search'))
        <a href="{{ route('treatments.index') }}"
            class="bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium px-4 py-2.5 rounded-lg transition">
            Clear
        </a>
        @endif
    </form>
</div>

{{-- Treatments Table --}}
<div class="bg-white rounded-xl shadow-sm border border-rose-100 overflow-hidden">
    @if($treatments->count() > 0)
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-rose-50 border-b border-rose-100">
                    <th class="text-left px-6 py-3 text-xs font-semibold text-rose-600 uppercase tracking-wider">#</th>
                    <th class="text-left px-6 py-3 text-xs font-semibold text-rose-600 uppercase tracking-wider">Name</th>
                    <th class="text-left px-6 py-3 text-xs font-semibold text-rose-600 uppercase tracking-wider">Description</th>
                    <th class="text-left px-6 py-3 text-xs font-semibold text-rose-600 uppercase tracking-wider">Added</th>
                    <th class="px-6 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($treatments as $treatment)
                <tr class="hover:bg-rose-50/30 transition">
                    <td class="px-6 py-4 text-gray-400 text-xs">{{ $loop->iteration + ($treatments->currentPage() - 1) * $treatments->perPage() }}</td>
                    <td class="px-6 py-4">
                        <p class="font-medium text-gray-800">{{ $treatment->name }}</p>
                    </td>
                    <td class="px-6 py-4 text-gray-500 max-w-xs">
                        {{ $treatment->description ? Str::limit($treatment->description, 80) : '—' }}
                    </td>
                    <td class="px-6 py-4 text-gray-400 text-xs">
                        {{ $treatment->created_at->format('d M Y') }}
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2 justify-end">
                            <a href="{{ route('treatments.show', $treatment) }}"
                                class="text-rose-500 hover:text-rose-700 transition" title="View">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </a>
                            <a href="{{ route('treatments.edit', $treatment) }}"
                                class="text-blue-500 hover:text-blue-700 transition" title="Edit">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </a>
                            <form action="{{ route('treatments.destroy', $treatment) }}" method="POST"
                                onsubmit="return confirm('Delete treatment \'{{ addslashes($treatment->name) }}\'? This cannot be undone.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-400 hover:text-red-600 transition" title="Delete">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($treatments->hasPages())
    <div class="px-6 py-4 border-t border-gray-100">
        {{ $treatments->links() }}
    </div>
    @endif

    @else
    <div class="text-center py-16">
        <div class="w-16 h-16 bg-rose-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
        </div>
        <h3 class="text-gray-600 font-medium mb-1">No treatments found</h3>
        <p class="text-gray-400 text-sm mb-4">
            @if(request('search'))
                No results for "{{ request('search') }}"
            @else
                Add your first treatment to get started.
            @endif
        </p>
        @if(!request('search'))
        <a href="{{ route('treatments.create') }}"
            class="inline-flex items-center gap-2 bg-rose-500 hover:bg-rose-600 text-white text-sm font-medium px-4 py-2 rounded-lg transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Add First Treatment
        </a>
        @endif
    </div>
    @endif
</div>

@endsection
