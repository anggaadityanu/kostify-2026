<?php

namespace App\Console\Commands;

use App\Models\Booking;
use App\Models\Payment;
use App\Models\Contract;
use App\Notifications\PaymentReminderNotification;
use App\Notifications\ContractExpiryNotification;
use Illuminate\Console\Command;

class SendPaymentReminders extends Command
{
    protected $signature   = 'kostify:send-reminders';
    protected $description = 'Kirim reminder tagihan dan notifikasi kontrak hampir habis';

    public function handle(): void
    {
        $this->sendPaymentReminders();
        $this->markOverduePayments();
        $this->cancelExpiredBookings();
        $this->sendContractReminders();

        $this->info('✅ Semua reminder berhasil dikirim!');
    }

    protected function sendPaymentReminders(): void
    {
        // Kirim reminder H-7, H-3, H-1
        $reminderDays = [7, 3, 1];

        foreach ($reminderDays as $days) {
            $payments = Payment::whereIn('status', ['unpaid'])
                ->whereDate('due_date', now()->addDays($days)->toDateString())
                ->with('booking.tenant.user')
                ->get();

            foreach ($payments as $payment) {
                $user = $payment->booking->tenant->user;
                $user->notify(new PaymentReminderNotification($payment, $days));
                $this->info("📧 Reminder H-{$days} dikirim ke: {$user->email}");
            }
        }
    }

    protected function markOverduePayments(): void
    {
        /**
         * Auto mark overdue: payment unpaid yang sudah lewat jatuh tempo
         * Logika: cari payment unpaid + due_date sudah lewat →
         * update status overdue + hitung denda
         */
        $overduePayments = Payment::where('status', 'unpaid')
            ->whereDate('due_date', '<', now()->toDateString())
            ->with('booking.tenant.user')
            ->get();

        foreach ($overduePayments as $payment) {
            $fine = $payment->calculateFine();
            $payment->update([
                'status'       => 'overdue',
                'fine_amount'  => $fine,
                'total_amount' => $payment->amount + $fine,
            ]);
            $this->info("⚠️ Payment {$payment->invoice_number} ditandai overdue");
        }
    }

    /**
     * Auto-cancel booking yang sudah di-approve tapi tagihan
     * pertamanya tidak dibayar sampai lewat jatuh tempo (2 hari).
     * Logika: cari booking status 'approved' yang punya tagihan
     * unpaid/overdue dengan due_date sudah lewat → batalkan booking
     * (kamar otomatis balik ke 'available' lewat hook di model Booking)
     * dan tandai tagihan terkait jadi 'cancelled'.
     */
    protected function cancelExpiredBookings(): void
    {
        $expiredBookings = Booking::where('status', 'approved')
            ->whereHas('payments', function ($q) {
                $q->whereIn('status', ['unpaid', 'overdue'])
                  ->whereDate('due_date', '<', now()->toDateString());
            })
            ->with('payments', 'tenant.user', 'room')
            ->get();

        foreach ($expiredBookings as $booking) {
            $booking->update(['status' => 'cancelled']);

            $booking->payments()
                ->whereIn('status', ['unpaid', 'overdue'])
                ->update(['status' => 'cancelled']);

            $this->info("🚫 Booking {$booking->booking_code} otomatis dibatalkan (telat bayar > 2 hari)");
        }
    }

    protected function sendContractReminders(): void
    {
        /**
         * Kirim notifikasi kontrak hampir habis (H-30)
         */
        $contracts = Contract::where('status', 'active')
            ->whereDate('end_date', now()->addDays(30)->toDateString())
            ->with('booking.tenant.user', 'booking.room.property')
            ->get();

        foreach ($contracts as $contract) {
            $user = $contract->booking->tenant->user;
            $user->notify(new ContractExpiryNotification($contract));
            $this->info("📧 Notifikasi kontrak dikirim ke: {$user->email}");
        }
    }
}