<?php

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewBookingNotification extends Notification
{
    use Queueable;

    public function __construct(protected Booking $booking) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'title'   => 'Booking Baru Masuk',
            'message' => $this->booking->tenant->user->name . ' memesan kamar '
                . $this->booking->room->room_number . ' di '
                . $this->booking->room->property->name . ' (' . $this->booking->duration_months . ' bulan)',
            'url'  => '/admin/bookings',
            'icon' => 'heroicon-o-calendar-days',
        ];
    }
}
