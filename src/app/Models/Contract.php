<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Contract extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'contract_number',
        'booking_id',
        'start_date',
        'end_date',
        'monthly_rent',
        'deposit_amount',
        'status',
        'contract_file',
        'terms',
    ];

    protected $casts = [
        'start_date'     => 'date',
        'end_date'       => 'date',
        'monthly_rent'   => 'decimal:2',
        'deposit_amount' => 'decimal:2',
    ];

    /**
     * Generate contract number otomatis
     * Format: KTR-2024-001
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($contract) {
            $contract->contract_number = self::generateContractNumber();
        });
    }

    public static function generateContractNumber(): string
    {
        $year  = date('Y');
        $count = self::whereYear('created_at', $year)->count() + 1;
        return 'KTR-' . $year . '-' . str_pad($count, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Kontrak milik satu booking
     */
    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    /**
     * Cek apakah kontrak hampir habis (30 hari lagi)
     */
    public function isExpiringSoon(): bool
    {
        return $this->status === 'active' &&
               $this->end_date->diffInDays(now()) <= 30 &&
               $this->end_date->isFuture();
    }

    /**
     * Hitung sisa hari kontrak
     */
    public function daysRemaining(): int
    {
        if ($this->end_date->isPast()) return 0;
        return now()->diffInDays($this->end_date);
    }
}