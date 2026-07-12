<?php

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BookingApprovedNotification extends Notification
{
    use Queueable;

    public function __construct(
        protected Booking $booking
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'title'   => 'Booking Disetujui',
            'message' => 'Booking ' . $this->booking->booking_code . ' untuk kamar '
                . $this->booking->room->room_number . ' disetujui. Segera bayar tagihan sebelum jatuh tempo.',
            'url'  => route('payments.index'),
            'icon' => 'heroicon-o-check-badge',
        ];
    }

    /**
     * Email notifikasi booking disetujui
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Booking Anda Disetujui! - Kostify')
            ->greeting("Halo, {$notifiable->name}!")
            ->line('Booking Anda telah disetujui oleh pengelola.')
            ->line('**Detail Booking:**')
            ->line('Kode Booking: ' . $this->booking->booking_code)
            ->line('Properti: ' . $this->booking->room->property->name)
            ->line('Kamar: ' . $this->booking->room->room_number)
            ->line('Tanggal Masuk: ' . $this->booking->check_in_date->format('d M Y'))
            ->line('Durasi: ' . $this->booking->duration_months . ' bulan')
            ->action('Lihat Tagihan', url('/dashboard/payments'))
            ->line('Silakan lakukan pembayaran untuk mengaktifkan booking Anda.');
    }
}