@extends('layouts.app')

@section('title', 'Edit Dr. ' . $doctor->full_name . ' - Skin Care Clinic')

@section('content')

<div class="mb-6">
    <a href="{{ route('doctors.show', $doctor) }}"
        class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-rose-600 transition mb-3">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        Back to Doctor
    </a>
    <nav class="text-sm text-gray-500 mb-2">
        <a href="{{ route('doctors.index') }}" class="hover:text-rose-600">Doctors</a>
        <span class="mx-2">/</span>
        <a href="{{ route('doctors.show', $doctor) }}" class="hover:text-rose-600">Dr. {{ $doctor->full_name }}</a>
        <span class="mx-2">/</span>
        <span class="text-gray-700">Edit</span>
    </nav>
    <h1 class="text-2xl font-bold text-gray-800">Edit Doctor Details</h1>
    <p class="text-gray-500 text-sm mt-1">Doctor ID: <span class="font-mono text-rose-600">{{ $doctor->doctor_id }}</span></p>
</div>

<form action="{{ route('doctors.update', $doctor) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
    @csrf
    @method('PUT')

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

            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Full Name <span class="text-red-500">*</span></label>
                <input type="text" name="full_name" value="{{ old('full_name', $doctor->full_name) }}"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-rose-400 @error('full_name') border-red-400 @enderror">
                @error('full_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">NIC Number <span class="text-red-500">*</span></label>
                <div class="relative">
                    <input type="text" name="nic" id="nic_input" value="{{ old('nic', $doctor->nic) }}"
                        maxlength="12" autocomplete="off"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2.5 pr-28 text-sm focus:outline-none focus:ring-2 focus:ring-rose-400 @error('nic') border-red-400 @enderror">
                    <span id="nic_status" class="absolute right-3 top-1/2 -translate-y-1/2 text-xs font-medium hidden"></span>
                </div>
                @error('nic') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Date of Birth <span class="text-red-500">*</span></label>
                <div class="relative">
                    <input type="date" name="date_of_birth" id="dob_input"
                        value="{{ old('date_of_birth', $doctor->date_of_birth->format('Y-m-d')) }}"
                        max="{{ date('Y-m-d') }}"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-rose-400 @error('date_of_birth') border-red-400 @enderror">
                    <span id="dob_auto_badge" class="hidden absolute right-8 top-1/2 -translate-y-1/2 text-xs bg-green-100 text-green-700 px-2 py-0.5 rounded-full pointer-events-none">Auto</span>
                </div>
                @error('date_of_birth') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Gender <span class="text-red-500">*</span></label>
                <div class="relative">
                    <select name="gender" id="gender_input"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-rose-400">
                        <option value="female" {{ old('gender', $doctor->gender) == 'female' ? 'selected' : '' }}>Female</option>
                        <option value="male"   {{ old('gender', $doctor->gender) == 'male'   ? 'selected' : '' }}>Male</option>
                        <option value="other"  {{ old('gender', $doctor->gender) == 'other'  ? 'selected' : '' }}>Other</option>
                    </select>
                    <span id="gender_auto_badge" class="hidden absolute right-8 top-1/2 -translate-y-1/2 text-xs bg-green-100 text-green-700 px-2 py-0.5 rounded-full pointer-events-none">Auto</span>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Phone Number <span class="text-red-500">*</span></label>
                <input type="tel" name="phone" value="{{ old('phone', $doctor->phone) }}"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-rose-400 @error('phone') border-red-400 @enderror">
                @error('phone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                <input type="email" name="email" value="{{ old('email', $doctor->email) }}"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-rose-400 @error('email') border-red-400 @enderror">
                @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Profile Photo --}}
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Profile Photo</label>
                <input type="hidden" name="webcam_photo" id="webcam_photo_data">
                <div class="flex items-start gap-5">
                    <div id="photo-preview" class="w-24 h-24 rounded-full bg-rose-100 flex items-center justify-center overflow-hidden border-2 border-rose-200 flex-shrink-0">
                        @if($doctor->profile_photo)
                            <img src="{{ Storage::url($doctor->profile_photo) }}" class="w-full h-full object-cover">
                        @else
                            <svg class="w-12 h-12 text-rose-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        @endif
                    </div>
                    <div class="flex flex-col gap-2">
                        <div>
                            <input type="file" name="profile_photo" id="profile_photo" accept="image/*" class="hidden" onchange="handleFileUpload(this)">
                            <label for="profile_photo" class="inline-flex items-center gap-2 cursor-pointer bg-white border border-gray-300 text-gray-700 text-sm px-4 py-2 rounded-lg hover:bg-gray-50 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                Upload Photo
                            </label>
                        </div>
                        <button type="button" onclick="openWebcam()"
                            class="inline-flex items-center gap-2 bg-rose-50 border border-rose-200 text-rose-700 text-sm px-4 py-2 rounded-lg hover:bg-rose-100 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 10l4.553-2.069A1 1 0 0121 8.82v6.36a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                            </svg>
                            Use Webcam
                        </button>
                        <p id="photo_source_label" class="text-xs text-gray-400">Leave empty to keep current photo</p>
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- Professional Information --}}
    <div class="bg-white rounded-xl shadow-sm border border-rose-100 overflow-hidden">
        <div class="bg-rose-50 px-6 py-4 border-b border-rose-100">
            <h2 class="text-base font-semibold text-rose-700">Professional Information</h2>
        </div>
        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-5">

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Specialization <span class="text-red-500">*</span></label>
                <input type="text" name="specialization" value="{{ old('specialization', $doctor->specialization) }}"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-rose-400 @error('specialization') border-red-400 @enderror">
                @error('specialization') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Qualification <span class="text-red-500">*</span></label>
                <input type="text" name="qualification" value="{{ old('qualification', $doctor->qualification) }}"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-rose-400 @error('qualification') border-red-400 @enderror">
                @error('qualification') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Medical Council Reg. No. <span class="text-red-500">*</span></label>
                <input type="text" name="registration_number" value="{{ old('registration_number', $doctor->registration_number) }}"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-rose-400 @error('registration_number') border-red-400 @enderror">
                @error('registration_number') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Years of Experience <span class="text-red-500">*</span></label>
                <input type="number" name="experience_years" value="{{ old('experience_years', $doctor->experience_years) }}"
                    min="0" max="60"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-rose-400 @error('experience_years') border-red-400 @enderror">
                @error('experience_years') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status <span class="text-red-500">*</span></label>
                <select name="status"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-rose-400">
                    <option value="active"   {{ old('status', $doctor->status) == 'active'   ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ old('status', $doctor->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    <option value="on_leave" {{ old('status', $doctor->status) == 'on_leave' ? 'selected' : '' }}>On Leave</option>
                </select>
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Bio / Notes</label>
                <textarea name="bio" rows="3"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-rose-400">{{ old('bio', $doctor->bio) }}</textarea>
            </div>

        </div>
    </div>

    <div class="flex items-center justify-end gap-3">
        <a href="{{ route('doctors.show', $doctor) }}"
            class="px-6 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition">
            Cancel
        </a>
        <button type="submit"
            class="px-6 py-2.5 text-sm font-medium text-white bg-rose-500 hover:bg-rose-600 rounded-lg transition flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            Save Changes
        </button>
    </div>

</form>

{{-- Webcam Modal --}}
<div id="webcam_modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60 backdrop-blur-sm p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden">
        <div class="bg-rose-50 px-5 py-4 border-b border-rose-100 flex items-center justify-between">
            <h3 class="font-semibold text-rose-700">Take Photo</h3>
            <button type="button" onclick="closeWebcam()" class="text-gray-400 hover:text-gray-600 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <div class="p-5">
            <div class="relative bg-black rounded-xl overflow-hidden" style="aspect-ratio:4/3;">
                <video id="webcam_video" autoplay playsinline class="w-full h-full object-cover"></video>
                <canvas id="webcam_canvas" class="hidden w-full h-full object-cover"></canvas>
                <div id="cam_guide" class="absolute inset-0 flex items-center justify-center pointer-events-none">
                    <div class="w-48 h-48 rounded-full border-4 border-white/50 border-dashed"></div>
                </div>
                <div id="cam_error" class="hidden absolute inset-0 flex flex-col items-center justify-center bg-gray-900 text-white text-center p-6">
                    <p class="text-sm font-medium">Camera access denied</p>
                </div>
            </div>
            <div class="flex gap-3 mt-4">
                <button type="button" id="btn_capture" onclick="capturePhoto()"
                    class="flex-1 flex items-center justify-center gap-2 bg-rose-500 hover:bg-rose-600 text-white font-medium py-2.5 rounded-lg transition">Capture</button>
                <button type="button" id="btn_retake" onclick="retakePhoto()"
                    class="hidden flex-1 flex items-center justify-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-2.5 rounded-lg transition">Retake</button>
                <button type="button" id="btn_use" onclick="usePhoto()"
                    class="hidden flex-1 flex items-center justify-center gap-2 bg-green-500 hover:bg-green-600 text-white font-medium py-2.5 rounded-lg transition">Use Photo</button>
            </div>
        </div>
    </div>
</div>

<script>
function parseNIC(nic) {
    nic = nic.trim().toUpperCase();
    let year, dayOfYear, isFemale;
    if (/^\d{12}$/.test(nic)) { year = parseInt(nic.substring(0,4)); dayOfYear = parseInt(nic.substring(4,7)); }
    else if (/^\d{9}[VX]$/.test(nic)) { year = 1900+parseInt(nic.substring(0,2)); dayOfYear = parseInt(nic.substring(2,5)); }
    else return null;
    if (dayOfYear > 500) { isFemale = true; dayOfYear -= 500; } else { isFemale = false; }
    const isLeap = (year%4===0 && year%100!==0) || year%400===0;
    if (dayOfYear < 1 || dayOfYear > (isLeap?366:365)) return null;
    const months = [31,isLeap?29:28,31,30,31,30,31,31,30,31,30,31];
    let remaining = dayOfYear-1, month = 1;
    for (let i=0;i<12;i++) { if (remaining<months[i]) { month=i+1; break; } remaining-=months[i]; }
    return { dob:`${year}-${String(month).padStart(2,'0')}-${String(remaining+1).padStart(2,'0')}`, gender:isFemale?'female':'male' };
}
document.getElementById('nic_input').addEventListener('input', function() {
    const nic=this.value.trim(), status=document.getElementById('nic_status'),
          dobInput=document.getElementById('dob_input'), genderSel=document.getElementById('gender_input'),
          dobBadge=document.getElementById('dob_auto_badge'), genBadge=document.getElementById('gender_auto_badge');
    const ready = nic.length===12 || (nic.length===10 && /[VX]$/i.test(nic));
    if (!ready) { status.classList.add('hidden'); dobBadge.classList.add('hidden'); genBadge.classList.add('hidden'); return; }
    const result = parseNIC(nic);
    if (result) {
        dobInput.value=result.dob; genderSel.value=result.gender;
        dobBadge.classList.remove('hidden'); genBadge.classList.remove('hidden');
        status.textContent='✓ Valid'; status.className='absolute right-3 top-1/2 -translate-y-1/2 text-xs font-medium text-green-600'; status.classList.remove('hidden');
    } else {
        dobBadge.classList.add('hidden'); genBadge.classList.add('hidden');
        status.textContent='✗ Invalid'; status.className='absolute right-3 top-1/2 -translate-y-1/2 text-xs font-medium text-red-500'; status.classList.remove('hidden');
    }
});
function handleFileUpload(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => { setPreview(e.target.result); document.getElementById('webcam_photo_data').value=''; document.getElementById('photo_source_label').textContent='📁 File selected'; };
        reader.readAsDataURL(input.files[0]);
    }
}
function setPreview(src) { document.getElementById('photo-preview').innerHTML=`<img src="${src}" class="w-full h-full object-cover">`; }
let camStream = null;
function openWebcam() {
    const modal=document.getElementById('webcam_modal');
    modal.classList.remove('hidden'); modal.classList.add('flex');
    ['webcam_video','cam_guide'].forEach(id=>document.getElementById(id).classList.remove('hidden'));
    ['webcam_canvas','cam_error','btn_retake','btn_use'].forEach(id=>document.getElementById(id).classList.add('hidden'));
    document.getElementById('btn_capture').classList.remove('hidden');
    navigator.mediaDevices.getUserMedia({video:{facingMode:'user',width:640,height:480},audio:false})
        .then(s=>{ camStream=s; document.getElementById('webcam_video').srcObject=s; })
        .catch(()=>{ document.getElementById('cam_error').classList.remove('hidden'); document.getElementById('webcam_video').classList.add('hidden'); document.getElementById('cam_guide').classList.add('hidden'); });
}
function closeWebcam() {
    if (camStream) { camStream.getTracks().forEach(t=>t.stop()); camStream=null; }
    const modal=document.getElementById('webcam_modal'); modal.classList.add('hidden'); modal.classList.remove('flex');
}
function capturePhoto() {
    const v=document.getElementById('webcam_video'), c=document.getElementById('webcam_canvas');
    c.width=v.videoWidth||640; c.height=v.videoHeight||480; c.getContext('2d').drawImage(v,0,0,c.width,c.height);
    v.classList.add('hidden'); c.classList.remove('hidden');
    document.getElementById('cam_guide').classList.add('hidden');
    document.getElementById('btn_capture').classList.add('hidden');
    document.getElementById('btn_retake').classList.remove('hidden');
    document.getElementById('btn_use').classList.remove('hidden');
}
function retakePhoto() {
    document.getElementById('webcam_video').classList.remove('hidden');
    document.getElementById('webcam_canvas').classList.add('hidden');
    document.getElementById('cam_guide').classList.remove('hidden');
    document.getElementById('btn_capture').classList.remove('hidden');
    document.getElementById('btn_retake').classList.add('hidden');
    document.getElementById('btn_use').classList.add('hidden');
}
function usePhoto() {
    const dataUrl=document.getElementById('webcam_canvas').toDataURL('image/jpeg',0.85);
    setPreview(dataUrl);
    document.getElementById('webcam_photo_data').value=dataUrl;
    document.getElementById('profile_photo').value='';
    document.getElementById('photo_source_label').textContent='📷 Webcam photo captured';
    closeWebcam();
}
document.getElementById('webcam_modal').addEventListener('click', function(e) { if(e.target===this) closeWebcam(); });
</script>

@endsection
