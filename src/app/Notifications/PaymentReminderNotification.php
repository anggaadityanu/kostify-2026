<?php

namespace App\Notifications;

use App\Models\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PaymentReminderNotification extends Notification
{
    use Queueable;

    public function __construct(
        protected Payment $payment,
        protected int $daysRemaining
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Email reminder tagihan jatuh tempo
     */
    public function toMail(object $notifiable): MailMessage
    {
        $urgency = match(true) {
            $this->daysRemaining <= 1 => ' HARI INI',
            $this->daysRemaining <= 3 => $this->daysRemaining . ' Hari Lagi',
            default                   => $this->daysRemaining . ' Hari Lagi',
        };

        return (new MailMessage)
            ->subject("Reminder Tagihan {$urgency} - Kostify")
            ->greeting("Halo, {$notifiable->name}!")
            ->line("Tagihan sewa Anda akan jatuh tempo dalam **{$this->daysRemaining} hari**.")
            ->line('**Detail Tagihan:**')
            ->line('No. Invoice: ' . $this->payment->invoice_number)
            ->line('Properti: ' . $this->payment->booking->room->property->name)
            ->line('Kamar: ' . $this->payment->booking->room->room_number)
            ->line('Total: Rp ' . number_format($this->payment->total_amount, 0, ',', '.'))
            ->line('Jatuh Tempo: ' . $this->payment->due_date->format('d M Y'))
            ->action('Bayar Sekarang', url('/dashboard/payments'))
            ->line('Hindari denda keterlambatan dengan membayar tepat waktu!');
    }
}