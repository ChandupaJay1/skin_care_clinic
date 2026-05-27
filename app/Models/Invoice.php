<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_number',
        'patient_id',
        'appointment_id',
        'subtotal',
        'discount',
        'discount_percent',
        'total',
        'paid_amount',
        'balance',
        'payment_method',
        'status',
        'notes',
    ];

    protected $casts = [
        'subtotal'         => 'decimal:2',
        'discount'         => 'decimal:2',
        'discount_percent' => 'decimal:2',
        'total'            => 'decimal:2',
        'paid_amount'      => 'decimal:2',
        'balance'          => 'decimal:2',
    ];

    // ── Relationships ─────────────────────────────────────────────────────────

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }

    // ── Helpers ───────────────────────────────────────────────────────────────

    /**
     * Generate invoice number: INV-YYYYMMDD-NNNN, resets daily
     */
    public static function generateInvoiceNumber(): string
    {
        $today  = Carbon::today()->format('Ymd');
        $prefix = "INV-{$today}-";

        $last = static::where('invoice_number', 'like', "{$prefix}%")
            ->orderBy('id', 'desc')
            ->first();

        $next = $last
            ? (intval(substr($last->invoice_number, -4)) + 1)
            : 1;

        return $prefix . str_pad($next, 4, '0', STR_PAD_LEFT);
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'paid'      => 'bg-green-100 text-green-700',
            'partial'   => 'bg-yellow-100 text-yellow-700',
            'cancelled' => 'bg-red-100 text-red-700',
            default     => 'bg-gray-100 text-gray-600',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'paid'      => 'Paid',
            'partial'   => 'Partial',
            'cancelled' => 'Cancelled',
            default     => 'Draft',
        };
    }

    public function getPaymentMethodLabelAttribute(): string
    {
        return match ($this->payment_method) {
            'cash'          => 'Cash',
            'card'          => 'Card',
            'bank_transfer' => 'Bank Transfer',
            default         => 'Other',
        };
    }
}
