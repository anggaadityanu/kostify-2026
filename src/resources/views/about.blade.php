@extends('layouts.makaan')
@section('title', 'Tentang Kami - Kostify')
@section('content')

@php($settings = \App\Models\Setting::current())

<!-- Hero/Header Section -->
<div class="container-fluid pt-20 pb-10 bg-gradient-to-b from-primary/5 to-white relative overflow-hidden">
    <!-- Decorative background elements -->
    <div class="absolute top-0 right-0 -mr-20 -mt-20 w-96 h-96 rounded-full bg-primary/10 blur-3xl"></div>
    <div class="absolute bottom-0 left-0 -ml-20 -mb-20 w-80 h-80 rounded-full bg-blue-100 blur-3xl"></div>
    
    <div class="container relative z-10 text-center py-10">
        <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 mb-4 animate__animated animate__fadeInDown">Mengenal Kostify Lebih Dekat</h1>
        <p class="text-lg text-gray-600 max-w-2xl mx-auto animate__animated animate__fadeInUp">Platform tepercaya untuk menghubungkan pencari kos dengan pemilik properti, mewujudkan pengalaman menyewa yang aman, transparan, dan mudah.</p>
    </div>
</div>

<!-- Main About Section -->
<div class="container-fluid py-5">
    <div class="container">
        <div class="row g-5 align-items-center">
            <div class="col-lg-6 wow fadeInLeft" data-wow-delay="0.1s">
                <div class="relative">
                    <div class="absolute inset-0 bg-primary rounded-3xl translate-x-4 translate-y-4 opacity-20"></div>
                    <img class="img-fluid w-100 rounded-3xl shadow-xl relative z-10 object-cover"
                        src="{{ $settings->aboutImageUrl() }}"
                        style="height: 500px;">
                    <!-- Floating badge -->
                    <div class="absolute -bottom-6 -left-6 bg-white p-4 rounded-2xl shadow-xl z-20 border border-gray-100 flex items-center gap-3">
                        <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center text-green-500">
                            <i class="fa fa-shield-alt text-xl"></i>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 font-bold mb-0">Platform</p>
                            <h6 class="mb-0 font-extrabold text-gray-900">100% Aman</h6>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 wow fadeInRight" data-wow-delay="0.3s">
                <span class="inline-block py-1 px-3 rounded-full bg-primary/10 text-primary font-bold text-sm mb-3">Tentang Platform Kami</span>
                <h2 class="mb-4 text-3xl font-bold text-gray-900 leading-tight">{{ $settings->about_title ?? 'Revolusi Cara Anda Mencari Tempat Tinggal' }}</h2>
                <p class="mb-4 text-gray-600 text-lg leading-relaxed">
                    {{ $settings->about_description ?? 'Kostify adalah ekosistem digital inovatif yang dirancang khusus untuk memecahkan masalah pencarian dan pengelolaan kos serta kontrakan di Indonesia. Kami menjembatani kesenjangan antara pemilik properti dan penyewa melalui teknologi modern yang efisien dan transparan.' }}
                </p>
                
                <div class="bg-gray-50 p-6 rounded-2xl border border-gray-100 mb-6">
                    <h5 class="font-bold text-gray-900 mb-3">Mengapa Memilih Kami?</h5>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        @if(empty($settings->about_features))
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-primary/10 text-primary flex items-center justify-center flex-shrink-0"><i class="fa fa-check"></i></div>
                                <span class="text-gray-700 font-medium">Verifikasi Properti Ketat</span>
                            </div>
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-primary/10 text-primary flex items-center justify-center flex-shrink-0"><i class="fa fa-check"></i></div>
                                <span class="text-gray-700 font-medium">Pembayaran Cashless</span>
                            </div>
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-primary/10 text-primary flex items-center justify-center flex-shrink-0"><i class="fa fa-check"></i></div>
                                <span class="text-gray-700 font-medium">Sistem Komplain Terpadu</span>
                            </div>
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-primary/10 text-primary flex items-center justify-center flex-shrink-0"><i class="fa fa-check"></i></div>
                                <span class="text-gray-700 font-medium">Dukungan CS 24/7</span>
                            </div>
                        @else
                            @foreach($settings->about_features as $feature)
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-primary/10 text-primary flex items-center justify-center flex-shrink-0"><i class="fa fa-check"></i></div>
                                    <span class="text-gray-700 font-medium">{{ $feature }}</span>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
                
                <a class="btn btn-primary py-3 px-6 rounded-full shadow-lg hover:shadow-xl transition-all inline-flex items-center gap-2"
                    href="{{ route('rooms.index') }}">
                    Mulai Eksplorasi <i class="fa fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>
