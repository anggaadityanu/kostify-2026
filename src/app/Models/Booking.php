<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Booking extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'booking_code',
        'room_id',
        'tenant_id',
        'check_in_date',
        'check_out_date',
        'duration_months',
        'total_price',
        'status',
        'notes',
    ];

    protected $casts = [
        'check_in_date'  => 'date',
        'check_out_date' => 'date',
        'total_price'    => 'decimal:2',
    ];

    /**
     * Generate booking code otomatis
     * Format: BK-2024-001
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($booking) {
            $booking->booking_code = self::generateCode();
        });

        /**
         * Ketika booking di-approve → ubah status kamar jadi occupied
         * Ketika booking di-cancel → ubah status kamar jadi available
         */
        static::updated(function ($booking) {
            if ($booking->isDirty('status')) {
                if ($booking->status === 'active') {
                    $booking->room->update(['status' => 'occupied']);
                } elseif (in_array($booking->status, ['cancelled', 'completed'])) {
                    $booking->room->update(['status' => 'available']);
                }
            }
        });
    }

    /**
     * Generate kode booking unik
     */
    public static function generateCode(): string
    {
        $year  = date('Y');
        $count = self::whereYear('created_at', $year)->count() + 1;
        return 'BK-' . $year . '-' . str_pad($count, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Booking milik satu kamar
     */
    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    /**
     * Booking milik satu tenant
     */
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * Booking punya satu kontrak
     */
    public function contract(): HasOne
    {
        return $this->hasOne(Contract::class);
    }

    /**
     * Booking punya banyak pembayaran
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }
}