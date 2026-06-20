<?php

namespace App\Console\Commands;

use App\Models\Payment;
use App\Models\Contract;
use App\Notifications\PaymentReminderNotification;
use App\Notifications\ContractExpiryNotification;
use Illuminate\Console\Command;

class SendPaymentReminders extends Command
{
    protected $signature   = 'kostify:send-reminders';
    protected $description = 'Kirim reminder tagihan dan notifikasi kontrak hampir habis';

    /**
     * Jalankan semua reminder
     * Logika:
     * 1. Cari payment yang jatuh tempo H-7, H-3, H-1
     * 2. Kirim email reminder ke tenant
     * 3. Cari kontrak yang hampir habis (H-30)
     * 4. Kirim notifikasi ke tenant
     * 5. Update payment overdue
     */
    public function handle(): void
    {
        $this->sendPaymentReminders();
        $this->markOverduePayments();
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