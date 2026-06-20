<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LoginNotification extends Notification
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
     * Isi email notifikasi login
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Login Berhasil - Kostify')
            ->greeting("Halo, {$notifiable->name}!")
            ->line('Kami mendeteksi login baru ke akun Anda.')
            ->line('**Waktu Login:** ' . now()->format('d M Y, H:i') . ' WIB')
            ->line('Jika ini bukan Anda, segera ganti password.')
            ->action('Ganti Password', url('/profile'))
            ->line('Terima kasih!');
    }
}