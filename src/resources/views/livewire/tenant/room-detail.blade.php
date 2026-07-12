@section("page-title", "Detail Kamar")

<div>
    {{-- Breadcrumb --}}
    <div class="container-fluid bg-light py-3">
        <div class="container">
            <a href="{{ route('rooms.index') }}" class="text-primary text-decoration-none">
                Cari Kamar
            </a>
            <span class="text-muted mx-2">›</span>
            <span class="text-muted">{{ $room->property->name }} - Kamar {{ $room->room_number }}</span>
        </div>
    </div>

    <div class="container py-5">
        <div class="row g-5">

            {{-- Foto & Info Utama --}}
            <div class="col-lg-8">
                <div class="rounded overflow-hidden mb-2" style="height: 400px; background: #f0f0f0;">
                    <img src="{{ $room->coverPhotoUrl() }}"
                        class="w-100 h-100" style="object-fit: cover;" alt="{{ $room->property->name }}">
                </div>

                @if(!empty($room->photos) && count($room->photos) > 1)
                    <div class="d-flex gap-2 mb-4 flex-wrap">
                        @foreach($room->photos as $photo)
                            <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($photo) }}"
                                class="rounded" style="width: 80px; height: 80px; object-fit: cover; cursor: pointer;"
                                onclick="document.querySelector('.rounded.overflow-hidden.mb-2 img').src = this.src">
                        @endforeach
                    </div>
                @else
                    <div class="mb-4"></div>
                @endif

                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <h2 class="fw-bold mb-1">{{ $room->property->name }}</h2>
                        <p class="text-muted mb-0">
                            <i class="fa fa-map-marker-alt text-primary me-2"></i>
                            {{ $room->property->address }}, {{ $room->property->city }}
                        </p>
                    </div>
                    <span class="badge {{ $room->status === 'available' ? 'bg-success' : 'bg-danger' }} fs-6 px-3 py-2">
                        {{ $room->status === 'available' ? 'Tersedia' : 'Terisi' }}
                    </span>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-4">
                        <div class="bg-light rounded p-3 text-center">
                            <i class="fa fa-door-open text-primary fa-lg mb-2"></i>
                            <p class="mb-0 small text-muted">Tipe Kamar</p>
                            <p class="fw-semibold mb-0">{{ ucfirst($room->type) }}</p>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="bg-light rounded p-3 text-center">
                            <i class="fa fa-user text-primary fa-lg mb-2"></i>
                            <p class="mb-0 small text-muted">Kapasitas</p>
                            <p class="fw-semibold mb-0">{{ $room->capacity }} orang</p>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="bg-light rounded p-3 text-center">
                            <i class="fa fa-ruler-combined text-primary fa-lg mb-2"></i>
                            <p class="mb-0 small text-muted">Ukuran</p>
                            <p class="fw-semibold mb-0">{{ $room->size ?? '-' }} m²</p>
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <h5 class="fw-bold mb-3">Deskripsi</h5>
                    <p class="text-muted">{{ $room->description ?? 'Tidak ada deskripsi tambahan.' }}</p>
                </div>

                @if($room->facilities)
                    <div class="mb-4">
                        <h5 class="fw-bold mb-3">Fasilitas</h5>
                        <div class="row g-2">
                            @foreach(is_array($room->facilities) ? $room->facilities : json_decode($room->facilities, true) ?? [] as $facility)
                                <div class="col-6 col-md-4">
                                    <p class="mb-0">
                                        <i class="fa fa-check-circle text-success me-2"></i>{{ $facility }}
                                    </p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Lokasi Maps --}}
                @if($room->property->latitude)
                    <div class="mb-4">
                        <h5 class="fw-bold mb-3">Lokasi</h5>
                        <div class="rounded overflow-hidden" style="height: 300px; border: 3px solid #00B98E;">
                            <iframe
                                src="https://www.google.com/maps/embed/v1/place?key={{ config('services.google_maps.key') }}&q={{ $room->property->latitude }},{{ $room->property->longitude }}"
                                width="100%" height="300" style="border:0;" allowfullscreen loading="lazy">
                            </iframe>
                        </div>
                        <a href="https://www.google.com/maps/dir/?api=1&destination={{ $room->property->latitude }},{{ $room->property->longitude }}"
                            target="_blank" class="btn btn-outline-primary btn-sm mt-3">
                            <i class="fa fa-directions me-1"></i>Lihat Rute ke Sini
                        </a>
                    </div>
                @endif
            </div>

            {{-- Sidebar Booking --}}
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm sticky-top" style="top: 20px;">
                    <div class="card-body p-4">
                        <h3 class="text-primary fw-bold mb-1">
                            Rp {{ number_format($room->price_monthly, 0, ',', '.') }}
                        </h3>
                        <p class="text-muted mb-4">/bulan</p>

                        @if($room->price_yearly)
                            <p class="text-muted mb-4">
                                <i class="fa fa-tag me-1"></i>
                                Tahunan: Rp {{ number_format($room->price_yearly, 0, ',', '.') }}
                            </p>
                        @endif

                        @auth
                            @if($room->status === 'available')
                                <a href="{{ route('booking.form', $room->id) }}"
                                    class="btn btn-primary w-100 py-3 mb-2">
                                    <i class="fa fa-calendar-check me-2"></i>Booking Sekarang
                                </a>
                            @else
                                <button class="btn btn-secondary w-100 py-3 mb-2" disabled>
                                    Kamar Sudah Terisi
                                </button>
                            @endif
                        @else
                            <a href="{{ route('login') }}"
                                class="btn btn-primary w-100 py-3 mb-2">
                                <i class="fa fa-lock me-2"></i>Login untuk Booking
                            </a>
                            <p class="text-center text-muted small mb-0">
                                Belum punya akun?
                                <a href="{{ route('register') }}" class="text-primary">Daftar di sini</a>
                            </p>
                        @endauth

                        <hr class="my-4">

                        <h6 class="fw-bold mb-3">Tipe Properti</h6>
                        <p class="mb-0">
                            <i class="fa fa-home text-primary me-2"></i>
                            {{ match($room->property->type) {
                                'kos_putra'  => 'Kos Putra',
                                'kos_putri'  => 'Kos Putri',
                                'kos_campur' => 'Kos Campur',
                                'kontrakan'  => 'Kontrakan',
                            } }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>