<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WelcomeNotification extends Notification
{
    use Queueable;

    /**
     * Kirim via email
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Isi email selamat datang
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Selamat Datang di Kostify! 🏠')
            ->greeting("Halo, {$notifiable->name}!")
            ->line('Akun Anda berhasil dibuat di Kostify.')
            ->line('Sekarang Anda bisa:')
            ->line('✅ Mencari dan booking kamar kos')
            ->line('✅ Cek tagihan pembayaran')
            ->line('✅ Submit keluhan & komplain')
            ->action('Mulai Sekarang', url('/dashboard'))
            ->line('Terima kasih telah bergabung dengan Kostify!');
    }
}