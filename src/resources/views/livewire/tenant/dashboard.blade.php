<div>
    {{-- Header --}}
    <div class="mb-4">
        <h2 class="fw-bold mb-1">Halo, {{ Auth::user()->name }}!</h2>
        <p class="text-muted mb-0">Selamat datang di portal tenant Kostify</p>
    </div>

    {{-- Alert Profil Belum Lengkap --}}
    @if(!$tenant)
        <div class="alert alert-warning d-flex align-items-center gap-3 mb-4">
            <i class="fa fa-exclamation-triangle fa-2x"></i>
            <div>
                <strong>Profil belum lengkap!</strong>
                <p class="mb-1">Lengkapi data diri untuk bisa booking kamar.</p>
                <a href="{{ route('profile.complete') }}"
                    class="btn btn-warning btn-sm">
                    Lengkapi Sekarang
                </a>
            </div>
        </div>
    @endif

    {{-- Stats Cards --}}
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="bg-white rounded border shadow-sm p-4 h-100">
                <p class="text-muted mb-1">Status Sewa</p>
                @if($activeBooking)
                    <h4 class="text-primary fw-bold">Aktif</h4>
                    <small class="text-muted">
                        {{ $activeBooking->room->property->name }} -
                        Kamar {{ $activeBooking->room->room_number }}
                    </small>
                @else
                    <h4 class="text-muted">Tidak Ada</h4>
                    <a href="{{ route('rooms.index') }}" class="btn btn-primary btn-sm mt-2">
                        Cari Kamar
                    </a>
                @endif
            </div>
        </div>
        <div class="col-md-4">
            <div class="bg-white rounded border shadow-sm p-4 h-100">
                <p class="text-muted mb-1">Tagihan Belum Bayar</p>
                <h4 class="{{ $unpaidPayments->count() > 0 ? 'text-danger' : 'text-success' }} fw-bold">
                    {{ $unpaidPayments->count() }} tagihan
                </h4>
                @if($unpaidPayments->count() > 0)
                    <small class="text-danger">
                        Total: Rp {{ number_format($unpaidPayments->sum('total_amount'), 0, ',', '.') }}
                    </small>
                @endif
            </div>
        </div>
        <div class="col-md-4">
            <div class="bg-white rounded border shadow-sm p-4 h-100">
                <p class="text-muted mb-1">Komplain Aktif</p>
                <h4 class="{{ $openComplaints->count() > 0 ? 'text-warning' : 'text-success' }} fw-bold">
                    {{ $openComplaints->count() }} komplain
                </h4>
            </div>
        </div>
    </div>

    {{-- Booking Menunggu Approve / Bayar --}}
    @if($pendingBookings->count() > 0)
        <div class="bg-white rounded border shadow-sm mb-4">
            <div class="p-3 border-bottom">
                <h6 class="fw-bold mb-0">Booking Saya</h6>
            </div>
            <div class="p-3">
                @foreach($pendingBookings as $booking)
                    <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                        <div>
                            <p class="mb-0 fw-medium">
                                {{ $booking->room->property->name ?? '-' }} -
                                Kamar {{ $booking->room->room_number ?? '-' }}
                            </p>
                            <small class="text-muted">{{ $booking->booking_code }}</small>
                        </div>
                        <div class="text-end">
                            @if($booking->status === 'pending')
                                <span class="badge bg-warning text-dark">Menunggu Approve Admin</span>
                            @elseif($booking->status === 'approved')
                                <span class="badge bg-info text-dark d-block mb-1">Disetujui</span>
                                <a href="{{ route('payments.index') }}" class="btn btn-primary btn-sm">
                                    Bayar Sekarang
                                </a>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <div class="row g-4">
        {{-- Tagihan Belum Bayar --}}
        <div class="col-md-6">
            <div class="bg-white rounded border shadow-sm h-100">
                <div class="p-3 border-bottom d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold mb-0">Tagihan Belum Bayar</h6>
                    <a href="{{ route('payments.index') }}" class="text-primary small">Lihat Semua</a>
                </div>
                <div class="p-3">
                    @forelse($unpaidPayments as $payment)
                        <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                            <div>
                                <p class="mb-0 fw-medium">{{ $payment->invoice_number }}</p>
                                <small class="text-muted">
                                    Jatuh tempo: {{ $payment->due_date->format('d M Y') }}
                                </small>
                                @if($payment->status === 'overdue')
                                    <span class="badge bg-danger ms-1">Terlambat</span>
                                @endif
                            </div>
                            <div class="text-end">
                                <p class="text-danger fw-bold mb-1">
                                    Rp {{ number_format($payment->total_amount, 0, ',', '.') }}
                                </p>
                                <a href="{{ route('payments.index') }}"
                                    class="btn btn-primary btn-sm">Bayar</a>
                            </div>
                        </div>
                    @empty
                        <p class="text-center text-muted py-3">Semua tagihan sudah lunas!</p>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Riwayat Pembayaran --}}
        <div class="col-md-6">
            <div class="bg-white rounded border shadow-sm h-100">
                <div class="p-3 border-bottom">
                    <h6 class="fw-bold mb-0">Riwayat Pembayaran</h6>
                </div>
                <div class="p-3">
                    @forelse($recentPayments as $payment)
                        <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                            <div>
                                <p class="mb-0 fw-medium">{{ $payment->invoice_number }}</p>
                                <small class="text-muted">{{ $payment->paid_date?->format('d M Y') }}</small>
                            </div>
                            <div class="text-end">
                                <p class="text-success fw-bold mb-0">
                                    Rp {{ number_format($payment->total_amount, 0, ',', '.') }}
                                </p>
                                <small class="text-success">Lunas</small>
                            </div>
                        </div>
                    @empty
                        <p class="text-center text-muted py-3">Belum ada riwayat pembayaran</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    {{-- Quick Actions --}}
    <div class="row g-3 mt-2">
        <div class="col-6 col-md-3">
            <a href="{{ route('rooms.index') }}"
                class="btn btn-primary w-100 py-3 d-flex flex-column align-items-center">
                <i class="fa fa-search fa-lg mb-1"></i>
                <small>Cari Kamar</small>
            </a>
        </div>
        <div class="col-6 col-md-3">
            <a href="{{ route('payments.index') }}"
                class="btn btn-success w-100 py-3 d-flex flex-column align-items-center">
                <i class="fa fa-credit-card fa-lg mb-1"></i>
                <small>Bayar Tagihan</small>
            </a>
        </div>
        <div class="col-6 col-md-3">
            <a href="{{ route('complaints.index') }}"
                class="btn btn-warning w-100 py-3 d-flex flex-column align-items-center">
                <i class="fa fa-comment fa-lg mb-1"></i>
                <small>Submit Komplain</small>
            </a>
        </div>
        <div class="col-6 col-md-3">
            <a href="{{ route('profile.complete') }}"
                class="btn btn-secondary w-100 py-3 d-flex flex-column align-items-center">
                <i class="fa fa-user fa-lg mb-1"></i>
                <small>Edit Profil</small>
            </a>
        </div>
    </div>
</div>