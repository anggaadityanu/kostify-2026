<?php

namespace App\Livewire\Tenant;

use App\Models\Room;
use App\Models\Property;
use Livewire\Component;
use Livewire\WithPagination;

class RoomList extends Component
{
    use WithPagination;

    // Filter properties
    public string $search      = '';
    public string $type        = '';
    public string $city        = '';
    public string $priceMin    = '';
    public string $priceMax    = '';
    public string $sortBy      = 'price_monthly';
    public string $sortDir     = 'asc';

    /**
     * Reset pagination ketika filter berubah
     */
    public function updatingSearch(): void { $this->resetPage(); }
    public function updatingType(): void { $this->resetPage(); }
    public function updatingCity(): void { $this->resetPage(); }

    /**
     * Query kamar tersedia dengan filter
     * Logika: ambil kamar status available →
     * filter berdasarkan input user → paginate
     */
    public function render()
    {
        $rooms = Room::with('property')
            ->where('status', 'available')
            ->when($this->search, fn ($q) =>
                $q->whereHas('property', fn ($q) =>
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('city', 'like', '%' . $this->search . '%')
                      ->orWhere('address', 'like', '%' . $this->search . '%')
                )
            )
            ->when($this->type, fn ($q) =>
                $q->whereHas('property', fn ($q) =>
                    $q->where('type', $this->type)
                )
            )
            ->when($this->city, fn ($q) =>
                $q->whereHas('property', fn ($q) =>
                    $q->where('city', $this->city)
                )
            )
            ->when($this->priceMin, fn ($q) =>
                $q->where('price_monthly', '>=', $this->priceMin)
            )
            ->when($this->priceMax, fn ($q) =>
                $q->where('price_monthly', '<=', $this->priceMax)
            )
            ->orderBy($this->sortBy, $this->sortDir)
            ->paginate(9);

        // Ambil daftar kota untuk filter
        $cities = Property::where('status', 'active')
            ->whereNotNull('city')
            ->distinct()
            ->pluck('city');

        return view('livewire.tenant.room-list', [
            'rooms'  => $rooms,
            'cities' => $cities,
        ])->layout('layouts.makaan');
    }
}