</div>

{{-- Visi & Misi --}}
<div class="container-fluid py-5 bg-white">
    <div class="container">
        <div class="text-center mx-auto mb-5 wow fadeInUp" data-wow-delay="0.1s" style="max-width: 600px;">
            <h2 class="mb-3 font-bold text-gray-900">Visi & Misi Kami</h2>
            <p class="text-gray-500">Membangun ekosistem sewa properti yang lebih baik untuk masa depan.</p>
        </div>
        <div class="row g-4">
            <div class="col-md-6 wow fadeInUp" data-wow-delay="0.1s">
                <div class="bg-gray-50 h-100 p-8 rounded-3xl border border-gray-100 hover:border-primary/30 transition-colors group">
                    <div class="w-16 h-16 bg-white rounded-2xl shadow-sm flex items-center justify-center text-primary text-2xl mb-5 group-hover:scale-110 transition-transform">
                        <i class="fa fa-eye"></i>
                    </div>
                    <h3 class="font-bold text-gray-900 mb-3 text-2xl">Visi</h3>
                    <p class="text-gray-600 leading-relaxed text-lg">Menjadi platform rujukan nomor satu di Asia Tenggara untuk manajemen dan pencarian properti sewa jangka panjang, yang memberdayakan penyewa dan pemilik properti melalui teknologi pintar.</p>
                </div>
            </div>
            <div class="col-md-6 wow fadeInUp" data-wow-delay="0.3s">
                <div class="bg-gray-50 h-100 p-8 rounded-3xl border border-gray-100 hover:border-primary/30 transition-colors group">
                    <div class="w-16 h-16 bg-white rounded-2xl shadow-sm flex items-center justify-center text-primary text-2xl mb-5 group-hover:scale-110 transition-transform">
                        <i class="fa fa-bullseye"></i>
                    </div>
                    <h3 class="font-bold text-gray-900 mb-3 text-2xl">Misi</h3>
                    <ul class="text-gray-600 leading-relaxed space-y-3">
                        <li class="flex items-start gap-3"><i class="fa fa-arrow-right text-primary mt-1"></i> Mendigitalisasi proses sewa-menyewa tradisional yang rumit.</li>
                        <li class="flex items-start gap-3"><i class="fa fa-arrow-right text-primary mt-1"></i> Menyediakan transparansi harga dan kondisi properti secara akurat.</li>
                        <li class="flex items-start gap-3"><i class="fa fa-arrow-right text-primary mt-1"></i> Memberikan jaminan keamanan transaksi finansial bagi kedua belah pihak.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Stats --}}
