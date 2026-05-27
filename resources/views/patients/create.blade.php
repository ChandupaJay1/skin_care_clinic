@extends('layouts.app')

@section('title', 'Register New Patient - Skin Care Clinic')

@section('content')

<div class="mb-6">
    <a href="{{ route('patients.index') }}"
        class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-rose-600 transition mb-3">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        Back to Patients
    </a>
    <nav class="text-sm text-gray-500 mb-2">
        <a href="{{ route('patients.index') }}" class="hover:text-rose-600">Patients</a>
        <span class="mx-2">/</span>
        <span class="text-gray-700">Register New Patient</span>
    </nav>
    <h1 class="text-2xl font-bold text-gray-800">Register New Patient</h1>
    <p class="text-gray-500 text-sm mt-1">Fill in the patient's details to create a new record.</p>
</div>

<form action="{{ route('patients.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
    @csrf

    {{-- Personal Information --}}
    <div class="bg-white rounded-xl shadow-sm border border-rose-100 overflow-hidden">
        <div class="bg-rose-50 px-6 py-4 border-b border-rose-100">
            <h2 class="text-base font-semibold text-rose-700 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                Personal Information
            </h2>
        </div>
        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-5">

            {{-- Full Name --}}
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Full Name <span class="text-red-500">*</span>
                </label>
                <input type="text" name="full_name" value="{{ old('full_name') }}"
                    placeholder="Enter patient's full name"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-rose-400 focus:border-transparent @error('full_name') border-red-400 @enderror">
                @error('full_name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- NIC — entered first, auto-fills DOB & Gender --}}
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    NIC Number <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <input type="text" name="nic" id="nic_input" value="{{ old('nic') }}"
                        placeholder="e.g. 199012345678 or 901234567V"
                        maxlength="12"
                        autocomplete="off"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2.5 pr-28 text-sm focus:outline-none focus:ring-2 focus:ring-rose-400 focus:border-transparent @error('nic') border-red-400 @enderror">
                    <span id="nic_status" class="absolute right-3 top-1/2 -translate-y-1/2 text-xs font-medium hidden"></span>
                </div>
                <p class="text-xs text-gray-400 mt-1">NIC type karanna — Date of Birth සහ Gender auto-fill වේ</p>
                @error('nic')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Date of Birth (auto-filled from NIC) --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Date of Birth <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <input type="date" name="date_of_birth" id="dob_input" value="{{ old('date_of_birth') }}"
                        max="{{ date('Y-m-d') }}"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-rose-400 focus:border-transparent @error('date_of_birth') border-red-400 @enderror">
                    <span id="dob_auto_badge"
                        class="hidden absolute right-8 top-1/2 -translate-y-1/2 text-xs bg-green-100 text-green-700 px-2 py-0.5 rounded-full pointer-events-none">
                        Auto ✓
                    </span>
                </div>
                @error('date_of_birth')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Gender (auto-filled from NIC) --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Gender <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <select name="gender" id="gender_input"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-rose-400 focus:border-transparent @error('gender') border-red-400 @enderror">
                        <option value="">Select gender</option>
                        <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                        <option value="male"   {{ old('gender') == 'male'   ? 'selected' : '' }}>Male</option>
                        <option value="other"  {{ old('gender') == 'other'  ? 'selected' : '' }}>Other</option>
                    </select>
                    <span id="gender_auto_badge"
                        class="hidden absolute right-8 top-1/2 -translate-y-1/2 text-xs bg-green-100 text-green-700 px-2 py-0.5 rounded-full pointer-events-none">
                        Auto ✓
                    </span>
                </div>
                @error('gender')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Phone --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Phone Number <span class="text-red-500">*</span>
                </label>
                <input type="tel" name="phone" value="{{ old('phone') }}"
                    placeholder="e.g. 0771234567"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-rose-400 focus:border-transparent @error('phone') border-red-400 @enderror">
                @error('phone')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Email --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Email Address
                </label>
                <input type="email" name="email" value="{{ old('email') }}"
                    placeholder="patient@example.com"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-rose-400 focus:border-transparent @error('email') border-red-400 @enderror">
                @error('email')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Address --}}
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Address <span class="text-red-500">*</span>
                </label>
                <textarea name="address" rows="2"
                    placeholder="Enter full address"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-rose-400 focus:border-transparent @error('address') border-red-400 @enderror">{{ old('address') }}</textarea>
                @error('address')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Profile Photo --}}
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Profile Photo</label>

                {{-- Hidden input to carry webcam captured image as base64 --}}
                <input type="hidden" name="webcam_photo" id="webcam_photo_data">

                <div class="flex items-start gap-5">
                    {{-- Preview circle --}}
                    <div id="photo-preview"
                        class="w-24 h-24 rounded-full bg-rose-100 flex items-center justify-center overflow-hidden border-2 border-rose-200 flex-shrink-0">
                        <svg class="w-12 h-12 text-rose-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>

                    {{-- Buttons --}}
                    <div class="flex flex-col gap-2">
                        {{-- File upload --}}
                        <div>
                            <input type="file" name="profile_photo" id="profile_photo" accept="image/*"
                                class="hidden" onchange="handleFileUpload(this)">
                            <label for="profile_photo"
                                class="inline-flex items-center gap-2 cursor-pointer bg-white border border-gray-300 text-gray-700 text-sm px-4 py-2 rounded-lg hover:bg-gray-50 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                Upload Photo
                            </label>
                        </div>

                        {{-- Webcam button --}}
                        <button type="button" onclick="openWebcam()"
                            class="inline-flex items-center gap-2 bg-rose-50 border border-rose-200 text-rose-700 text-sm px-4 py-2 rounded-lg hover:bg-rose-100 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 10l4.553-2.069A1 1 0 0121 8.82v6.36a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                            </svg>
                            Use Webcam
                        </button>

                        <p id="photo_source_label" class="text-xs text-gray-400">JPEG, PNG up to 2MB</p>
                    </div>
                </div>

                @error('profile_photo')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

        </div>
    </div>

    {{-- Emergency Contact --}}
    <div class="bg-white rounded-xl shadow-sm border border-rose-100 overflow-hidden">
        <div class="bg-rose-50 px-6 py-4 border-b border-rose-100">
            <h2 class="text-base font-semibold text-rose-700 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                </svg>
                Emergency Contact
            </h2>
        </div>
        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Contact Name <span class="text-red-500">*</span>
                </label>
                <input type="text" name="emergency_contact_name" value="{{ old('emergency_contact_name') }}"
                    placeholder="Emergency contact's name"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-rose-400 focus:border-transparent @error('emergency_contact_name') border-red-400 @enderror">
                @error('emergency_contact_name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Contact Phone <span class="text-red-500">*</span>
                </label>
                <input type="tel" name="emergency_contact_phone" value="{{ old('emergency_contact_phone') }}"
                    placeholder="e.g. 0771234567"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-rose-400 focus:border-transparent @error('emergency_contact_phone') border-red-400 @enderror">
                @error('emergency_contact_phone')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>

    {{-- Skin & Medical Information --}}
    <div class="bg-white rounded-xl shadow-sm border border-rose-100 overflow-hidden">
        <div class="bg-rose-50 px-6 py-4 border-b border-rose-100">
            <h2 class="text-base font-semibold text-rose-700 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Skin & Medical Information
            </h2>
        </div>
        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Skin Type <span class="text-red-500">*</span>
                </label>
                <select name="skin_type"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-rose-400 focus:border-transparent @error('skin_type') border-red-400 @enderror">
                    <option value="">Select skin type</option>
                    <option value="normal"      {{ old('skin_type') == 'normal'      ? 'selected' : '' }}>Normal</option>
                    <option value="dry"         {{ old('skin_type') == 'dry'         ? 'selected' : '' }}>Dry</option>
                    <option value="oily"        {{ old('skin_type') == 'oily'        ? 'selected' : '' }}>Oily</option>
                    <option value="combination" {{ old('skin_type') == 'combination' ? 'selected' : '' }}>Combination</option>
                    <option value="sensitive"   {{ old('skin_type') == 'sensitive'   ? 'selected' : '' }}>Sensitive</option>
                </select>
                @error('skin_type')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Known Allergies</label>
                <input type="text" name="known_allergies" value="{{ old('known_allergies') }}"
                    placeholder="e.g. Penicillin, Latex (optional)"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-rose-400 focus:border-transparent">
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Medical History</label>
                <textarea name="medical_history" rows="3"
                    placeholder="Any relevant medical conditions, previous treatments, medications... (optional)"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-rose-400 focus:border-transparent">{{ old('medical_history') }}</textarea>
            </div>
        </div>
    </div>

    {{-- Submit Buttons --}}
    <div class="flex items-center justify-end gap-3">
        <a href="{{ route('patients.index') }}"
            class="px-6 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition">
            Cancel
        </a>
        <button type="submit"
            class="px-6 py-2.5 text-sm font-medium text-white bg-rose-500 hover:bg-rose-600 rounded-lg transition flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            Register Patient
        </button>
    </div>

</form>

{{-- ═══ Webcam Modal ═══════════════════════════════════════════════════════ --}}
<div id="webcam_modal"
    class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60 backdrop-blur-sm p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden">

        {{-- Header --}}
        <div class="bg-rose-50 px-5 py-4 border-b border-rose-100 flex items-center justify-between">
            <h3 class="font-semibold text-rose-700 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 10l4.553-2.069A1 1 0 0121 8.82v6.36a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                </svg>
                Take Photo
            </h3>
            <button type="button" onclick="closeWebcam()"
                class="text-gray-400 hover:text-gray-600 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        {{-- Camera view --}}
        <div class="p-5">
            <div class="relative bg-black rounded-xl overflow-hidden" style="aspect-ratio:4/3;">
                <video id="webcam_video" autoplay playsinline
                    class="w-full h-full object-cover"></video>
                <canvas id="webcam_canvas" class="hidden w-full h-full object-cover"></canvas>

                {{-- Overlay guide circle --}}
                <div id="cam_guide"
                    class="absolute inset-0 flex items-center justify-center pointer-events-none">
                    <div class="w-48 h-48 rounded-full border-4 border-white/50 border-dashed"></div>
                </div>

                {{-- Error message --}}
                <div id="cam_error"
                    class="hidden absolute inset-0 flex flex-col items-center justify-center bg-gray-900 text-white text-center p-6">
                    <svg class="w-12 h-12 text-red-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                    </svg>
                    <p class="text-sm font-medium">Camera access denied</p>
                    <p class="text-xs text-gray-400 mt-1">Please allow camera permission in your browser and try again.</p>
                </div>
            </div>

            {{-- Controls --}}
            <div id="cam_controls" class="flex gap-3 mt-4">
                <button type="button" id="btn_capture" onclick="capturePhoto()"
                    class="flex-1 flex items-center justify-center gap-2 bg-rose-500 hover:bg-rose-600 text-white font-medium py-2.5 rounded-lg transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    Capture
                </button>
                <button type="button" id="btn_retake" onclick="retakePhoto()"
                    class="hidden flex-1 flex items-center justify-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-2.5 rounded-lg transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    Retake
                </button>
                <button type="button" id="btn_use" onclick="usePhoto()"
                    class="hidden flex-1 flex items-center justify-center gap-2 bg-green-500 hover:bg-green-600 text-white font-medium py-2.5 rounded-lg transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Use Photo
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// ─── Sri Lanka NIC Parser ───────────────────────────────────────────────────
function parseNIC(nic) {
    nic = nic.trim().toUpperCase();

    let year, dayOfYear, isFemale;

    if (/^\d{12}$/.test(nic)) {
        year      = parseInt(nic.substring(0, 4));
        dayOfYear = parseInt(nic.substring(4, 7));
    } else if (/^\d{9}[VX]$/.test(nic)) {
        year      = 1900 + parseInt(nic.substring(0, 2));
        dayOfYear = parseInt(nic.substring(2, 5));
    } else {
        return null;
    }

    if (dayOfYear > 500) { isFemale = true; dayOfYear -= 500; }
    else { isFemale = false; }

    const isLeap = (year % 4 === 0 && year % 100 !== 0) || year % 400 === 0;
    const maxDay = isLeap ? 366 : 365;
    if (dayOfYear < 1 || dayOfYear > maxDay) return null;

    const dateDay = dayOfYear - 1;
    const months  = [31, isLeap ? 29 : 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];
    let remaining = dateDay, month = 1;
    for (let i = 0; i < 12; i++) {
        if (remaining < months[i]) { month = i + 1; break; }
        remaining -= months[i];
    }
    const day = remaining + 1;

    return {
        dob    : `${year}-${String(month).padStart(2,'0')}-${String(day).padStart(2,'0')}`,
        gender : isFemale ? 'female' : 'male',
    };
}

document.getElementById('nic_input').addEventListener('input', function () {
    const nic       = this.value.trim();
    const status    = document.getElementById('nic_status');
    const dobInput  = document.getElementById('dob_input');
    const genderSel = document.getElementById('gender_input');
    const dobBadge  = document.getElementById('dob_auto_badge');
    const genBadge  = document.getElementById('gender_auto_badge');

    const ready = nic.length === 12 || (nic.length === 10 && /[VX]$/i.test(nic));
    if (!ready) {
        status.className = 'absolute right-3 top-1/2 -translate-y-1/2 text-xs font-medium hidden';
        dobBadge.classList.add('hidden');
        genBadge.classList.add('hidden');
        return;
    }

    const result = parseNIC(nic);
    if (result) {
        dobInput.value  = result.dob;
        genderSel.value = result.gender;
        dobBadge.classList.remove('hidden');
        genBadge.classList.remove('hidden');
        status.textContent = '✓ Valid';
        status.className = 'absolute right-3 top-1/2 -translate-y-1/2 text-xs font-medium text-green-600';
        status.classList.remove('hidden');
    } else {
        dobBadge.classList.add('hidden');
        genBadge.classList.add('hidden');
        status.textContent = '✗ Invalid';
        status.className = 'absolute right-3 top-1/2 -translate-y-1/2 text-xs font-medium text-red-500';
        status.classList.remove('hidden');
    }
});

window.addEventListener('DOMContentLoaded', function () {
    const nicInput = document.getElementById('nic_input');
    if (nicInput.value) nicInput.dispatchEvent(new Event('input'));
});

// ─── File upload preview ────────────────────────────────────────────────────
function handleFileUpload(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            setPreview(e.target.result);
            // Clear webcam data since file is chosen
            document.getElementById('webcam_photo_data').value = '';
            document.getElementById('photo_source_label').textContent = '📁 File selected';
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function setPreview(src) {
    document.getElementById('photo-preview').innerHTML =
        `<img src="${src}" class="w-full h-full object-cover">`;
}

// ─── Webcam ─────────────────────────────────────────────────────────────────
let camStream = null;

function openWebcam() {
    const modal   = document.getElementById('webcam_modal');
    const video   = document.getElementById('webcam_video');
    const canvas  = document.getElementById('webcam_canvas');
    const errDiv  = document.getElementById('cam_error');
    const guide   = document.getElementById('cam_guide');

    modal.classList.remove('hidden');
    modal.classList.add('flex');

    // Reset state
    video.classList.remove('hidden');
    canvas.classList.add('hidden');
    guide.classList.remove('hidden');
    errDiv.classList.add('hidden');
    document.getElementById('btn_capture').classList.remove('hidden');
    document.getElementById('btn_retake').classList.add('hidden');
    document.getElementById('btn_use').classList.add('hidden');

    navigator.mediaDevices.getUserMedia({ video: { facingMode: 'user', width: 640, height: 480 }, audio: false })
        .then(stream => {
            camStream = stream;
            video.srcObject = stream;
        })
        .catch(() => {
            errDiv.classList.remove('hidden');
            video.classList.add('hidden');
            guide.classList.add('hidden');
        });
}

function closeWebcam() {
    stopStream();
    const modal = document.getElementById('webcam_modal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

function stopStream() {
    if (camStream) {
        camStream.getTracks().forEach(t => t.stop());
        camStream = null;
    }
}

function capturePhoto() {
    const video  = document.getElementById('webcam_video');
    const canvas = document.getElementById('webcam_canvas');

    canvas.width  = video.videoWidth  || 640;
    canvas.height = video.videoHeight || 480;
    canvas.getContext('2d').drawImage(video, 0, 0, canvas.width, canvas.height);

    // Show captured frame
    video.classList.add('hidden');
    canvas.classList.remove('hidden');
    document.getElementById('cam_guide').classList.add('hidden');
    document.getElementById('btn_capture').classList.add('hidden');
    document.getElementById('btn_retake').classList.remove('hidden');
    document.getElementById('btn_use').classList.remove('hidden');
}

function retakePhoto() {
    const video  = document.getElementById('webcam_video');
    const canvas = document.getElementById('webcam_canvas');

    video.classList.remove('hidden');
    canvas.classList.add('hidden');
    document.getElementById('cam_guide').classList.remove('hidden');
    document.getElementById('btn_capture').classList.remove('hidden');
    document.getElementById('btn_retake').classList.add('hidden');
    document.getElementById('btn_use').classList.add('hidden');
}

function usePhoto() {
    const canvas   = document.getElementById('webcam_canvas');
    const dataUrl  = canvas.toDataURL('image/jpeg', 0.85);

    // Set preview
    setPreview(dataUrl);

    // Store base64 in hidden field, clear file input
    document.getElementById('webcam_photo_data').value = dataUrl;
    document.getElementById('profile_photo').value     = '';
    document.getElementById('photo_source_label').textContent = '📷 Webcam photo captured';

    closeWebcam();
}

// Close modal on backdrop click
document.getElementById('webcam_modal').addEventListener('click', function(e) {
    if (e.target === this) closeWebcam();
});
</script>

@endsection
