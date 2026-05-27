<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatientTreatmentPhoto extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'treatment_id',
        'photo_path',
        'taken_on',
        'notes',
    ];

    protected $casts = [
        'taken_on' => 'date',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function treatment()
    {
        return $this->belongsTo(Treatment::class);
    }
}