<div class="container-fluid bg-primary py-5 relative overflow-hidden">
    <!-- Abstract pattern background -->
    <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(circle at 2px 2px, white 1px, transparent 0); background-size: 32px 32px;"></div>
    
    <div class="container relative z-10 py-4">
        <div class="row g-4 text-center text-white">
            <div class="col-md-3 col-6 wow zoomIn" data-wow-delay="0.1s">
                <div class="p-4 rounded-2xl hover:bg-white/10 transition-colors">
                    <h1 class="display-4 fw-bold mb-2">{{ \App\Models\Property::where('status','active')->count() }}+</h1>
                    <p class="text-lg font-medium opacity-90 mb-0">Properti Aktif</p>
                </div>
            </div>
            <div class="col-md-3 col-6 wow zoomIn" data-wow-delay="0.3s">
                <div class="p-4 rounded-2xl hover:bg-white/10 transition-colors">
                    <h1 class="display-4 fw-bold mb-2">{{ \App\Models\Room::where('status','available')->count() }}+</h1>
                    <p class="text-lg font-medium opacity-90 mb-0">Kamar Tersedia</p>
                </div>
            </div>
            <div class="col-md-3 col-6 wow zoomIn" data-wow-delay="0.5s">
                <div class="p-4 rounded-2xl hover:bg-white/10 transition-colors">
                    <h1 class="display-4 fw-bold mb-2">{{ \App\Models\Tenant::count() }}+</h1>
                    <p class="text-lg font-medium opacity-90 mb-0">Tenant Terdaftar</p>
                </div>
            </div>
            <div class="col-md-3 col-6 wow zoomIn" data-wow-delay="0.7s">
                <div class="p-4 rounded-2xl hover:bg-white/10 transition-colors">
                    <h1 class="display-4 fw-bold mb-2">99%</h1>
                    <p class="text-lg font-medium opacity-90 mb-0">Tingkat Kepuasan</p>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Contact --}}
<div class="container-fluid py-5 my-10">
    <div class="container">
        <div class="text-center mx-auto mb-5 wow fadeInUp" data-wow-delay="0.1s" style="max-width: 600px;">
            <h2 class="mb-3 font-bold text-gray-900">Butuh Bantuan?</h2>
            <p class="text-gray-500">Tim kami siap membantu Anda kapan saja. Silakan hubungi kami melalui saluran berikut.</p>
        </div>
        <div class="row g-4 justify-content-center">
            <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.1s">
                <div class="bg-white rounded-3xl p-6 text-center h-100 shadow-lg border border-gray-100 hover:-translate-y-2 transition-transform duration-300">
                    <div class="w-20 h-20 bg-blue-50 rounded-full mx-auto flex items-center justify-center mb-4">
                        <i class="fa fa-phone-alt fa-2x text-primary"></i>
                    </div>
                    <h4 class="font-bold text-gray-900 mb-2">Telepon</h4>
                    <p class="text-lg font-medium text-primary mb-1">{{ $settings->phone ?? '(021) 12345678' }}</p>
                    <p class="text-gray-500 text-sm">Senin - Minggu, Jam 08.00 - 20.00</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.3s">
                <div class="bg-white rounded-3xl p-6 text-center h-100 shadow-lg border border-gray-100 hover:-translate-y-2 transition-transform duration-300">
                    <div class="w-20 h-20 bg-amber-50 rounded-full mx-auto flex items-center justify-center mb-4">
                        <i class="fa fa-envelope fa-2x text-amber-500"></i>
                    </div>
                    <h4 class="font-bold text-gray-900 mb-2">Email</h4>
                    <p class="text-lg font-medium text-primary mb-1">{{ $settings->email ?? 'hello@kostify.com' }}</p>
                    <p class="text-gray-500 text-sm">Respon maksimal dalam 1x24 jam</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.5s">
                <div class="bg-white rounded-3xl p-6 text-center h-100 shadow-lg border border-gray-100 hover:-translate-y-2 transition-transform duration-300">
                    <div class="w-20 h-20 bg-green-50 rounded-full mx-auto flex items-center justify-center mb-4">
                        <i class="fab fa-whatsapp fa-2x text-green-500"></i>
                    </div>
                    <h4 class="font-bold text-gray-900 mb-2">WhatsApp</h4>
                    <p class="text-lg font-medium text-primary mb-3">{{ $settings->whatsapp ?? '0812-3456-7890' }}</p>
                    <a href="https://wa.me/{{ preg_replace('/\D/', '', $settings->whatsapp ?? '081234567890') }}"
                        target="_blank" class="btn btn-success rounded-full px-5 py-2 inline-flex items-center gap-2 hover:shadow-lg transition-all">
                        <i class="fab fa-whatsapp"></i> Chat Sekarang
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection