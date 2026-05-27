<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    use HasFactory;

    protected $fillable = [
        'doctor_id',
        'full_name',
        'date_of_birth',
        'gender',
        'phone',
        'email',
        'nic',
        'specialization',
        'qualification',
        'registration_number',
        'experience_years',
        'bio',
        'profile_photo',
        'status',
    ];

    protected $casts = [
        'date_of_birth'    => 'date',
        'experience_years' => 'integer',
    ];

    /**
     * Get doctor's age
     */
    public function getAgeAttribute(): int
    {
        return $this->date_of_birth->age;
    }

    /**
     * Generate next doctor ID (DOC-0001, DOC-0002, …)
     */
    public static function generateDoctorId(): string
    {
        $last       = static::orderBy('id', 'desc')->first();
        $nextNumber = $last ? (intval(substr($last->doctor_id, 4)) + 1) : 1;

        return 'DOC-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    }
}
