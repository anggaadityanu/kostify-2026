<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'address',
        'phone',
        'whatsapp',
        'email',
        'about_title',
        'about_description',
        'about_image',
        'about_features',
        'home_hero_title',
        'home_hero_subtitle',
        'home_carousel_images',
        'home_cta_title',
        'home_cta_description',
        'home_cta_image',
    ];

    protected $casts = [
        'about_features'       => 'array',
        'home_carousel_images' => 'array',
    ];

    /**
     * URL gambar halaman About. Kalau belum diupload, pakai gambar default.
     */
    public function aboutImageUrl(): string
    {
        if ($this->about_image) {
            return \Illuminate\Support\Facades\Storage::disk('public')->url($this->about_image);
        }

        return asset('makaan/img/carousel-1.jpg');
    }

    /**
     * Daftar URL gambar carousel hero di halaman Home.
     * Kalau belum diupload sama sekali, pakai 2 gambar default bawaan template.
     */
    public function heroCarouselUrls(): array
    {
        if (!empty($this->home_carousel_images)) {
            return array_map(
                fn ($path) => \Illuminate\Support\Facades\Storage::disk('public')->url($path),
                $this->home_carousel_images
            );
        }

        return [
            asset('makaan/img/carousel-1.jpg'),
            asset('makaan/img/carousel-2.jpg'),
        ];
    }

    /**
     * URL gambar CTA di halaman Home. Kalau belum diupload, pakai gambar default.
     */
    public function ctaImageUrl(): string
    {
        if ($this->home_cta_image) {
            return \Illuminate\Support\Facades\Storage::disk('public')->url($this->home_cta_image);
        }

        return asset('makaan/img/call-to-action.jpg');
    }

    /**
     * Ambil baris settings (cuma ada 1 baris di tabel ini).
     * Kalau belum ada (kasus aneh, misal lupa migrate seed),
     * otomatis dibuatin baris kosong biar gak error null.
     */
    public static function current(): self
    {
        return self::first() ?? self::create([]);
    }
}