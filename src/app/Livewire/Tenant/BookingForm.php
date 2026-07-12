<?php

namespace App\Livewire\Tenant;

use App\Models\Booking;
use App\Models\Room;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class BookingForm extends Component
{
    public int    $roomId;
    public ?Room  $room    = null;
    public string $checkInDate     = '';
    public int    $durationMonths  = 1;
    public string $notes           = '';
    public float  $totalPrice      = 0;

    public function mount(int $roomId): void
    {
        /**
         * Load kamar yang dipilih
         * Cek apakah tenant sudah punya profil
         * Cek apakah kamar masih available
         */
        $this->roomId = $roomId;
        $this->room   = Room::with('property')->findOrFail($roomId);

        // Redirect kalau kamar tidak available
        if ($this->room->status !== 'available') {
            session()->flash('error', 'Kamar tidak tersedia!');
            $this->redirect(route('rooms.index'));
        }

        // Redirect kalau profil belum lengkap
        if (!Auth::user()->tenant) {
            session()->flash('warning', 'Lengkapi profil dulu sebelum booking!');
            $this->redirect(route('profile.complete'));
        }

        // Default check in = hari ini
        $this->checkInDate = now()->format('Y-m-d');
        $this->calculateTotal();
    }

    /**
     * Hitung total harga otomatis
     */
    public function updatedDurationMonths(): void
    {
        $this->calculateTotal();
    }

    protected function calculateTotal(): void
    {
        $this->totalPrice = $this->room->price_monthly * $this->durationMonths;
    }

    /**
     * Submit booking
     * Logika:
     * 1. Validasi input
     * 2. Buat booking → status pending (kamar otomatis jadi 'booked', lihat Booking model)
     * 3. Tagihan/Payment BELUM dibuat di sini — baru dibuat saat admin approve
     * 4. Redirect ke dashboard, tenant menunggu approval admin
     */
    public function submit(): void
    {
        $this->validate([
            'checkInDate'    => 'required|date|after_or_equal:today',
            'durationMonths' => 'required|integer|min:1|max:24',
        ], [
            'checkInDate.required'         => 'Tanggal masuk wajib diisi.',
            'checkInDate.after_or_equal'   => 'Tanggal masuk tidak boleh kurang dari hari ini.',
            'durationMonths.required'      => 'Durasi sewa wajib diisi.',
        ]);

        $tenant = Auth::user()->tenant;

        // Buat booking (status pending, kamar otomatis ditandai 'booked')
        $booking = Booking::create([
            'room_id'         => $this->room->id,
            'tenant_id'       => $tenant->id,
            'check_in_date'   => $this->checkInDate,
            'duration_months' => $this->durationMonths,
            'total_price'     => $this->totalPrice,
            'status'          => 'pending',
            'notes'           => $this->notes,
        ]);

        // Notifikasi ke admin & pemilik properti
        \App\Models\User::role('super_admin')->get()
            ->each(fn ($u) => $u->notify(new \App\Notifications\NewBookingNotification($booking)));

        $owner = $this->room->property->user;
        if ($owner) {
            $owner->notify(new \App\Notifications\NewBookingNotification($booking));
        }

        session()->flash('success', 'Booking berhasil dibuat! Mohon tunggu, admin akan memverifikasi booking kamu. Tagihan akan muncul setelah disetujui.');
        $this->redirect(route('dashboard'));
    }

    public function render()
    {
        return view('livewire.tenant.booking-form', [
            'room' => $this->room,
        ])->layout('layouts.makaan');
    }
}