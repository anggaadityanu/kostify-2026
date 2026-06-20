<?php

namespace App\Livewire\Tenant;

use App\Models\Payment;
use App\Services\MidtransService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class PaymentList extends Component
{
    use WithPagination;

    public string $snapToken  = '';
    public bool   $showPayment = false;
    public ?int   $payingId    = null;

    /**
     * Trigger popup Midtrans
     * Logika: request snap token → tampilkan popup bayar
     */
    public function pay(int $paymentId): void
    {
        $payment = Payment::findOrFail($paymentId);

        // Pastikan payment milik tenant yang login
        if ($payment->booking->tenant->user_id !== Auth::id()) {
            return;
        }

        try {
            $midtrans        = app(MidtransService::class);
            $this->snapToken = $midtrans->createTransaction($payment);
            $this->payingId  = $paymentId;
            $this->showPayment = true;

            // Dispatch event ke JS untuk buka popup Midtrans
            $this->dispatch('open-midtrans-snap', token: $this->snapToken);
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal memproses pembayaran: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $tenant = Auth::user()->tenant;

        $payments = Payment::whereHas('booking', fn ($q) =>
            $q->where('tenant_id', $tenant?->id)
        )
        ->with('booking.room.property')
        ->latest()
        ->paginate(10);

        return view('livewire.tenant.payment-list', [
            'payments' => $payments,
        ])->layout('layouts.makaan');
    }
}