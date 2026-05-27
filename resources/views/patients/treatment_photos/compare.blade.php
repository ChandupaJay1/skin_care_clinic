@extends('layouts.app')

@section('title', 'Compare Photos - ' . $patient->full_name)

@section('content')

<div class="mb-6">
    <a href="{{ route('patients.show', $patient) }}"
        class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-rose-600 transition mb-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        Back to Patient Profile
    </a>
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Compare Treatment Photos</h1>
            <p class="text-gray-500 text-sm mt-1">
                <span class="font-medium text-gray-700">{{ $patient->full_name }}</span>
                <span class="font-mono text-rose-500 ml-1">{{ $patient->patient_id }}</span>
            </p>
        </div>
        <a href="{{ route('patients.treatment-photos.create', $patient) }}"
            class="inline-flex items-center gap-2 bg-rose-500 hover:bg-rose-600 text-white text-sm font-medium px-4 py-2 rounded-lg transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Add Photo
        </a>
    </div>
</div>

@if($photosByTreatment->isEmpty())
    <div class="bg-white rounded-xl shadow-sm border border-rose-100 p-16 text-center">
        <div class="w-16 h-16 bg-rose-50 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8 text-rose-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
        </div>
        <p class="text-gray-600 font-medium mb-1">No treatment photos yet</p>
        <p class="text-gray-400 text-sm mb-4">Add photos first to use the comparison tool.</p>
        <a href="{{ route('patients.treatment-photos.create', $patient) }}"
            class="inline-flex items-center gap-2 bg-rose-500 hover:bg-rose-600 text-white text-sm font-medium px-4 py-2 rounded-lg transition">
            Add First Photo
        </a>
    </div>
@else

<div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

    {{-- LEFT: Photo selector panel --}}
    <div class="xl:col-span-1">
        <div class="bg-white rounded-xl shadow-sm border border-rose-100 overflow-hidden sticky top-6">
            <div class="bg-rose-50 px-5 py-3 border-b border-rose-100">
                <h2 class="font-semibold text-rose-700 text-sm flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    Select Photos to Compare
                </h2>
                <p class="text-xs text-gray-400 mt-0.5">Select 2 or more photos</p>
            </div>

            <div class="p-4 max-h-[70vh] overflow-y-auto space-y-5">
                @foreach($photosByTreatment as $treatmentId => $photos)
                @php $treatment = $photos->first()->treatment; @endphp
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2 flex items-center gap-1.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-rose-400 inline-block"></span>
                        {{ $treatment->name }}
                    </p>
                    <div class="space-y-2">
                        @foreach($photos as $photo)
                        <label class="flex items-center gap-3 p-2 rounded-lg hover:bg-rose-50 cursor-pointer transition group">
                            <input type="checkbox"
                                class="photo-checkbox w-4 h-4 rounded border-gray-300 text-rose-500 focus:ring-rose-400 cursor-pointer"
                                value="{{ $photo->id }}"
                                data-url="{{ Storage::url($photo->photo_path) }}"
                                data-date="{{ $photo->taken_on->format('d M Y') }}"
                                data-treatment="{{ $treatment->name }}"
                                data-notes="{{ $photo->notes ?? '' }}"
                                onchange="updateComparison()">
                            <img src="{{ Storage::url($photo->photo_path) }}"
                                class="w-12 h-12 rounded-lg object-cover border border-gray-100 group-hover:border-rose-200 transition flex-shrink-0">
                            <div class="min-w-0">
                                <p class="text-xs font-medium text-gray-700">{{ $photo->taken_on->format('d M Y') }}</p>
                                @if($photo->notes)
                                    <p class="text-xs text-gray-400 truncate">{{ $photo->notes }}</p>
                                @endif
                            </div>
                        </label>
                        @endforeach
                    </div>
                </div>
                @endforeach
            </div>

            <div class="px-4 pb-4 pt-2 border-t border-gray-100">
                <p id="selection-count" class="text-xs text-gray-400 text-center">0 photos selected</p>
                <button onclick="clearSelection()"
                    class="mt-2 w-full text-xs text-gray-500 hover:text-red-500 transition py-1">
                    Clear selection
                </button>
            </div>
        </div>
    </div>

    {{-- RIGHT: Comparison display --}}
    <div class="xl:col-span-2">

        {{-- Empty state --}}
        <div id="compare-empty" class="bg-white rounded-xl shadow-sm border border-rose-100 p-16 text-center">
            <div class="w-16 h-16 bg-purple-50 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-purple-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7"/>
                </svg>
            </div>
            <p class="text-gray-500 font-medium mb-1">Select photos to compare</p>
            <p class="text-gray-400 text-sm">Choose 2 or more photos from the panel on the left.</p>
        </div>

        {{-- Comparison grid --}}
        <div id="compare-grid" class="hidden">
            <div class="flex items-center justify-between mb-3">
                <h2 class="font-semibold text-gray-700 flex items-center gap-2">
                    <svg class="w-5 h-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7"/>
                    </svg>
                    Side-by-Side Comparison
                </h2>
                <button onclick="printComparison()"
                    class="inline-flex items-center gap-1.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-medium px-3 py-1.5 rounded-lg transition">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                    </svg>
                    Print
                </button>
            </div>

            <div id="photos-container" class="grid gap-4">
                {{-- Dynamically populated by JS --}}
            </div>
        </div>

    </div>
