<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Payment;

class BillingService
{
    /**
     * Generate tagihan untuk booking yang baru di-approve admin.
     * SEBELUM: cuma nagih 1 bulan (price_monthly) walaupun durasi sewa berbulan-bulan. BUG.
     * SEKARANG: 1 invoice lump-sum = total_price booking (price_monthly x duration_months),
     * dibayar sekali di depan sesuai durasi yang dipesan.
     */
    public function generateInitialInvoices(Booking $booking): void
    {
        Payment::create([
            'booking_id'   => $booking->id,
            'period_month' => $booking->duration_months, // dipakai buat nampilin "mencakup X bulan"
            'is_renewal'   => false,
            'amount'       => $booking->total_price,
            'fine_amount'  => 0,
            'total_amount' => $booking->total_price,
            'due_date'     => now()->addDays(2),
            'status'       => 'unpaid',
        ]);
    }

    /**
     * Generate tagihan tambahan saat tenant melakukan perpanjangan sewa.
     * Sama seperti invoice awal: lump-sum = price_monthly x jumlah bulan tambahan.
     */
    public function generateRenewalInvoices(Booking $booking, int $extraMonths): void
    {
        $amount = $booking->room->price_monthly * $extraMonths;

        Payment::create([
            'booking_id'   => $booking->id,
            'period_month' => $extraMonths,
            'is_renewal'   => true,
            'amount'       => $amount,
            'fine_amount'  => 0,
            'total_amount' => $amount,
            'due_date'     => now()->addDays(2),
            'status'       => 'unpaid',
        ]);
    }
}
