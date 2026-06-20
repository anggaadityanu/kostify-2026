<?php

namespace App\Services;

use App\Models\Payment;
use App\Models\Booking;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Notification;

class MidtransService
{
    public function __construct()
    {
        /**
         * Setup konfigurasi Midtrans
         * Logika: ambil dari config → set ke Midtrans SDK
         */
        Config::$serverKey    = config('midtrans.server_key');
        Config::$clientKey    = config('midtrans.client_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized  = config('midtrans.is_sanitized');
        Config::$is3ds        = config('midtrans.is_3ds');
    }

    /**
     * Buat transaksi baru di Midtrans
     * Logika:
     * 1. Ambil data payment & booking
     * 2. Format data sesuai Midtrans API
     * 3. Request Snap Token ke Midtrans
     * 4. Return token untuk frontend
     */
    public function createTransaction(Payment $payment): string
    {
        $booking = $payment->booking;
        $tenant  = $booking->tenant;
        $user    = $tenant->user;

        $params = [
            // ID transaksi unik
            'transaction_details' => [
                'order_id'     => $payment->invoice_number,
                'gross_amount' => (int) $payment->total_amount,
            ],

            // Info pembeli
            'customer_details' => [
                'first_name' => $user->name,
                'email'      => $user->email,
                'phone'      => $tenant->phone ?? '',
            ],

            // Detail item yang dibayar
            'item_details' => [
                [
                    'id'       => 'SEWA-' . $booking->room->room_number,
                    'price'    => (int) $payment->amount,
                    'quantity' => 1,
                    'name'     => 'Sewa Kamar ' . $booking->room->room_number .
                                  ' - ' . $booking->room->property->name,
                ],
                // Tambahkan denda jika ada
                ...(($payment->fine_amount > 0) ? [[
                    'id'       => 'DENDA-' . $payment->invoice_number,
                    'price'    => (int) $payment->fine_amount,
                    'quantity' => 1,
                    'name'     => 'Denda Keterlambatan',
                ]] : []),
            ],

            // URL callback
            'callbacks' => [
                'finish' => url('/dashboard/payments'),
            ],
        ];

        // Request snap token ke Midtrans
        $snapToken = Snap::getSnapToken($params);

        // Simpan transaction ID sementara
        $payment->update([
            'midtrans_transaction_id' => $payment->invoice_number,
            'status'                  => 'pending',
        ]);

        return $snapToken;
    }

    /**
     * Handle notification/callback dari Midtrans
     * Logika:
     * 1. Terima POST request dari Midtrans
     * 2. Verifikasi signature key
     * 3. Cek status transaksi
     * 4. Update payment & booking di DB
     */
    public function handleNotification(): array
    {
        $notification = new Notification();

        $transactionStatus = $notification->transaction_status;
        $fraudStatus       = $notification->fraud_status;
        $orderId           = $notification->order_id;

        // Cari payment berdasarkan invoice number
        $payment = Payment::where('invoice_number', $orderId)->firstOrFail();

        // Simpan response lengkap dari Midtrans
        $payment->update([
            'midtrans_response' => (array) $notification,
        ]);

        /**
         * Logika update status berdasarkan response Midtrans:
         * - capture + accept = paid
         * - settlement = paid
         * - pending = pending
         * - deny/cancel/expire = cancelled
         */
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

    /**
     * Mark payment sebagai lunas
     * Logika: update payment → update booking jadi active
     */
    protected function markAsPaid(Payment $payment): void
    {
        $payment->update([
            'status'          => 'paid',
            'paid_date'       => now(),
            'payment_method'  => 'midtrans',
        ]);

        // Update booking jadi active setelah bayar
        $booking = $payment->booking;
        if ($booking->status === 'approved') {
            $booking->update(['status' => 'active']);
            $booking->room->update(['status' => 'occupied']);
        }
    }
}