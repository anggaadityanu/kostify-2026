@section("page-title", "Pembayaran")

<div>
    {{-- Header --}}
    <div class="mb-4">
        <h2 class="fw-bold mb-1">Tagihan Saya</h2>
        <p class="text-muted mb-0">Riwayat dan tagihan pembayaran</p>
    </div>

    {{-- Flash Message --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show mb-4">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Summary Cards --}}
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="bg-light rounded p-4">
                <p class="text-muted mb-1 small">Total Belum Bayar</p>
                <h4 class="text-danger fw-bold mb-0">
                    {{ $payments->where('status', 'unpaid')->count() }} tagihan
                </h4>
            </div>
        </div>
        <div class="col-md-4">
            <div class="bg-light rounded p-4">
                <p class="text-muted mb-1 small">Menunggu Konfirmasi</p>
                <h4 class="text-warning fw-bold mb-0">
                    {{ $payments->where('status', 'pending')->count() }} tagihan
                </h4>
            </div>
        </div>
        <div class="col-md-4">
            <div class="bg-light rounded p-4">
                <p class="text-muted mb-1 small">Sudah Lunas</p>
                <h4 class="text-success fw-bold mb-0">
                    {{ $payments->where('status', 'paid')->count() }} tagihan
                </h4>
            </div>
        </div>
    </div>

    {{-- List Tagihan --}}
    @forelse($payments as $payment)
        <div class="card mb-3 border-0 shadow-sm">
            <div class="card-body p-4">
                <div class="row align-items-center">
                    <div class="col-md-7">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <span class="badge bg-secondary">{{ $payment->invoice_number }}</span>
                            <span class="badge {{ match($payment->status) {
                                'unpaid'    => 'bg-danger',
                                'pending'   => 'bg-warning text-dark',
                                'paid'      => 'bg-success',
                                'overdue'   => 'bg-danger',
                                'cancelled' => 'bg-secondary',
                                default     => 'bg-secondary',
                            } }}">
                                {{ match($payment->status) {
                                    'unpaid'    => 'Belum Bayar',
                                    'pending'   => 'Menunggu Konfirmasi',
                                    'paid'      => 'Lunas',
                                    'overdue'   => 'Menunggak',
                                    'cancelled' => 'Dibatalkan',
                                    default     => $payment->status,
                                } }}
                            </span>
                        </div>

                        <h6 class="fw-bold mb-1">
                            {{ $payment->booking->room->property->name ?? '-' }} -
                            Kamar {{ $payment->booking->room->room_number ?? '-' }}
                        </h6>

                        <p class="text-muted small mb-1">
                            <i class="fa fa-calendar me-1"></i>
                            Jatuh tempo: {{ $payment->due_date?->format('d M Y') }}
                        </p>

                        @if($payment->status === 'paid' && $payment->paid_date)
                            <p class="text-success small mb-0">
                                <i class="fa fa-check-circle me-1"></i>
                                Dibayar pada {{ $payment->paid_date->format('d M Y') }}
                            </p>
                        @endif
                    </div>

                    <div class="col-md-3 text-md-end">
                        <p class="text-muted small mb-1">Total Tagihan</p>
                        <h5 class="fw-bold {{ $payment->status === 'paid' ? 'text-success' : 'text-danger' }}">
                            Rp {{ number_format($payment->total_amount, 0, ',', '.') }}
                        </h5>
                        @if($payment->late_fee > 0)
                            <small class="text-danger">
                                (termasuk denda Rp {{ number_format($payment->late_fee, 0, ',', '.') }})
                            </small>
                        @endif
                    </div>

                    <div class="col-md-2 text-md-end mt-3 mt-md-0">
                        @if($payment->status === 'unpaid' || $payment->status === 'overdue')
                            <button wire:click="pay({{ $payment->id }})"
                                class="btn btn-primary w-100">
                                <i class="fa fa-credit-card me-1"></i>Bayar
                            </button>
                        @elseif($payment->status === 'paid')
                            <a href="{{ route('invoice.download', $payment->id) }}"
                                class="btn btn-outline-success w-100">
                                <i class="fa fa-download me-1"></i>Invoice
                            </a>
                        @elseif($payment->status === 'pending')
                            <span class="badge bg-warning text-dark w-100 py-2">
                                Menunggu
                            </span>
                        @elseif($payment->status === 'cancelled')
                            <span class="badge bg-secondary w-100 py-2">
                                Dibatalkan
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="text-center py-5">
            <i class="fa fa-inbox fa-3x text-muted mb-3"></i>
            <h5 class="text-muted">Belum ada tagihan</h5>
            <p class="text-muted">Tagihan akan muncul setelah booking disetujui</p>
        </div>
    @endforelse
</div>

{{-- Script Midtrans Snap --}}
<script src="https://app.sandbox.midtrans.com/snap/snap.js"
    data-client-key="{{ config('midtrans.client_key') }}"></script>

<script>
    document.addEventListener('livewire:init', () => {
        Livewire.on('open-midtrans-snap', (data) => {
            window.snap.pay(data.token, {
                onSuccess: function() {
                    Livewire.dispatch('midtrans-finished', { paymentId: data.paymentId });
                },
                onPending: function() {
                    Livewire.dispatch('midtrans-finished', { paymentId: data.paymentId });
                },
                onError: function() {
                    alert('Pembayaran gagal, silakan coba lagi.');
                },
                onClose: function() {
                    console.log('Popup ditutup tanpa menyelesaikan pembayaran, tagihan tetap bisa dicoba lagi');
                }
            });
        });
    });
</script>