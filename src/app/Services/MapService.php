<?php

namespace App\Services;

use App\Models\Property;
use Illuminate\Support\Facades\Http;

class MapService
{
    protected ?string $apiKey;

    public function __construct()
    {
        /**
         * Ambil API key dari config
         * Kalau belum diisi → set kosong, tidak crash
         */
        $this->apiKey = config('services.google_maps.key') ?? '';
    }

    /**
     * Geocoding: konversi alamat → koordinat
     * Logika: kirim alamat ke Google API →
     * dapat lat/lng → simpan ke DB
     */
    public function getCoordinates(string $address): ?array
    {
        // Skip kalau API key belum diisi
        if (empty($this->apiKey)) return null;

        $response = Http::get('https://maps.googleapis.com/maps/api/geocode/json', [
            'address' => $address,
            'key'     => $this->apiKey,
        ]);

        $data = $response->json();

        if ($data['status'] === 'OK') {
            $location = $data['results'][0]['geometry']['location'];
            return [
                'latitude'  => $location['lat'],
                'longitude' => $location['lng'],
            ];
        }

        return null;
    }

    /**
     * Generate embed URL untuk iframe Maps
     */
    public function getEmbedUrl(float $lat, float $lng, string $label = ''): string
    {
        // Kalau key belum ada, return placeholder
        if (empty($this->apiKey)) {
            return '';
        }

        return "https://www.google.com/maps/embed/v1/place?" .
               "key={$this->apiKey}" .
               "&q={$lat},{$lng}" .
               "&zoom=16";
    }

    /**
     * Generate link directions ke properti
     */
    public function getDirectionsUrl(float $lat, float $lng): string
    {
        return "https://www.google.com/maps/dir/?api=1&destination={$lat},{$lng}";
    }
}