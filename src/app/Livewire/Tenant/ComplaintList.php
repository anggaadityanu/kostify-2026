<?php

namespace App\Livewire\Tenant;

use App\Models\Complaint;
use App\Models\Room;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ComplaintList extends Component
{
    public bool   $showForm    = false;
    public string $title       = '';
    public string $description = '';
    public string $category    = '';
    public string $priority    = 'medium';
    public ?int   $roomId      = null;

    /**
     * Submit komplain baru
     * Logika: validasi → simpan → refresh list
     */
    public function submit(): void
    {
        $this->validate([
            'title'       => 'required|min:5',
            'description' => 'required|min:10',
            'category'    => 'required',
            'roomId'      => 'required|exists:rooms,id',
        ], [
            'title.required'       => 'Judul wajib diisi.',
            'description.required' => 'Deskripsi wajib diisi.',
            'category.required'    => 'Kategori wajib dipilih.',
            'roomId.required'      => 'Kamar wajib dipilih.',
        ]);

        $tenant = Auth::user()->tenant;

        Complaint::create([
            'tenant_id'   => $tenant->id,
            'room_id'     => $this->roomId,
            'title'       => $this->title,
            'description' => $this->description,
            'category'    => $this->category,
            'priority'    => $this->priority,
            'status'      => 'open',
        ]);

        // Reset form
        $this->reset(['title', 'description', 'category', 'priority', 'roomId', 'showForm']);
        session()->flash('success', 'Komplain berhasil dikirim!');
    }

    public function render()
    {
        $tenant = Auth::user()->tenant;

        $complaints = Complaint::where('tenant_id', $tenant?->id)
            ->with('room.property')
            ->latest()
            ->get();

        // Kamar tenant untuk pilihan form
        $rooms = Room::whereHas('bookings', fn ($q) =>
            $q->where('tenant_id', $tenant?->id)
              ->where('status', 'active')
        )->get();

        return view('livewire.tenant.complaint-list', [
            'complaints' => $complaints,
            'rooms'      => $rooms,
        ])->layout('layouts.tenant-portal');
    }
}