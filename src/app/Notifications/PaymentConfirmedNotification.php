<?php

namespace App\Notifications;

use App\Models\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PaymentConfirmedNotification extends Notification
{
    use Queueable;

    public function __construct(protected Payment $payment) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'title'   => 'Pembayaran Berhasil',
            'message' => 'Tagihan ' . $this->payment->invoice_number . ' sebesar Rp '
                . number_format($this->payment->total_amount, 0, ',', '.') . ' telah dikonfirmasi lunas.',
            'url'  => route('payments.index'),
            'icon' => 'heroicon-o-check-circle',
        ];
    }
}
