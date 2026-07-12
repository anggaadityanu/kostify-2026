<?php

namespace App\Livewire\Tenant;

use App\Models\Complaint;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ComplaintDetail extends Component
{
    public Complaint $complaint;

    public function mount(Complaint $complaint): void
    {
        $tenant = Auth::user()->tenant;

        // Pastikan komplain ini milik tenant yang login
        abort_unless($complaint->tenant_id === $tenant?->id, 403);

        $this->complaint = $complaint->load('room.property');
    }

    public function render()
    {
        return view('livewire.tenant.complaint-detail')
            ->layout('layouts.tenant-portal');
    }
}