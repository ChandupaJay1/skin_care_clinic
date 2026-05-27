<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Treatment;
use App\Models\PatientTreatmentPhoto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PatientTreatmentPhotoController extends Controller
{
    /**
     * Show the upload form for a patient's treatment photo
     */
    public function create(Patient $patient)
    {
        $treatments = Treatment::where('is_active', true)->orderBy('name')->get();
        return view('patients.treatment_photos.create', compact('patient', 'treatments'));
    }

    /**
     * Store a new treatment photo
     */
    public function store(Request $request, Patient $patient)
    {
        $validated = $request->validate([
            'treatment_id' => 'required|exists:treatments,id',
            'taken_on'     => 'required|date|before_or_equal:today',
            'notes'        => 'nullable|string|max:500',
            'photo'        => 'required|image|mimes:jpeg,png,jpg|max:5120',
        ]);

        $path = $request->file('photo')->store(
            'patients/' . $patient->id . '/treatment_photos',
            'public'
        );

        PatientTreatmentPhoto::create([
            'patient_id'   => $patient->id,
            'treatment_id' => $validated['treatment_id'],
            'photo_path'   => $path,
            'taken_on'     => $validated['taken_on'],
            'notes'        => $validated['notes'] ?? null,
        ]);

        return redirect()->route('patients.show', $patient)
            ->with('success', 'Treatment photo added successfully.');
    }

    /**
     * Delete a treatment photo
     */
    public function destroy(Patient $patient, PatientTreatmentPhoto $photo)
    {
        // Ensure the photo belongs to this patient
        abort_if($photo->patient_id !== $patient->id, 403);

        Storage::disk('public')->delete($photo->photo_path);
        $photo->delete();

        return back()->with('success', 'Photo deleted.');
    }

    /**
     * Show the compare selection page for a patient
     */
    public function compare(Patient $patient)
    {
        // Group photos by treatment for easy selection
        $photosByTreatment = $patient->treatmentPhotos()
            ->with('treatment')
            ->orderBy('taken_on')
            ->get()
            ->groupBy('treatment_id');

        return view('patients.treatment_photos.compare', compact('patient', 'photosByTreatment'));
    }
}
