@extends('layouts.app')

@section('title', 'Add Treatment Photo - ' . $patient->full_name)

@section('content')

<div class="mb-6">
    <a href="{{ route('patients.show', $patient) }}"
        class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-rose-600 transition mb-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        Back to Patient Profile
    </a>
    <h1 class="text-2xl font-bold text-gray-800">Add Treatment Photo</h1>
    <p class="text-gray-500 text-sm mt-1">
        Uploading for <span class="font-medium text-gray-700">{{ $patient->full_name }}</span>
        <span class="font-mono text-rose-500 ml-1">{{ $patient->patient_id }}</span>
    </p>
</div>

<div class="max-w-xl">
    <div class="bg-white rounded-xl shadow-sm border border-rose-100 p-6">
        <form action="{{ route('patients.treatment-photos.store', $patient) }}" method="POST" enctype="multipart/form-data">
            @csrf

            {{-- Treatment --}}
            <div class="mb-5">
                <label for="treatment_id" class="block text-sm font-medium text-gray-700 mb-1.5">
                    Treatment <span class="text-red-500">*</span>
                </label>
                <select id="treatment_id" name="treatment_id"
                    class="w-full px-4 py-2.5 text-sm border {{ $errors->has('treatment_id') ? 'border-red-400 bg-red-50' : 'border-gray-300' }} rounded-lg focus:outline-none focus:ring-2 focus:ring-rose-400 focus:border-transparent">
                    <option value="">— Select treatment —</option>
                    @foreach($treatments as $treatment)
                        <option value="{{ $treatment->id }}" {{ old('treatment_id') == $treatment->id ? 'selected' : '' }}>
                            {{ $treatment->name }}
                        </option>
                    @endforeach
                </select>
                @error('treatment_id')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Date taken --}}
            <div class="mb-5">
                <label for="taken_on" class="block text-sm font-medium text-gray-700 mb-1.5">
                    Date Photo Taken <span class="text-red-500">*</span>
                </label>
                <input type="date" id="taken_on" name="taken_on"
                    value="{{ old('taken_on', date('Y-m-d')) }}"
                    max="{{ date('Y-m-d') }}"
                    class="w-full px-4 py-2.5 text-sm border {{ $errors->has('taken_on') ? 'border-red-400 bg-red-50' : 'border-gray-300' }} rounded-lg focus:outline-none focus:ring-2 focus:ring-rose-400 focus:border-transparent">
                @error('taken_on')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Photo upload --}}
            <div class="mb-5">
                <label class="block text-sm font-medium text-gray-700 mb-1.5">
                    Photo <span class="text-red-500">*</span>
                </label>

                {{-- Drop zone --}}
                <div id="drop-zone"
                    class="relative border-2 border-dashed {{ $errors->has('photo') ? 'border-red-400 bg-red-50' : 'border-gray-300 hover:border-rose-400' }} rounded-xl p-6 text-center cursor-pointer transition">
                    <input type="file" id="photo" name="photo" accept="image/jpeg,image/png,image/jpg"
                        class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                        onchange="previewPhoto(this)">

                    <div id="upload-placeholder">
                        <div class="w-12 h-12 bg-rose-50 rounded-full flex items-center justify-center mx-auto mb-3">
                            <svg class="w-6 h-6 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <p class="text-sm text-gray-600 font-medium">Click or drag to upload photo</p>
                        <p class="text-xs text-gray-400 mt-1">JPEG, PNG — max 5 MB</p>
                    </div>

                    <div id="preview-container" class="hidden">
                        <img id="photo-preview" src="" alt="Preview"
                            class="max-h-48 mx-auto rounded-lg object-contain mb-2">
                        <p id="preview-name" class="text-xs text-gray-500"></p>
                    </div>
                </div>

                @error('photo')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Notes --}}
            <div class="mb-6">
                <label for="notes" class="block text-sm font-medium text-gray-700 mb-1.5">
                    Doctor's Notes
                    <span class="text-gray-400 font-normal">(optional)</span>
                </label>
                <textarea id="notes" name="notes" rows="3"
                    placeholder="Observations, treatment response, skin condition..."
                    class="w-full px-4 py-2.5 text-sm border {{ $errors->has('notes') ? 'border-red-400 bg-red-50' : 'border-gray-300' }} rounded-lg focus:outline-none focus:ring-2 focus:ring-rose-400 focus:border-transparent resize-none">{{ old('notes') }}</textarea>
                @error('notes')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Actions --}}
            <div class="flex items-center gap-3">
                <button type="submit"
                    class="bg-rose-500 hover:bg-rose-600 text-white text-sm font-medium px-6 py-2.5 rounded-lg transition">
                    Save Photo
                </button>
                <a href="{{ route('patients.show', $patient) }}"
                    class="bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium px-5 py-2.5 rounded-lg transition">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>

<script>
function previewPhoto(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('upload-placeholder').classList.add('hidden');
            document.getElementById('preview-container').classList.remove('hidden');
            document.getElementById('photo-preview').src = e.target.result;
            document.getElementById('preview-name').textContent = input.files[0].name;
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>

@endsection
