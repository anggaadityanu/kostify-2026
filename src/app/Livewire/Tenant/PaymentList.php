<?php

namespace App\Livewire\Tenant;

use App\Models\Payment;
use App\Services\MidtransService;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class PaymentList extends Component
{
    use WithPagination;

    public string $snapToken  = '';
    public bool   $showPayment = false;
    public ?int   $payingId    = null;

    public function pay(int $paymentId): void
    {
        $payment = Payment::findOrFail($paymentId);

        if ($payment->booking->tenant->user_id !== Auth::id()) {
            session()->flash('error', 'Tagihan tidak ditemukan.');
            return;
        }

        try {
            $midtrans        = app(MidtransService::class);
            $this->snapToken = $midtrans->createTransaction($payment);
            $this->payingId  = $paymentId;
            $this->showPayment = true;

            $this->dispatch('open-midtrans-snap', token: $this->snapToken, paymentId: $paymentId);
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal memproses pembayaran: ' . $e->getMessage());
        }
    }

    #[On('midtrans-finished')]
    public function checkStatus(int $paymentId): void
    {
        $payment = Payment::find($paymentId);

        if (!$payment || $payment->booking->tenant->user_id !== Auth::id()) {
            return;
        }

        app(MidtransService::class)->checkStatus($payment);
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