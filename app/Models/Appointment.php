<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_number',
        'patient_id',
        'doctor_id',
        'treatment_id',
        'appointment_date',
        'appointment_time',
        'status',
        'notes',
    ];

    protected $casts = [
        'appointment_date' => 'date',
    ];

    // ── Relationships ─────────────────────────────────────────────────────────

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function treatment()
    {
        return $this->belongsTo(Treatment::class);
    }

    // ── Helpers ───────────────────────────────────────────────────────────────

    /**
     * Generate booking number: plain daily counter (1, 2, 3 …)
     * Resets to 1 every new day.
     */
    public static function generateBookingNumber(): string
    {
        $today = Carbon::today()->toDateString(); // e.g. 2026-05-27

        $last = static::whereDate('appointment_date', $today)
            ->orderBy('id', 'desc')
            ->first();

        $next = $last
            ? (intval($last->booking_number) + 1)
            : 1;

        return (string) $next;
    }

    /**
     * Formatted time for display (e.g. 09:30 AM)
     */
    public function getFormattedTimeAttribute(): string
    {
        return Carbon::createFromFormat('H:i:s', $this->appointment_time)->format('h:i A');
    }

    /**
     * Status badge colour classes
     */
    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'scheduled'  => 'bg-blue-100 text-blue-700',
            'completed'  => 'bg-green-100 text-green-700',
            'cancelled'  => 'bg-red-100 text-red-700',
            'no_show'    => 'bg-yellow-100 text-yellow-700',
            default      => 'bg-gray-100 text-gray-600',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'scheduled' => 'Scheduled',
            'completed' => 'Completed',
            'cancelled' => 'Cancelled',
            'no_show'   => 'No Show',
            default     => ucfirst($this->status),
        };
    }
}
