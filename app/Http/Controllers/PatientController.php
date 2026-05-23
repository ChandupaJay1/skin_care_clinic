<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Picqer\Barcode\BarcodeGeneratorSVG;

class PatientController extends Controller
{
    /**
     * Save a base64 encoded image (from webcam) to storage
     */
    private function saveBase64Photo(string $base64): string
    {
        // Strip data URI prefix: data:image/jpeg;base64,...
        $imageData = preg_replace('/^data:image\/\w+;base64,/', '', $base64);
        $decoded   = base64_decode($imageData);

        $filename = 'patients/photos/webcam_' . uniqid() . '.jpg';
        Storage::disk('public')->put($filename, $decoded);

        return $filename;
    }

    /**
     * Generate SVG barcode for a given value
     */
    private function generateBarcode(string $value): string
    {
        $generator = new BarcodeGeneratorSVG();
        return $generator->getBarcode($value, $generator::TYPE_CODE_128, 2, 60);
    }

    /**
     * Display list of all patients
     */
    public function index(Request $request)
    {
        $query = Patient::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                  ->orWhere('patient_id', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('nic', 'like', "%{$search}%");
            });
        }

        $patients = $query->latest()->paginate(10);

        return view('patients.index', compact('patients'));
    }

    /**
     * Show registration form
     */
    public function create()
    {
        return view('patients.create');
    }

    /**
     * Store new patient
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'full_name'               => 'required|string|max:255',
            'date_of_birth'           => 'required|date|before:today',
            'gender'                  => 'required|in:male,female,other',
            'phone'                   => 'required|string|max:15',
            'email'                   => 'nullable|email|unique:patients,email',
            'address'                 => 'required|string',
            'nic'                     => 'required|string|unique:patients,nic',
            'emergency_contact_name'  => 'required|string|max:255',
            'emergency_contact_phone' => 'required|string|max:15',
            'skin_type'               => 'required|in:normal,dry,oily,combination,sensitive',
            'known_allergies'         => 'nullable|string',
            'medical_history'         => 'nullable|string',
            'profile_photo'           => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $validated['patient_id'] = Patient::generatePatientId();

        // Generate barcode based on patient_id
        $validated['barcode_value'] = $validated['patient_id'];
        $validated['barcode_svg']   = $this->generateBarcode($validated['patient_id']);

        if ($request->hasFile('profile_photo')) {
            $validated['profile_photo'] = $request->file('profile_photo')
                ->store('patients/photos', 'public');
        } elseif ($request->filled('webcam_photo')) {
            // Webcam base64 image → save as file
            $validated['profile_photo'] = $this->saveBase64Photo($request->webcam_photo);
        }

        $patient = Patient::create($validated);

        return redirect()->route('patients.show', $patient)
            ->with('success', "Patient {$patient->full_name} registered successfully! ID: {$patient->patient_id}");
    }

    /**
     * Show patient details
     */
    public function show(Patient $patient)
    {
        return view('patients.show', compact('patient'));
    }

    /**
     * Show edit form
     */
    public function edit(Patient $patient)
    {
        return view('patients.edit', compact('patient'));
    }

    /**
     * Update patient
     */
    public function update(Request $request, Patient $patient)
    {
        $validated = $request->validate([
            'full_name'               => 'required|string|max:255',
            'date_of_birth'           => 'required|date|before:today',
            'gender'                  => 'required|in:male,female,other',
            'phone'                   => 'required|string|max:15',
            'email'                   => 'nullable|email|unique:patients,email,' . $patient->id,
            'address'                 => 'required|string',
            'nic'                     => 'required|string|unique:patients,nic,' . $patient->id,
            'emergency_contact_name'  => 'required|string|max:255',
            'emergency_contact_phone' => 'required|string|max:15',
            'skin_type'               => 'required|in:normal,dry,oily,combination,sensitive',
            'known_allergies'         => 'nullable|string',
            'medical_history'         => 'nullable|string',
            'profile_photo'           => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('profile_photo')) {
            if ($patient->profile_photo) {
                Storage::disk('public')->delete($patient->profile_photo);
            }
            $validated['profile_photo'] = $request->file('profile_photo')
                ->store('patients/photos', 'public');
        } elseif ($request->filled('webcam_photo')) {
            if ($patient->profile_photo) {
                Storage::disk('public')->delete($patient->profile_photo);
            }
            $validated['profile_photo'] = $this->saveBase64Photo($request->webcam_photo);
        }

        $patient->update($validated);

        return redirect()->route('patients.show', $patient)
            ->with('success', 'Patient details updated successfully!');
    }

    /**
     * Regenerate barcode for a patient
     */
    public function regenerateBarcode(Patient $patient)
    {
        $patient->update([
            'barcode_value' => $patient->patient_id,
            'barcode_svg'   => $this->generateBarcode($patient->patient_id),
        ]);

        return back()->with('success', 'Barcode regenerated successfully!');
    }

    /**
     * Soft delete (deactivate) patient
     */
    public function destroy(Patient $patient)
    {
        $patient->update(['is_active' => false]);

        return redirect()->route('patients.index')
            ->with('success', 'Patient deactivated successfully.');
    }
}
