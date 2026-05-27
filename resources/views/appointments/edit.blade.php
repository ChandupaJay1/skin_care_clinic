@extends('layouts.app')
@section('title', 'Edit Appointment')
@section('page_title', 'Edit Appointment')
@section('page_subtitle', $appointment->booking_number)

@section('content')
<div class="max-w-2xl mx-auto">

<form action="{{ route('appointments.update', $appointment) }}" method="POST" class="space-y-5" id="booking_form">
@csrf @method('PUT')

<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="bg-rose-50 px-6 py-4 border-b border-rose-100">
        <h2 class="text-sm font-semibold text-rose-700">Patient & Doctor</h2>
    </div>
    <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-5">

        <div class="md:col-span-2">
            <label class="block text-sm font-medium text-gray-700 mb-1.5">Patient <span class="text-red-500">*</span></label>
            <select name="patient_id" required
                class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-rose-400 bg-white">
                @foreach($patients as $p)
                    <option value="{{ $p->id }}" {{ old('patient_id', $appointment->patient_id) == $p->id ? 'selected' : '' }}>
                        {{ $p->full_name }} ({{ $p->patient_id }})
                    </option>
                @endforeach
            </select>
        </div>

        <div class="md:col-span-2">
            <label class="block text-sm font-medium text-gray-700 mb-1.5">Doctor <span class="text-red-500">*</span></label>
            <select name="doctor_id" id="doctor_id" required
                class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-rose-400 bg-white">
                @foreach($doctors as $d)
                    <option value="{{ $d->id }}" {{ old('doctor_id', $appointment->doctor_id) == $d->id ? 'selected' : '' }}>
                        Dr. {{ $d->full_name }} — {{ $d->specialization }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="md:col-span-2">
            <label class="block text-sm font-medium text-gray-700 mb-1.5">Treatment</label>
            <select name="treatment_id"
                class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-rose-400 bg-white">
                <option value="">— No specific treatment —</option>
                @foreach($treatments as $t)
                    <option value="{{ $t->id }}" {{ old('treatment_id', $appointment->treatment_id) == $t->id ? 'selected' : '' }}>
                        {{ $t->name }}
                    </option>
                @endforeach
            </select>
        </div>

    </div>
</div>

<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="bg-rose-50 px-6 py-4 border-b border-rose-100">
        <h2 class="text-sm font-semibold text-rose-700">Date, Time & Status</h2>
    </div>
    <div class="p-6 space-y-5">

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Date <span class="text-red-500">*</span></label>
                <input type="date" name="appointment_date" id="appointment_date"
                    value="{{ old('appointment_date', $appointment->appointment_date->format('Y-m-d')) }}" required
                    class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-rose-400">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Status <span class="text-red-500">*</span></label>
                <select name="status"
                    class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-rose-400 bg-white">
                    @foreach(['scheduled'=>'Scheduled','completed'=>'Completed','cancelled'=>'Cancelled','no_show'=>'No Show'] as $val => $lbl)
                    <option value="{{ $val }}" {{ old('status', $appointment->status) == $val ? 'selected' : '' }}>{{ $lbl }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
                Time <span class="text-red-500">*</span>
                <span id="slots_loading" class="hidden text-xs text-gray-400 font-normal ml-2">Loading…</span>
            </label>
            <div id="time_slots" class="grid grid-cols-4 sm:grid-cols-6 gap-2"></div>
            <input type="hidden" name="appointment_time" id="appointment_time"
                value="{{ old('appointment_time', substr($appointment->appointment_time, 0, 5)) }}" required>
            @error('appointment_time') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1.5">Notes</label>
            <textarea name="notes" rows="2"
                class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-rose-400 resize-none">{{ old('notes', $appointment->notes) }}</textarea>
        </div>

    </div>
</div>

<div class="flex items-center justify-end gap-3">
    <a href="{{ route('appointments.show', $appointment) }}"
        class="px-5 py-2.5 text-sm font-medium text-gray-600 bg-white border border-gray-200 rounded-xl hover:bg-gray-50 transition">
        Cancel
    </a>
    <button type="submit"
        class="px-6 py-2.5 text-sm font-semibold text-white bg-rose-500 hover:bg-rose-600 rounded-xl transition flex items-center gap-2 shadow-sm shadow-rose-200">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
        </svg>
        Save Changes
    </button>
</div>

</form>
</div>

<script>
const ALL_SLOTS = [
    '08:00','08:30','09:00','09:30','10:00','10:30',
    '11:00','11:30','12:00','12:30','13:00','13:30',
    '14:00','14:30','15:00','15:30','16:00','16:30','17:00'
];
const CURRENT_APPT_ID = {{ $appointment->id }};
let selectedTime = document.getElementById('appointment_time').value || null;

function renderSlots(bookedSlots) {
    const container = document.getElementById('time_slots');
    container.innerHTML = '';
    ALL_SLOTS.forEach(slot => {
        const isBooked   = bookedSlots.includes(slot);
        const isSelected = slot === selectedTime;
        const btn = document.createElement('button');
        btn.type = 'button';
        btn.textContent = formatTime(slot);
        btn.dataset.time = slot;
        if (isBooked) {
            btn.className = 'py-2 px-1 text-xs rounded-xl border border-gray-100 bg-gray-50 text-gray-300 cursor-not-allowed line-through';
            btn.disabled = true;
        } else if (isSelected) {
            btn.className = 'py-2 px-1 text-xs rounded-xl border-2 border-rose-500 bg-rose-500 text-white font-semibold';
        } else {
            btn.className = 'py-2 px-1 text-xs rounded-xl border border-gray-200 bg-white text-gray-700 hover:border-rose-400 hover:text-rose-600 transition';
        }
        btn.addEventListener('click', () => { selectedTime = slot; document.getElementById('appointment_time').value = slot; renderSlots(bookedSlots); });
        container.appendChild(btn);
    });
}
function formatTime(t) {
    const [h, m] = t.split(':').map(Number);
    return `${h%12||12}:${String(m).padStart(2,'0')} ${h>=12?'PM':'AM'}`;
}
async function loadSlots() {
    const doctorId = document.getElementById('doctor_id').value;
    const date     = document.getElementById('appointment_date').value;
    if (!doctorId || !date) return;
    document.getElementById('slots_loading').classList.remove('hidden');
    try {
        const res    = await fetch(`/appointments/booked-slots?doctor_id=${doctorId}&date=${date}&exclude=${CURRENT_APPT_ID}`);
        const booked = await res.json();
        renderSlots(booked);
    } finally {
        document.getElementById('slots_loading').classList.add('hidden');
    }
}
document.getElementById('doctor_id').addEventListener('change', loadSlots);
document.getElementById('appointment_date').addEventListener('change', loadSlots);
window.addEventListener('DOMContentLoaded', loadSlots);
</script>
@endsection