</div>

{{-- Hidden print area --}}
<div id="print-area" class="hidden"></div>

<script>
function updateComparison() {
    const checked = document.querySelectorAll('.photo-checkbox:checked');
    const count = checked.length;

    // Update count label
    document.getElementById('selection-count').textContent =
        count === 0 ? '0 photos selected' : `${count} photo${count > 1 ? 's' : ''} selected`;

    const emptyEl  = document.getElementById('compare-empty');
    const gridEl   = document.getElementById('compare-grid');
    const container = document.getElementById('photos-container');

    if (count < 2) {
        emptyEl.classList.remove('hidden');
        gridEl.classList.add('hidden');
        return;
    }

    emptyEl.classList.add('hidden');
    gridEl.classList.remove('hidden');

    // Determine grid columns based on count
    const cols = count === 2 ? 'grid-cols-2' :
                 count === 3 ? 'grid-cols-3' :
                               'grid-cols-2 md:grid-cols-4';

    container.className = `grid gap-4 ${cols}`;
    container.innerHTML = '';

    checked.forEach((cb, index) => {
        const url       = cb.dataset.url;
        const date      = cb.dataset.date;
        const treatment = cb.dataset.treatment;
        const notes     = cb.dataset.notes;

        const card = document.createElement('div');
        card.className = 'bg-white rounded-xl border border-gray-200 overflow-hidden shadow-sm';
        card.innerHTML = `
            <div class="bg-gray-50 px-3 py-2 border-b border-gray-100 flex items-center justify-between">
                <span class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Photo ${index + 1}</span>
                <span class="text-xs text-rose-500 font-medium">${date}</span>
            </div>
            <div class="aspect-square overflow-hidden bg-gray-100">
                <img src="${url}" class="w-full h-full object-cover" alt="${date}">
            </div>
            <div class="p-3">
                <p class="text-xs font-medium text-gray-700">${treatment}</p>
                ${notes ? `<p class="text-xs text-gray-400 mt-1">${notes}</p>` : ''}
            </div>
        `;
        container.appendChild(card);
    });
}

function clearSelection() {
    document.querySelectorAll('.photo-checkbox:checked').forEach(cb => cb.checked = false);
    updateComparison();
}

function printComparison() {
    const checked = document.querySelectorAll('.photo-checkbox:checked');
    if (checked.length < 2) return;

    let photosHtml = '';
    checked.forEach((cb, index) => {
        photosHtml += `
            <div style="display:inline-block; width:${Math.floor(90/checked.length)}%; margin:1%; vertical-align:top; text-align:center; border:1px solid #eee; border-radius:8px; overflow:hidden;">
                <div style="background:#fdf2f8; padding:6px 10px; border-bottom:1px solid #fce7f3; display:flex; justify-content:space-between; align-items:center;">
                    <span style="font-size:11px; color:#9ca3af; font-weight:600; text-transform:uppercase;">Photo ${index + 1}</span>
                    <span style="font-size:11px; color:#f43f5e; font-weight:500;">${cb.dataset.date}</span>
                </div>
                <img src="${cb.dataset.url}" style="width:100%; aspect-ratio:1; object-fit:cover; display:block;">
                <div style="padding:8px;">
                    <p style="font-size:11px; font-weight:600; color:#374151; margin:0 0 2px;">${cb.dataset.treatment}</p>
                    ${cb.dataset.notes ? `<p style="font-size:10px; color:#9ca3af; margin:0;">${cb.dataset.notes}</p>` : ''}
                </div>
            </div>
        `;
    });

    const win = window.open('', '_blank', 'width=900,height=700');
    win.document.write(`
        <html>
        <head>
            <title>Treatment Comparison - {{ $patient->full_name }}</title>
            <style>
                body { font-family: sans-serif; padding: 20px; }
                h2 { font-size: 16px; margin-bottom: 4px; color: #1f2937; }
                p.sub { font-size: 12px; color: #9ca3af; margin-bottom: 16px; }
                @media print { body { padding: 10px; } }
            </style>
        </head>
        <body>
            <h2>Treatment Progress Comparison</h2>
            <p class="sub">{{ $patient->full_name }} &nbsp;·&nbsp; {{ $patient->patient_id }} &nbsp;·&nbsp; Printed {{ now()->format('d M Y') }}</p>
            <div style="white-space:nowrap;">${photosHtml}</div>
        </body>
        </html>
    `);
    win.document.close();
    win.focus();
    setTimeout(() => { win.print(); win.close(); }, 500);
}
</script>

@endif

@endsection
