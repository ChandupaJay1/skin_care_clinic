<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DoctorController extends Controller
{
    /**
     * Save a base64 encoded image (from webcam) to storage
     */
    private function saveBase64Photo(string $base64): string
    {
        $imageData = preg_replace('/^data:image\/\w+;base64,/', '', $base64);
        $decoded   = base64_decode($imageData);

        $filename = 'doctors/photos/webcam_' . uniqid() . '.jpg';
        Storage::disk('public')->put($filename, $decoded);

        return $filename;
    }

    /**
     * Display list of all doctors
     */
    public function index(Request $request)
    {
        $query = Doctor::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                  ->orWhere('doctor_id', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('specialization', 'like', "%{$search}%")
                  ->orWhere('registration_number', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $doctors = $query->latest()->paginate(10);

        return view('doctors.index', compact('doctors'));
    }

    /**
     * Show registration form
     */
    public function create()
    {
        return view('doctors.create');
    }

    /**
     * Store new doctor
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'full_name'           => 'required|string|max:255',
            'date_of_birth'       => 'required|date|before:today',
            'gender'              => 'required|in:male,female,other',
            'phone'               => 'required|string|max:15',
            'email'               => 'nullable|email|unique:doctors,email',
            'nic'                 => 'required|string|unique:doctors,nic',
            'specialization'      => 'required|string|max:255',
            'qualification'       => 'required|string|max:255',
            'registration_number' => 'required|string|unique:doctors,registration_number',
            'experience_years'    => 'required|integer|min:0|max:60',
            'bio'                 => 'nullable|string',
            'profile_photo'       => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'status'              => 'required|in:active,inactive,on_leave',
        ]);

        $validated['doctor_id'] = Doctor::generateDoctorId();

        if ($request->hasFile('profile_photo')) {
            $validated['profile_photo'] = $request->file('profile_photo')
                ->store('doctors/photos', 'public');
        } elseif ($request->filled('webcam_photo')) {
            $validated['profile_photo'] = $this->saveBase64Photo($request->webcam_photo);
        }

        $doctor = Doctor::create($validated);

        return redirect()->route('doctors.show', $doctor)
            ->with('success', "Dr. {$doctor->full_name} registered successfully! ID: {$doctor->doctor_id}");
    }

    /**
     * Show doctor profile
     */
    public function show(Doctor $doctor)
    {
        return view('doctors.show', compact('doctor'));
    }

    /**
     * Show edit form
     */
    public function edit(Doctor $doctor)
    {
        return view('doctors.edit', compact('doctor'));
    }

    /**
     * Update doctor
     */
    public function update(Request $request, Doctor $doctor)
    {
        $validated = $request->validate([
            'full_name'           => 'required|string|max:255',
            'date_of_birth'       => 'required|date|before:today',
            'gender'              => 'required|in:male,female,other',
            'phone'               => 'required|string|max:15',
            'email'               => 'nullable|email|unique:doctors,email,' . $doctor->id,
            'nic'                 => 'required|string|unique:doctors,nic,' . $doctor->id,
            'specialization'      => 'required|string|max:255',
            'qualification'       => 'required|string|max:255',
            'registration_number' => 'required|string|unique:doctors,registration_number,' . $doctor->id,
            'experience_years'    => 'required|integer|min:0|max:60',
            'bio'                 => 'nullable|string',
            'profile_photo'       => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'status'              => 'required|in:active,inactive,on_leave',
        ]);

        if ($request->hasFile('profile_photo')) {
            if ($doctor->profile_photo) {
                Storage::disk('public')->delete($doctor->profile_photo);
            }
            $validated['profile_photo'] = $request->file('profile_photo')
                ->store('doctors/photos', 'public');
        } elseif ($request->filled('webcam_photo')) {
            if ($doctor->profile_photo) {
                Storage::disk('public')->delete($doctor->profile_photo);
            }
            $validated['profile_photo'] = $this->saveBase64Photo($request->webcam_photo);
        }

        $doctor->update($validated);

        return redirect()->route('doctors.show', $doctor)
            ->with('success', 'Doctor details updated successfully!');
    }

    /**
     * Deactivate doctor
     */
    public function destroy(Doctor $doctor)
    {
        $doctor->update(['status' => 'inactive']);

        return redirect()->route('doctors.index')
            ->with('success', 'Doctor deactivated successfully.');
    }
}
