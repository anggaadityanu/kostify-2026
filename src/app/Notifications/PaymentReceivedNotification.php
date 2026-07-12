<?php

namespace App\Notifications;

use App\Models\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PaymentReceivedNotification extends Notification
{
    use Queueable;

    public function __construct(protected Payment $payment) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        $booking = $this->payment->booking;

        return [
            'title'   => 'Pembayaran Masuk',
            'message' => $booking->tenant->user->name . ' membayar tagihan '
                . $this->payment->invoice_number . ' sebesar Rp '
                . number_format($this->payment->total_amount, 0, ',', '.'),
            'url'  => '/admin/payments',
            'icon' => 'heroicon-o-banknotes',
        ];
    }
}
