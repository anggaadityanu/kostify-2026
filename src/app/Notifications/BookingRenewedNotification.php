<?php

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class BookingRenewedNotification extends Notification
{
    use Queueable;

    public function __construct(
        protected Booking $booking,
        protected int $extraMonths,
        protected bool $forAdmin = false
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        $tenantName = $this->booking->tenant->user->name;
        $room       = $this->booking->room->room_number;
        $property   = $this->booking->room->property->name;

        return [
            'title'   => $this->forAdmin ? 'Perpanjangan Sewa' : 'Perpanjangan Sewa Berhasil',
            'message' => $this->forAdmin
                ? "{$tenantName} memperpanjang sewa kamar {$room} di {$property} selama {$this->extraMonths} bulan."
                : "Sewa kamu untuk kamar {$room} berhasil diperpanjang {$this->extraMonths} bulan. Cek tagihan baru di halaman Tagihan.",
            'url'  => $this->forAdmin ? '/admin/bookings' : route('payments.index'),
            'icon' => 'heroicon-o-arrow-path',
        ];
    }
}
