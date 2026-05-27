<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'barcode_value',
        'barcode_svg',
        'full_name',
        'date_of_birth',
        'gender',
        'phone',
        'email',
        'address',
        'nic',
        'emergency_contact_name',
        'emergency_contact_phone',
        'skin_type',
        'known_allergies',
        'medical_history',
        'profile_photo',
        'is_active',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'is_active' => 'boolean',
    ];

    /**
     * Treatment progress photos
     */
    public function treatmentPhotos()
    {
        return $this->hasMany(PatientTreatmentPhoto::class)->orderBy('taken_on');
    }

    /**
     * Get patient's age
     */
    public function getAgeAttribute(): int
    {
        return $this->date_of_birth->age;
    }

    /**
     * Generate next patient ID
     */
    public static function generatePatientId(): string
    {
        $last = static::orderBy('id', 'desc')->first();
        $nextNumber = $last ? (intval(substr($last->patient_id, 4)) + 1) : 1;
        return 'SCC-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    }
}
