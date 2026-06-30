<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Room extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'property_id',
        'room_number',
        'type',
        'description',
        'price_monthly',
        'price_yearly',
        'capacity',
        'size',
        'facilities',
        'photos',
        'status',
    ];

    protected $casts = [
        'facilities'    => 'array',
        'photos'        => 'array',
        'price_monthly' => 'decimal:2',
        'price_yearly'  => 'decimal:2',
        'size'          => 'decimal:2',
    ];

    /**
     * URL foto utama kamar. Kalau kamar belum punya foto,
     * fallback ke foto properti, kalau itu pun belum ada,
     * fallback ke placeholder bawaan template.
     */
    public function coverPhotoUrl(): string
    {
        if (!empty($this->photos[0])) {
            return \Illuminate\Support\Facades\Storage::disk('public')->url($this->photos[0]);
        }

        if (!empty($this->property?->photos[0])) {
            return \Illuminate\Support\Facades\Storage::disk('public')->url($this->property->photos[0]);
        }

        return asset('makaan/img/property-1.jpg');
    }

    /**
     * Kamar milik satu properti
     */
    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    /**
     * Kamar bisa punya banyak booking
     */
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Cek apakah kamar tersedia
     */
    public function isAvailable(): bool
    {
        return $this->status === 'available';
    }
}