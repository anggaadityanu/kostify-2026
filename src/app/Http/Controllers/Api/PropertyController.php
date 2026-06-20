<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Services\MapService;
use Illuminate\Http\JsonResponse;

class PropertyController extends Controller
{
    public function __construct(
        protected MapService $mapService
    ) {}

    /**
     * Ambil semua properti aktif + koordinat untuk maps
     * Digunakan oleh frontend untuk tampilkan marker di peta
     */
    public function index(): JsonResponse
    {
        $properties = Property::where('status', 'active')
            ->with(['rooms' => fn ($q) => $q->where('status', 'available')])
            ->get()
            ->map(fn ($property) => [
                'id'              => $property->id,
                'name'            => $property->name,
                'type'            => $property->type,
                'address'         => $property->address,
                'city'            => $property->city,
                'latitude'        => $property->latitude,
                'longitude'       => $property->longitude,
                'available_rooms' => $property->rooms->count(),
                'facilities'      => $property->facilities,
                'directions_url'  => $property->latitude
                    ? $this->mapService->getDirectionsUrl(
                        $property->latitude,
                        $property->longitude
                      )
                    : null,
            ]);

        return response()->json([
            'data'    => $properties,
            'total'   => $properties->count(),
        ]);
    }

    /**
     * Detail satu properti + info maps
     */
    public function show(int $id): JsonResponse
    {
        $property = Property::where('status', 'active')
            ->with(['rooms', 'owner'])
            ->findOrFail($id);

        $data = $property->toArray();

        if ($property->latitude) {
            $data['embed_url']      = $this->mapService->getEmbedUrl(
                $property->latitude,
                $property->longitude,
                $property->name
            );
            $data['directions_url'] = $this->mapService->getDirectionsUrl(
                $property->latitude,
                $property->longitude
            );
        }

        return response()->json(['data' => $data]);
    }
}