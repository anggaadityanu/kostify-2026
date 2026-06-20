<?php

namespace App\Notifications;

use App\Models\Contract;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ContractExpiryNotification extends Notification
{
    use Queueable;

    public function __construct(
        protected Contract $contract
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Email notifikasi kontrak hampir habis
     */
    public function toMail(object $notifiable): MailMessage
    {
        $daysLeft = $this->contract->daysRemaining();

        return (new MailMessage)
            ->subject('Kontrak Sewa Hampir Berakhir - Kostify')
            ->greeting("Halo, {$notifiable->name}!")
            ->line("Kontrak sewa Anda akan berakhir dalam **{$daysLeft} hari**.")
            ->line('**Detail Kontrak:**')
            ->line('No. Kontrak: ' . $this->contract->contract_number)
            ->line('Properti: ' . $this->contract->booking->room->property->name)
            ->line('Kamar: ' . $this->contract->booking->room->room_number)
            ->line('Berakhir: ' . $this->contract->end_date->format('d M Y'))
            ->action('Hubungi Pengelola', url('/dashboard'))
            ->line('Segera hubungi pengelola jika ingin memperpanjang kontrak.');
    }
}