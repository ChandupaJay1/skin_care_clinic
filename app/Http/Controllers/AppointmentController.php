<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Treatment;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    /**
     * List appointments — supports date filter + history view
     */
    public function index(Request $request)
    {
        $date = $request->filled('date')
            ? Carbon::parse($request->date)
            : Carbon::today();

        $query = Appointment::with(['patient', 'doctor', 'treatment'])
            ->whereDate('appointment_date', $date);

        if ($request->filled('doctor_id')) {
            $query->where('doctor_id', $request->doctor_id);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $appointments = $query->orderBy('appointment_time')->get();

        // Doctor-wise grouping for the selected date
        $byDoctor = $appointments->groupBy('doctor_id');

        // All active doctors for filter dropdown
        $doctors = Doctor::where('status', 'active')->orderBy('full_name')->get();

        // Stats for selected date
        $stats = [
            'total'     => $appointments->count(),
            'scheduled' => $appointments->where('status', 'scheduled')->count(),
            'completed' => $appointments->where('status', 'completed')->count(),
            'cancelled' => $appointments->where('status', 'cancelled')->count(),
            'no_show'   => $appointments->where('status', 'no_show')->count(),
        ];

        return view('appointments.index', compact('appointments', 'byDoctor', 'doctors', 'date', 'stats'));
    }

    /**
     * History — paginated list across all dates
     */
    public function history(Request $request)
    {
        $query = Appointment::with(['patient', 'doctor', 'treatment']);

        if ($request->filled('from')) {
            $query->whereDate('appointment_date', '>=', $request->from);
        }
        if ($request->filled('to')) {
            $query->whereDate('appointment_date', '<=', $request->to);
        }
        if ($request->filled('doctor_id')) {
            $query->where('doctor_id', $request->doctor_id);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('patient', fn($q) =>
                $q->where('full_name', 'like', "%{$search}%")
                  ->orWhere('patient_id', 'like', "%{$search}%")
            )->orWhere('booking_number', 'like', "%{$search}%");
        }

        $appointments = $query->orderBy('appointment_date', 'desc')
            ->orderBy('appointment_time', 'desc')
            ->paginate(20)
            ->withQueryString();

        $doctors = Doctor::where('status', 'active')->orderBy('full_name')->get();

        return view('appointments.history', compact('appointments', 'doctors'));
    }

    /**
     * Show booking form
     */
    public function create(Request $request)
    {
        $patients  = Patient::where('is_active', true)->orderBy('full_name')->get();
        $doctors   = Doctor::where('status', 'active')->orderBy('full_name')->get();
        $treatments = Treatment::where('is_active', true)->orderBy('name')->get();

        // Pre-select patient if coming from patient profile
        $selectedPatient = $request->filled('patient_id')
            ? Patient::find($request->patient_id)
            : null;

        return view('appointments.create', compact('patients', 'doctors', 'treatments', 'selectedPatient'));
    }

    /**
     * Store new appointment
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id'       => 'required|exists:patients,id',
            'doctor_id'        => 'required|exists:doctors,id',
            'treatment_id'     => 'nullable|exists:treatments,id',
            'appointment_date' => 'required|date|after_or_equal:today',
            'appointment_time' => 'required',
            'notes'            => 'nullable|string|max:1000',
        ]);

        // Check slot availability
        $conflict = Appointment::where('doctor_id', $validated['doctor_id'])
            ->whereDate('appointment_date', $validated['appointment_date'])
            ->where('appointment_time', $validated['appointment_time'])
            ->whereNotIn('status', ['cancelled'])
            ->exists();

        if ($conflict) {
            return back()
                ->withInput()
                ->withErrors(['appointment_time' => 'This time slot is already booked for the selected doctor.']);
        }

        $validated['booking_number'] = Appointment::generateBookingNumber();
        $validated['status']         = 'scheduled';

        $appointment = Appointment::create($validated);

        return redirect()->route('appointments.show', $appointment)
            ->with('success', "Appointment booked! Booking No: {$appointment->booking_number}");
    }

    /**
     * Show appointment detail
     */
    public function show(Appointment $appointment)
    {
        $appointment->load(['patient', 'doctor', 'treatment']);
        return view('appointments.show', compact('appointment'));
    }

    /**
     * Print receipt
     */
    public function receipt(Appointment $appointment)
    {
        $appointment->load(['patient', 'doctor', 'treatment']);
        return view('appointments.receipt', compact('appointment'));
    }

    /**
     * Show edit form
     */
    public function edit(Appointment $appointment)
    {
        $patients   = Patient::where('is_active', true)->orderBy('full_name')->get();
        $doctors    = Doctor::where('status', 'active')->orderBy('full_name')->get();
        $treatments = Treatment::where('is_active', true)->orderBy('name')->get();

        return view('appointments.edit', compact('appointment', 'patients', 'doctors', 'treatments'));
    }

    /**
     * Update appointment
     */
    public function update(Request $request, Appointment $appointment)
    {
        $validated = $request->validate([
            'patient_id'       => 'required|exists:patients,id',
            'doctor_id'        => 'required|exists:doctors,id',
            'treatment_id'     => 'nullable|exists:treatments,id',
            'appointment_date' => 'required|date',
            'appointment_time' => 'required',
            'status'           => 'required|in:scheduled,completed,cancelled,no_show',
            'notes'            => 'nullable|string|max:1000',
        ]);

        // Check slot conflict (exclude self)
        $conflict = Appointment::where('doctor_id', $validated['doctor_id'])
            ->whereDate('appointment_date', $validated['appointment_date'])
            ->where('appointment_time', $validated['appointment_time'])
            ->whereNotIn('status', ['cancelled'])
            ->where('id', '!=', $appointment->id)
            ->exists();

        if ($conflict) {
            return back()
                ->withInput()
                ->withErrors(['appointment_time' => 'This time slot is already booked for the selected doctor.']);
        }

        $appointment->update($validated);

        return redirect()->route('appointments.show', $appointment)
            ->with('success', 'Appointment updated successfully.');
    }

    /**
     * Quick status update (AJAX-friendly)
     */
    public function updateStatus(Request $request, Appointment $appointment)
    {
        $request->validate(['status' => 'required|in:scheduled,completed,cancelled,no_show']);
        $appointment->update(['status' => $request->status]);

        return back()->with('success', "Appointment marked as {$appointment->status_label}.");
    }

    /**
     * Get booked slots for a doctor on a date (JSON — used by booking form)
     */
    public function bookedSlots(Request $request)
    {
        $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            'date'      => 'required|date',
        ]);

        $query = Appointment::where('doctor_id', $request->doctor_id)
            ->whereDate('appointment_date', $request->date)
            ->whereNotIn('status', ['cancelled']);

        // Exclude current appointment when editing
        if ($request->filled('exclude')) {
            $query->where('id', '!=', $request->exclude);
        }

        $slots = $query->pluck('appointment_time')
            ->map(fn($t) => substr($t, 0, 5))
            ->values();

        return response()->json($slots);
    }
}
