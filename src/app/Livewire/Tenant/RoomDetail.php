<?php

namespace App\Livewire\Tenant;

use App\Models\Room;
use App\Services\MapService;
use Livewire\Component;

class RoomDetail extends Component
{
    public int $id;
    public ?Room $room = null;

    public function mount(int $id): void
    {
        /**
         * Load detail kamar berdasarkan ID
         * Logika: cari kamar → load relasi properti
         * → cek status available
         */
        $this->room = Room::with('property')
            ->where('status', 'available')
            ->findOrFail($id);
    }

    public function render()
    {
        $embedUrl      = null;
        $directionsUrl = null;

        if ($this->room->property->latitude) {
            $mapService    = app(MapService::class);
            $embedUrl      = $mapService->getEmbedUrl(
                $this->room->property->latitude,
                $this->room->property->longitude
            );
            $directionsUrl = $mapService->getDirectionsUrl(
                $this->room->property->latitude,
                $this->room->property->longitude
            );
        }

        return view('livewire.tenant.room-detail', [
            'room'          => $this->room,
            'embedUrl'      => $embedUrl,
            'directionsUrl' => $directionsUrl,
        ])->layout('layouts.makaan');
    }
}