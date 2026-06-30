<?php

namespace App\Livewire;

use App\Models\Complaint;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ComplaintChat extends Component
{
    public Complaint $complaint;
    public string $newMessage = '';

    public function mount(Complaint $complaint): void
    {
        $this->complaint = $complaint;
    }

    /**
     * Kirim pesan baru ke komplain ini.
     * Bisa dipanggil dari sisi tenant maupun admin/owner,
     * otorisasi akses ke complaint-nya sendiri sudah dicek
     * di level halaman pembungkus (ComplaintDetail / Filament Resource).
     */
    public function sendMessage(): void
    {
        $this->validate([
            'newMessage' => 'required|string|max:2000',
        ], [
            'newMessage.required' => 'Pesan tidak boleh kosong.',
        ]);

        $this->complaint->messages()->create([
            'user_id' => Auth::id(),
            'message' => $this->newMessage,
        ]);

        $this->newMessage = '';
    }

    public function render()
    {
        return view('livewire.complaint-chat', [
            'messages' => $this->complaint->messages()->with('user')->get(),
        ]);
    }
}