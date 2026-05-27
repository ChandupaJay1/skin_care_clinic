@extends('layouts.app')
@section('title', 'New Invoice')
@section('page_title', 'New Invoice')
@section('page_subtitle', 'Create a patient invoice')

@section('content')
<div class="max-w-3xl mx-auto">
<form action="{{ route('invoices.store') }}" method="POST" id="invoice_form" class="space-y-5">
@csrf

{{-- Patient --}}
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="bg-rose-50 px-6 py-4 border-b border-rose-100">
        <h2 class="text-sm font-semibold text-rose-700 flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
            Patient
        </h2>
    </div>
    <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-5">
        <div class="md:col-span-2">
            <label class="block text-sm font-medium text-gray-700 mb-1.5">Select Patient <span class="text-red-500">*</span></label>
            <select name="patient_id" id="patient_id" required
                class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-rose-400 bg-white @error('patient_id') border-red-400 @enderror">
                <option value="">— Select patient —</option>
                @foreach($patients as $p)
                    <option value="{{ $p->id }}" {{ old('patient_id', $selectedPatient?->id) == $p->id ? 'selected' : '' }}>
                        {{ $p->full_name }} ({{ $p->patient_id }})
                    </option>
                @endforeach
            </select>
            @error('patient_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>
        <div class="md:col-span-2">
            <label class="block text-sm font-medium text-gray-700 mb-1.5">Linked Appointment <span class="text-gray-400 font-normal">(optional)</span></label>
            <select name="appointment_id" id="appointment_id"
                class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-rose-400 bg-white">
                <option value="">— No linked appointment —</option>
                @if($selectedAppointment)
                    <option value="{{ $selectedAppointment->id }}" selected>
                        #{{ $selectedAppointment->booking_number }} — {{ $selectedAppointment->appointment_date->format('d M Y') }} {{ $selectedAppointment->formatted_time }}
                    </option>
                @endif
            </select>
        </div>
    </div>
</div>

{{-- Line Items --}}
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="bg-rose-50 px-6 py-4 border-b border-rose-100 flex items-center justify-between">
        <h2 class="text-sm font-semibold text-rose-700 flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
            Invoice Items
        </h2>
        <button type="button" onclick="addItem()"
            class="flex items-center gap-1.5 text-xs font-semibold text-rose-600 bg-rose-100 hover:bg-rose-200 px-3 py-1.5 rounded-lg transition">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Add Item
        </button>
    </div>

    <div class="p-6">
        {{-- Header --}}
        <div class="hidden sm:grid grid-cols-12 gap-2 mb-2 px-1">
            <div class="col-span-4 text-xs font-semibold text-gray-400 uppercase tracking-wide">Treatment</div>
            <div class="col-span-3 text-xs font-semibold text-gray-400 uppercase tracking-wide">Description</div>
            <div class="col-span-1 text-xs font-semibold text-gray-400 uppercase tracking-wide text-center">Qty</div>
            <div class="col-span-2 text-xs font-semibold text-gray-400 uppercase tracking-wide text-right">Unit Price</div>
            <div class="col-span-2 text-xs font-semibold text-gray-400 uppercase tracking-wide text-right">Total</div>
        </div>

        <div id="items_container" class="space-y-3"></div>

        @error('items') <p class="text-red-500 text-xs mt-2">{{ $message }}</p> @enderror
    </div>
</div>

{{-- Totals --}}
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="bg-rose-50 px-6 py-4 border-b border-rose-100">
        <h2 class="text-sm font-semibold text-rose-700">Payment Summary</h2>
    </div>
    <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">

        {{-- Left: discount + payment --}}
        <div class="space-y-4">
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1.5">Discount (Rs.)</label>
                    <input type="number" name="discount" id="discount" value="{{ old('discount', 0) }}"
                        min="0" step="0.01" oninput="recalculate()"
                        class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-rose-400">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1.5">Discount (%)</label>
                    <input type="number" name="discount_percent" id="discount_percent" value="{{ old('discount_percent', 0) }}"
                        min="0" max="100" step="0.01" oninput="recalculate()"
                        class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-rose-400">
                </div>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1.5">Paid Amount (Rs.) <span class="text-red-500">*</span></label>
                <input type="number" name="paid_amount" id="paid_amount" value="{{ old('paid_amount', 0) }}"
                    min="0" step="0.01" required oninput="recalculate()"
                    class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-rose-400 @error('paid_amount') border-red-400 @enderror">
                @error('paid_amount') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1.5">Payment Method <span class="text-red-500">*</span></label>
                <select name="payment_method" required
                    class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-rose-400 bg-white">
                    <option value="cash"          {{ old('payment_method','cash')=='cash'          ? 'selected':'' }}>Cash</option>
                    <option value="card"          {{ old('payment_method')=='card'          ? 'selected':'' }}>Card</option>
                    <option value="bank_transfer" {{ old('payment_method')=='bank_transfer' ? 'selected':'' }}>Bank Transfer</option>
                    <option value="other"         {{ old('payment_method')=='other'         ? 'selected':'' }}>Other</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1.5">Notes</label>
                <textarea name="notes" rows="2" placeholder="Optional notes…"
                    class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-rose-400 resize-none">{{ old('notes') }}</textarea>
            </div>
        </div>

        {{-- Right: live totals --}}
        <div class="bg-gray-50 rounded-2xl p-5 space-y-3 self-start">
            <div class="flex justify-between text-sm">
                <span class="text-gray-500">Subtotal</span>
                <span class="font-semibold text-gray-800" id="display_subtotal">Rs. 0.00</span>
            </div>
            <div class="flex justify-between text-sm">
                <span class="text-gray-500">Discount</span>
                <span class="font-semibold text-red-500" id="display_discount">- Rs. 0.00</span>
            </div>
            <div class="border-t border-gray-200 pt-3 flex justify-between">
                <span class="text-sm font-bold text-gray-700">Total</span>
                <span class="text-lg font-bold text-gray-900" id="display_total">Rs. 0.00</span>
            </div>
            <div class="flex justify-between text-sm">
                <span class="text-gray-500">Paid</span>
                <span class="font-semibold text-green-600" id="display_paid">Rs. 0.00</span>
            </div>
            <div class="border-t border-gray-200 pt-3 flex justify-between">
                <span class="text-sm font-bold text-gray-700">Balance Due</span>
                <span class="text-lg font-bold" id="display_balance">Rs. 0.00</span>
            </div>
            <div id="status_badge" class="text-center pt-1">
                <span class="text-xs px-3 py-1 rounded-full font-semibold bg-gray-100 text-gray-500">Draft</span>
            </div>
        </div>

    </div>
</div>

<div class="flex items-center justify-end gap-3">
    <a href="{{ route('invoices.index') }}"
        class="px-5 py-2.5 text-sm font-medium text-gray-600 bg-white border border-gray-200 rounded-xl hover:bg-gray-50 transition">
        Cancel
    </a>
    <button type="submit"
        class="px-6 py-2.5 text-sm font-semibold text-white bg-rose-500 hover:bg-rose-600 rounded-xl transition flex items-center gap-2 shadow-sm shadow-rose-200">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
        </svg>
        Save Invoice
    </button>
</div>

</form>
</div>

{{-- Item row template (hidden) --}}
<template id="item_template">
<div class="item-row grid grid-cols-12 gap-2 items-start bg-gray-50 rounded-xl p-3 border border-gray-100">
    <div class="col-span-12 sm:col-span-4">
        <label class="block text-xs text-gray-500 mb-1 sm:hidden">Treatment</label>
        <select name="items[__IDX__][treatment_id]" class="treatment-select w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-rose-400 bg-white">
            <option value="">— Select treatment —</option>
            @foreach($treatments as $t)
            <option value="{{ $t->id }}" data-name="{{ $t->name }}">{{ $t->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-span-12 sm:col-span-3">
        <label class="block text-xs text-gray-500 mb-1 sm:hidden">Description</label>
        <input type="text" name="items[__IDX__][description]" placeholder="Description" required
            class="desc-input w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-rose-400">
    </div>
    <div class="col-span-4 sm:col-span-1">
        <label class="block text-xs text-gray-500 mb-1 sm:hidden">Qty</label>
        <input type="number" name="items[__IDX__][quantity]" value="1" min="1" required
            class="qty-input w-full border border-gray-200 rounded-lg px-2 py-2 text-sm text-center focus:outline-none focus:ring-2 focus:ring-rose-400">
    </div>
    <div class="col-span-4 sm:col-span-2">
        <label class="block text-xs text-gray-500 mb-1 sm:hidden">Unit Price</label>
        <input type="number" name="items[__IDX__][unit_price]" value="0" min="0" step="0.01" required
            class="price-input w-full border border-gray-200 rounded-lg px-3 py-2 text-sm text-right focus:outline-none focus:ring-2 focus:ring-rose-400">
    </div>
    <div class="col-span-3 sm:col-span-2 flex items-center justify-end gap-2">
        <span class="line-total text-sm font-semibold text-gray-800">0.00</span>
        <button type="button" onclick="removeItem(this)"
            class="w-6 h-6 flex items-center justify-center rounded-lg text-gray-400 hover:text-red-500 hover:bg-red-50 transition flex-shrink-0">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>
</div>
</template>

<script>
let itemIndex = 0;
const treatments = @json($treatments->keyBy('id'));

function addItem(treatmentId = null, description = '', qty = 1, price = 0) {
    const template = document.getElementById('item_template').innerHTML
        .replaceAll('__IDX__', itemIndex++);

    const wrapper = document.createElement('div');
    wrapper.innerHTML = template;
    const row = wrapper.firstElementChild;

    const treatSel  = row.querySelector('.treatment-select');
    const descInput = row.querySelector('.desc-input');
    const qtyInput  = row.querySelector('.qty-input');
    const priceInput = row.querySelector('.price-input');

    if (treatmentId) treatSel.value = treatmentId;
    if (description) descInput.value = description;
    qtyInput.value   = qty;
    priceInput.value = price;

    // Auto-fill description when treatment selected
    treatSel.addEventListener('change', function () {
        const t = treatments[this.value];
        if (t && !descInput.value) descInput.value = t.name;
        recalculate();
    });

    [qtyInput, priceInput].forEach(el => el.addEventListener('input', recalculate));

    document.getElementById('items_container').appendChild(row);
    updateLineTotal(row);
    recalculate();
}

function removeItem(btn) {
    btn.closest('.item-row').remove();
    recalculate();
}

function updateLineTotal(row) {
    const qty   = parseFloat(row.querySelector('.qty-input').value) || 0;
    const price = parseFloat(row.querySelector('.price-input').value) || 0;
    const total = qty * price;
    row.querySelector('.line-total').textContent = fmt(total);
    return total;
}

function recalculate() {
    let subtotal = 0;
    document.querySelectorAll('.item-row').forEach(row => {
        subtotal += updateLineTotal(row);
    });

    const discAmt  = parseFloat(document.getElementById('discount').value) || 0;
    const discPct  = parseFloat(document.getElementById('discount_percent').value) || 0;
    const discount = discPct > 0 ? subtotal * discPct / 100 : discAmt;
    const total    = Math.max(0, subtotal - discount);
    const paid     = Math.min(parseFloat(document.getElementById('paid_amount').value) || 0, total);
    const balance  = total - paid;

    document.getElementById('display_subtotal').textContent = 'Rs. ' + fmt(subtotal);
    document.getElementById('display_discount').textContent = '- Rs. ' + fmt(discount);
    document.getElementById('display_total').textContent    = 'Rs. ' + fmt(total);
    document.getElementById('display_paid').textContent     = 'Rs. ' + fmt(paid);

    const balEl = document.getElementById('display_balance');
    balEl.textContent = 'Rs. ' + fmt(balance);
    balEl.className   = 'text-lg font-bold ' + (balance > 0 ? 'text-red-600' : 'text-green-600');

    const badge = document.getElementById('status_badge');
    let statusHtml = '';
    if (total === 0) {
        statusHtml = '<span class="text-xs px-3 py-1 rounded-full font-semibold bg-gray-100 text-gray-500">Draft</span>';
    } else if (paid >= total) {
        statusHtml = '<span class="text-xs px-3 py-1 rounded-full font-semibold bg-green-100 text-green-700">Paid</span>';
    } else if (paid > 0) {
        statusHtml = '<span class="text-xs px-3 py-1 rounded-full font-semibold bg-yellow-100 text-yellow-700">Partial</span>';
    } else {
        statusHtml = '<span class="text-xs px-3 py-1 rounded-full font-semibold bg-gray-100 text-gray-500">Draft</span>';
    }
    badge.innerHTML = statusHtml;
}

function fmt(n) { return parseFloat(n).toFixed(2); }

// Load patient appointments via AJAX
document.getElementById('patient_id').addEventListener('change', function () {
    const apptSel = document.getElementById('appointment_id');
    apptSel.innerHTML = '<option value="">— No linked appointment —</option>';
    if (!this.value) return;
    fetch(`/invoices/patient-appointments?patient_id=${this.value}`)
        .then(r => r.json())
        .then(data => {
            data.forEach(a => {
                const opt = document.createElement('option');
                opt.value = a.id;
                opt.textContent = `#${a.booking_number} — ${a.appointment_date} ${a.formatted_time}`;
                apptSel.appendChild(opt);
            });
        });
});

// Start with one empty item
window.addEventListener('DOMContentLoaded', () => {
    @if($selectedAppointment && $selectedAppointment->treatment)
        addItem({{ $selectedAppointment->treatment_id }}, '{{ $selectedAppointment->treatment->name }}');
    @else
        addItem();
    @endif
});
</script>
@endsection
