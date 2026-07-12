<?php

namespace App\Livewire\Tenant;

use App\Models\Booking;
use App\Models\User;
use App\Notifications\BookingRenewedNotification;
use App\Services\BillingService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class RenewalForm extends Component
{
    public Booking $booking;
    public int     $extraMonths = 1;
    public float   $totalPrice  = 0;

    public function mount(Booking $booking): void
    {
        $this->booking = $booking->loadMissing('room.property', 'tenant.user');

        // Pastikan booking ini milik tenant yang login & sedang aktif
        if ($this->booking->tenant->user_id !== Auth::id() || $this->booking->status !== 'active') {
            abort(403, 'Booking ini tidak bisa diperpanjang.');
        }

        $this->calculateTotal();
    }

    public function updatedExtraMonths(): void
    {
        $this->calculateTotal();
    }

    protected function calculateTotal(): void
    {
        $this->totalPrice = $this->booking->room->price_monthly * $this->extraMonths;
    }

    public function submit(BillingService $billing): void
    {
        $this->validate([
            'extraMonths' => 'required|integer|min:1|max:24',
        ], [
            'extraMonths.required' => 'Durasi perpanjangan wajib diisi.',
            'extraMonths.min'      => 'Minimal perpanjangan 1 bulan.',
            'extraMonths.max'      => 'Maksimal perpanjangan 24 bulan sekaligus.',
        ]);

        $newDuration = $this->booking->duration_months + $this->extraMonths;

        $this->booking->update([
            'duration_months' => $newDuration,
            'total_price'      => $this->booking->total_price + $this->totalPrice,
            'check_out_date'   => $this->booking->check_in_date->copy()->addMonths($newDuration),
        ]);

        // Generate tagihan bulan tambahan (fix inti: tagihan ikut durasi, bukan cuma 1 bulan)
        $billing->generateRenewalInvoices($this->booking, $this->extraMonths);

        // Notifikasi ke tenant
        Auth::user()->notify(new BookingRenewedNotification($this->booking, $this->extraMonths));

        // Notifikasi ke admin & pemilik properti
        User::role('super_admin')->get()
            ->each(fn ($u) => $u->notify(new BookingRenewedNotification($this->booking, $this->extraMonths, forAdmin: true)));

        $owner = $this->booking->room->property->user;
        if ($owner) {
            $owner->notify(new BookingRenewedNotification($this->booking, $this->extraMonths, forAdmin: true));
        }

        session()->flash('success', "Sewa berhasil diperpanjang {$this->extraMonths} bulan! Tagihan baru sudah muncul di halaman Tagihan.");
        $this->redirect(route('payments.index'));
    }

    public function render()
    {
        return view('livewire.tenant.renewal-form')->layout('layouts.makaan');
    }
}
