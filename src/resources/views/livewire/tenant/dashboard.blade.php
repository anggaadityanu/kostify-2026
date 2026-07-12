<div class="tenant-dashboard-page py-4 py-md-5">
    <div class="container">
        <div class="mb-4 mb-md-5">
            <h1 class="fw-bold text-gray-900 mb-2 tenant-dashboard-title">Halo, {{ Auth::user()->name }}!</h1>
            <p class="text-gray-500 mb-0 fs-5 tenant-dashboard-subtitle">Selamat datang di portal tenant Kostify</p>
        </div>

        @if(!$tenant)
            <div class="alert alert-warning border-0 rounded-3 shadow-sm mb-4 p-4 tenant-profile-alert">
                <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center gap-3">
                    <div class="flex-shrink-0 text-warning tenant-alert-icon">
                        <i class="fa fa-exclamation-triangle"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h5 class="fw-bold mb-1 text-warning-emphasis">Profil belum lengkap!</h5>
                        <p class="mb-3 mb-md-2 text-gray-800">Lengkapi data diri untuk bisa booking kamar.</p>
                        <a href="{{ route('profile.complete') }}" class="btn btn-warning rounded-3 px-4 fw-semibold">Lengkapi Sekarang</a>
                    </div>
                </div>
            </div>
        @endif

        <div class="row g-4 mb-4">
            <div class="col-12 col-md-4">
                <div class="tenant-card h-100">
                    <p class="tenant-card-label">Status Sewa</p>
                    <h2 class="tenant-card-value {{ $activeBooking ? 'text-success' : 'text-gray-700' }}">{{ $activeBooking ? 'Aktif' : 'Tidak Ada' }}</h2>
                    @if($activeBooking)
                        <p class="text-gray-500 mb-2">{{ $activeBooking->room->property->name }} &bull; Kamar {{ $activeBooking->room->room_number }}</p>
                        <a href="{{ route('renewal.form', $activeBooking->id) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                            <i class="fa fa-redo me-1"></i> Perpanjang Sewa
                        </a>
                    @endif
                </div>
            </div>

            <div class="col-12 col-md-4">
                <div class="tenant-card h-100">
                    <p class="tenant-card-label">Tagihan Belum Bayar</p>
                    <h2 class="tenant-card-value text-success">{{ $unpaidPayments->count() }} tagihan</h2>
                    @if($unpaidPayments->count() > 0)
                        <p class="text-danger fw-semibold mb-0">Rp {{ number_format($unpaidPayments->sum('total_amount'), 0, ',', '.') }}</p>
                    @endif
                </div>
            </div>

            <div class="col-12 col-md-4">
                <div class="tenant-card h-100">
                    <p class="tenant-card-label">Komplain Aktif</p>
                    <h2 class="tenant-card-value text-success">{{ $openComplaints->count() }} komplain</h2>
                </div>
            </div>
        </div>

        @if($pendingBookings->count() > 0)
            <div class="tenant-panel mb-4">
                <div class="tenant-panel-header">
                    <h3>Booking Aktif Saya</h3>
                </div>
                <div class="tenant-list tenant-panel-body">
                    @foreach($pendingBookings as $booking)
                        <div class="tenant-list-item">
                            <div>
                                <h4>{{ $booking->room->property->name ?? '-' }}</h4>
                                <p>Kamar {{ $booking->room->room_number ?? '-' }} &bull; {{ $booking->booking_code }}</p>
                            </div>
                            <span class="badge rounded-pill {{ $booking->status === 'pending' ? 'bg-warning text-dark' : 'bg-success' }}">
                                {{ $booking->status === 'pending' ? 'Menunggu Approve' : 'Disetujui' }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <div class="row g-4 mb-4">
            <div class="col-12 col-lg-6">
                <div class="tenant-panel h-100">
                    <div class="tenant-panel-header d-flex align-items-center justify-content-between">
                        <h3>Tagihan Belum Bayar</h3>
                        <a href="{{ route('payments.index') }}">Lihat Semua</a>
                    </div>
                    <div class="tenant-panel-body">
                        @forelse($unpaidPayments as $payment)
                            <div class="tenant-list-item px-0">
                                <div>
                                    <h4>{{ $payment->invoice_number }}</h4>
                                    <p>Jatuh tempo: {{ $payment->due_date->format('d M Y') }}</p>
                                </div>
                                <div class="text-end">
                                    <strong>Rp {{ number_format($payment->total_amount, 0, ',', '.') }}</strong>
                                    <a href="{{ route('payments.index') }}" class="btn btn-sm btn-primary rounded-3 d-block mt-2">Bayar</a>
                                </div>
                            </div>
                        @empty
                            <div class="tenant-empty">Semua tagihan sudah lunas!</div>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-6">
                <div class="tenant-panel h-100">
                    <div class="tenant-panel-header">
                        <h3>Riwayat Pembayaran</h3>
                    </div>
                    <div class="tenant-panel-body">
                        @forelse($recentPayments as $payment)
                            <div class="tenant-list-item px-0">
                                <div>
                                    <h4>{{ $payment->invoice_number }}</h4>
                                    <p>{{ $payment->paid_date?->format('d M Y') }}</p>
                                </div>
                                <strong>Rp {{ number_format($payment->total_amount, 0, ',', '.') }}</strong>
                            </div>
                        @empty
                            <div class="tenant-empty">Belum ada riwayat pembayaran</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-3 tenant-actions">
            <div class="col-6 col-md-3">
                <a href="{{ route('rooms.index') }}" class="tenant-action bg-primary">Cari Kamar</a>
            </div>
            <div class="col-6 col-md-3">
                <a href="{{ route('payments.index') }}" class="tenant-action bg-success">Bayar Tagihan</a>
            </div>
            <div class="col-6 col-md-3">
                <a href="{{ route('complaints.index') }}" class="tenant-action bg-warning text-dark">Komplain</a>
            </div>
            <div class="col-6 col-md-3">
                <a href="{{ route('profile.complete') }}" class="tenant-action bg-danger">Edit Profil</a>
            </div>
        </div>
    </div>
</div>
