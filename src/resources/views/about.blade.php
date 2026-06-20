@extends('layouts.makaan')
@section('title', 'Tentang Kami - Kostify')
@section('content')

<div class="container-fluid py-5">
    <div class="container">
        <div class="row g-5 align-items-center">
            <div class="col-lg-6 wow fadeIn">
                <div class="about-img position-relative overflow-hidden p-5 pe-0">
                    <img class="img-fluid w-100"
                        src="{{ asset('makaan/img/carousel-1.jpg') }}"
                        style="border-radius: 8px;">
                </div>
            </div>
            <div class="col-lg-6 wow fadeIn">
                <h1 class="mb-4">Tentang Kostify</h1>
                <p class="mb-4">
                    Kostify adalah platform digital terpercaya untuk mencari,
                    booking, dan mengelola kos & kontrakan di Indonesia.
                    Kami hadir untuk memudahkan proses sewa-menyewa properti
                    secara transparan dan efisien.
                </p>
                <p><i class="fa fa-check text-primary me-3"></i>Booking kamar mudah & cepat</p>
                <p><i class="fa fa-check text-primary me-3"></i>Pembayaran online aman via Midtrans</p>
                <p><i class="fa fa-check text-primary me-3"></i>Laporan & notifikasi otomatis</p>
                <p><i class="fa fa-check text-primary me-3"></i>Support 24/7 untuk tenant</p>
                <a class="btn btn-primary py-3 px-5 mt-3"
                    href="{{ route('rooms.index') }}">
                    Cari Kamar Sekarang
                </a>
            </div>
        </div>
    </div>
</div>

{{-- Stats --}}
<div class="container-fluid bg-primary py-5">
    <div class="container">
        <div class="row g-4 text-center text-white">
            <div class="col-md-3">
                <h1 class="display-4 fw-bold">
                    {{ \App\Models\Property::where('status','active')->count() }}+
                </h1>
                <p class="opacity-75">Properti Aktif</p>
            </div>
            <div class="col-md-3">
                <h1 class="display-4 fw-bold">
                    {{ \App\Models\Room::where('status','available')->count() }}+
                </h1>
                <p class="opacity-75">Kamar Tersedia</p>
            </div>
            <div class="col-md-3">
                <h1 class="display-4 fw-bold">
                    {{ \App\Models\Tenant::count() }}+
                </h1>
                <p class="opacity-75">Tenant Terdaftar</p>
            </div>
            <div class="col-md-3">
                <h1 class="display-4 fw-bold">100%</h1>
                <p class="opacity-75">Kepuasan Tenant</p>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid py-5">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-4 wow fadeInUp" data-wow-delay="0.1s">
                <div class="bg-light rounded p-4 text-center h-100">
                    <i class="fa fa-phone-alt fa-3x text-primary mb-3"></i>
                    <h5>Telepon</h5>
                    <p class="text-muted mb-0">+62 821-1498-1216</p>
                    <p class="text-muted">Senin - Minggu, 24/7</p>
                </div>
            </div>
            <div class="col-lg-4 wow fadeInUp" data-wow-delay="0.3s">
                <div class="bg-light rounded p-4 text-center h-100">
                    <i class="fa fa-envelope fa-3x text-primary mb-3"></i>
                    <h5>Email</h5>
                    <p class="text-muted mb-0">info@kostify.com</p>
                    <p class="text-muted">Respon dalam 1x24 jam</p>
                </div>
            </div>
            <div class="col-lg-4 wow fadeInUp" data-wow-delay="0.5s">
                <div class="bg-light rounded p-4 text-center h-100">
                    <i class="fab fa-whatsapp fa-3x text-success mb-3"></i>
                    <h5>WhatsApp</h5>
                    <p class="text-muted mb-0">+62 821-1498-1216</p>
                    <a href="https://wa.me/6282114981216" target="_blank" class="btn btn-success btn-sm mt-2">
                        Chat Sekarang
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection