<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Property extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'name',
        'type',
        'description',
        'address',
        'province',
        'city',
        'district',
        'latitude',
        'longitude',
        'google_maps_url',
        'facilities',
        'status',
    ];

    protected $casts = [
        'facilities' => 'array',
        'latitude'   => 'decimal:8',
        'longitude'  => 'decimal:8',
    ];

    /**
     * Properti dimiliki oleh seorang user (owner)
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Properti memiliki banyak kamar
     */
    public function rooms(): HasMany
    {
        return $this->hasMany(Room::class);
    }

    /**
     * Hitung total kamar tersedia
     */
    public function availableRoomsCount(): int
    {
        return $this->rooms()->where('status', 'available')->count();
    }

    protected static function boot()
    {
        parent::boot();

        /**
         * Ketika properti disimpan & belum ada koordinat
         * → auto geocoding dari alamat
         */
        static::saving(function ($property) {
            if ($property->address && !$property->latitude) {
                try {
                    $mapService  = app(\App\Services\MapService::class);
                    $coordinates = $mapService->getCoordinates(
                        $property->address . ', ' . $property->city . ', Indonesia'
                    );

                    if ($coordinates) {
                        $property->latitude  = $coordinates['latitude'];
                        $property->longitude = $coordinates['longitude'];
                    }
                } catch (\Exception $e) {
                    // Geocoding gagal, lanjutkan tanpa koordinat
                }
            }
        });
    }
}