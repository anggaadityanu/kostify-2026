<?php

namespace App\Services;

use App\Models\Payment;
use App\Models\Booking;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Notification;
use Midtrans\Transaction;

class MidtransService
{
    public function __construct()
    {
        Config::$serverKey    = config('midtrans.server_key');
        Config::$clientKey    = config('midtrans.client_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized  = config('midtrans.is_sanitized');
        Config::$is3ds        = config('midtrans.is_3ds');
    }

    public function createTransaction(Payment $payment): string
    {
        $booking = $payment->booking;
        $tenant  = $booking->tenant;
        $user    = $tenant->user;

        $orderId = $payment->invoice_number . '-' . now()->format('YmdHis') . '-' . random_int(100, 999);

        $params = [
            'transaction_details' => [
                'order_id'     => $orderId,
                'gross_amount' => (int) $payment->total_amount,
            ],

            'customer_details' => [
                'first_name' => $user->name,
                'email'      => $user->email,
                'phone'      => $tenant->phone ?? '',
            ],

            'item_details' => [
                [
                    'id'       => 'SEWA-' . $booking->room->room_number,
                    'price'    => (int) $payment->amount,
                    'quantity' => 1,
                    'name'     => 'Sewa Kamar ' . $booking->room->room_number .
                                  ' - ' . $booking->room->property->name,
                ],
                ...(($payment->fine_amount > 0) ? [[
                    'id'       => 'DENDA-' . $payment->invoice_number,
                    'price'    => (int) $payment->fine_amount,
                    'quantity' => 1,
                    'name'     => 'Denda Keterlambatan',
                ]] : []),
            ],

            'callbacks' => [
                'finish' => url('/dashboard/payments'),
            ],
        ];

        $snapToken = Snap::getSnapToken($params);

        $payment->update([
            'midtrans_transaction_id' => $orderId,
        ]);

        return $snapToken;
    }

    public function checkStatus(Payment $payment): void
    {
        if (!$payment->midtrans_transaction_id) {
            return;
        }

        $status = (array) Transaction::status($payment->midtrans_transaction_id);

        $transactionStatus = $status['transaction_status'] ?? null;
        $fraudStatus        = $status['fraud_status'] ?? null;

        if ($transactionStatus === 'capture') {
            if ($fraudStatus === 'accept') {
                $this->markAsPaid($payment);
            }
        } elseif ($transactionStatus === 'settlement') {
            $this->markAsPaid($payment);
        } elseif (in_array($transactionStatus, ['cancel', 'deny', 'expire'])) {
            $payment->update(['status' => 'unpaid']);
        } elseif ($transactionStatus === 'pending') {
            $payment->update(['status' => 'pending']);
        }
    }

    public function handleNotification(): array
    {
        $notification = new Notification();

        $transactionStatus = $notification->transaction_status;
        $fraudStatus       = $notification->fraud_status;
        $orderId           = $notification->order_id;

        $payment = Payment::where('midtrans_transaction_id', $orderId)->firstOrFail();

        $payment->update([
            'midtrans_response' => (array) $notification,
        ]);

        if ($transactionStatus === 'capture') {
            if ($fraudStatus === 'accept') {
                $this->markAsPaid($payment);
            }
        } elseif ($transactionStatus === 'settlement') {
            $this->markAsPaid($payment);
        } elseif (in_array($transactionStatus, ['cancel', 'deny', 'expire'])) {
            $payment->update([
                'status'    => 'cancelled',
                'paid_date' => null,
            ]);
        } elseif ($transactionStatus === 'pending') {
            $payment->update(['status' => 'pending']);
        }

        return ['status' => 'ok'];
    }

    protected function markAsPaid(Payment $payment): void
    {
        $payment->update([
            'status'          => 'paid',
            'paid_date'       => now(),
            'payment_method'  => 'midtrans',
        ]);

        $booking = $payment->booking;
        if ($booking->status === 'approved') {
            $booking->update(['status' => 'active']);
            $booking->room->update(['status' => 'occupied']);
        }

        $booking->tenant->user->notify(new \App\Notifications\PaymentConfirmedNotification($payment));

        \App\Models\User::role('super_admin')->get()
            ->each(fn ($u) => $u->notify(new \App\Notifications\PaymentReceivedNotification($payment)));

        $owner = $booking->room->property->user;
        if ($owner) {
            $owner->notify(new \App\Notifications\PaymentReceivedNotification($payment));
        }
    }
}