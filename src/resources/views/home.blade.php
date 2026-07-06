@extends('layouts.makaan')

@section('title', 'Kostify - Temukan Kos & Kontrakan Impian Anda')

@section('content')

@php($settings = \App\Models\Setting::current())

    <!-- Header/Hero -->
    <div class="container-fluid header bg-white p-0 relative pt-12">
        <div class="row g-0 align-items-center flex-column-reverse flex-md-row">
            <div class="col-md-6 p-5 mt-lg-5 relative z-10">
                <h1 class="display-5 animated fadeIn mb-4 font-bold text-gray-900">
                    {{ $settings->home_hero_title }}
                </h1>
                <p class="animated fadeIn mb-4 pb-2 text-lg text-gray-600">
                    {{ $settings->home_hero_subtitle }}
                </p>
                <a href="{{ route('rooms.index') }}" class="btn btn-primary py-3 px-5 me-3 animated fadeIn rounded-full shadow-lg hover:shadow-xl transition-all">
                    Cari Kamar Sekarang
                </a>
            </div>
            <div class="col-md-6 animated fadeIn relative">
                <div class="owl-carousel header-carousel">
                    @foreach($settings->heroCarouselUrls() as $imageUrl)
                        <div class="owl-carousel-item relative">
                            <img class="img-fluid" src="{{ $imageUrl }}"
                                alt="" style="width:100%; height:700px; object-fit:cover; border-bottom-left-radius: 4rem;">
                            <div class="absolute inset-0 bg-gradient-to-r from-white via-transparent to-transparent"></div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Search Bar -->
    <div class="container-fluid bg-primary mb-5 wow fadeIn relative z-20 -mt-16 mx-auto" data-wow-delay="0.1s" style="padding: 35px; border-radius: 1rem; max-width: 90%; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);">
        <div class="container">
            <form action="{{ route('rooms.index') }}" method="GET">
                <div class="row g-2">
                    <div class="col-md-10">
                        <div class="row g-2">
                            <div class="col-md-4">
                                <input type="text" name="search" class="form-control border-0 py-3 rounded-lg shadow-sm focus:ring-2 focus:ring-primary/50"
                                    placeholder="Cari nama kos, kota...">
                            </div>
                            <div class="col-md-4">
                                <select name="type" class="form-select border-0 py-3 rounded-lg shadow-sm focus:ring-2 focus:ring-primary/50">
                                    <option value="">Semua Tipe</option>
                                    <option value="kos_putra">Kos Putra</option>
                                    <option value="kos_putri">Kos Putri</option>
                                    <option value="kos_campur">Kos Campur</option>
                                    <option value="kontrakan">Kontrakan</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <select name="city" class="form-select border-0 py-3 rounded-lg shadow-sm focus:ring-2 focus:ring-primary/50">
                                    <option value="">Semua Kota</option>
                                    @foreach(\App\Models\Property::where('status','active')->whereNotNull('city')->distinct()->pluck('city') as $city)
                                        <option value="{{ $city }}">{{ $city }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-dark border-0 w-100 py-3 rounded-lg hover:bg-gray-800 transition-colors shadow-sm">
                            <i class="fa fa-search me-2"></i>Cari
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Tipe Properti -->
    <div class="container-fluid py-5">
        <div class="container">
            <div class="text-center mx-auto mb-5 wow fadeInUp" data-wow-delay="0.1s" style="max-width: 600px;">
                <h1 class="mb-3 font-bold text-gray-900">Tipe Properti</h1>
                <p class="text-gray-600">Temukan kos atau kontrakan sesuai kebutuhan Anda</p>
            </div>
            <div class="row g-4">
                <div class="col-6 col-lg-3 col-sm-6 wow fadeInUp" data-wow-delay="0.1s">
                    <a class="cat-item d-block bg-white text-center rounded-2xl p-3 shadow-md hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2 border border-gray-100 group"
                        href="{{ route('rooms.index') }}?type=kos_putra">
                        <div class="rounded-xl p-4 bg-blue-50/50 group-hover:bg-primary/5 transition-colors">
                            <div class="icon mb-3 bg-white w-16 h-16 mx-auto rounded-full flex items-center justify-center shadow-sm">
                                <img class="img-fluid w-8 h-8" src="{{ asset('makaan/img/icon-apartment.png') }}" alt="">
                            </div>
                            <h6 class="font-bold text-gray-800">Kos Putra</h6>
                            <span class="text-sm text-gray-500">{{ \App\Models\Property::where('type','kos_putra')->where('status','active')->count() }} Properti</span>
                        </div>
                    </a>
                </div>
                <div class="col-6 col-lg-3 col-sm-6 wow fadeInUp" data-wow-delay="0.3s">
                    <a class="cat-item d-block bg-white text-center rounded-2xl p-3 shadow-md hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2 border border-gray-100 group"
                        href="{{ route('rooms.index') }}?type=kos_putri">
                        <div class="rounded-xl p-4 bg-pink-50/50 group-hover:bg-primary/5 transition-colors">
                            <div class="icon mb-3 bg-white w-16 h-16 mx-auto rounded-full flex items-center justify-center shadow-sm">
                                <img class="img-fluid w-8 h-8" src="{{ asset('makaan/img/icon-villa.png') }}" alt="">
                            </div>
                            <h6 class="font-bold text-gray-800">Kos Putri</h6>
                            <span class="text-sm text-gray-500">{{ \App\Models\Property::where('type','kos_putri')->where('status','active')->count() }} Properti</span>
                        </div>
                    </a>
                </div>
                <div class="col-6 col-lg-3 col-sm-6 wow fadeInUp" data-wow-delay="0.5s">
                    <a class="cat-item d-block bg-white text-center rounded-2xl p-3 shadow-md hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2 border border-gray-100 group"
                        href="{{ route('rooms.index') }}?type=kos_campur">
                        <div class="rounded-xl p-4 bg-purple-50/50 group-hover:bg-primary/5 transition-colors">
                            <div class="icon mb-3 bg-white w-16 h-16 mx-auto rounded-full flex items-center justify-center shadow-sm">
                                <img class="img-fluid w-8 h-8" src="{{ asset('makaan/img/icon-house.png') }}" alt="">
                            </div>
                            <h6 class="font-bold text-gray-800">Kos Campur</h6>
                            <span class="text-sm text-gray-500">{{ \App\Models\Property::where('type','kos_campur')->where('status','active')->count() }} Properti</span>
                        </div>
                    </a>
                </div>
                <div class="col-6 col-lg-3 col-sm-6 wow fadeInUp" data-wow-delay="0.7s">
                    <a class="cat-item d-block bg-white text-center rounded-2xl p-3 shadow-md hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2 border border-gray-100 group"
                        href="{{ route('rooms.index') }}?type=kontrakan">
                        <div class="rounded-xl p-4 bg-green-50/50 group-hover:bg-primary/5 transition-colors">
                            <div class="icon mb-3 bg-white w-16 h-16 mx-auto rounded-full flex items-center justify-center shadow-sm">
                                <img class="img-fluid w-8 h-8" src="{{ asset('makaan/img/icon-housing.png') }}" alt="">
                            </div>
                            <h6 class="font-bold text-gray-800">Kontrakan</h6>
                            <span class="text-sm text-gray-500">{{ \App\Models\Property::where('type','kontrakan')->where('status','active')->count() }} Properti</span>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats -->
    <div class="container-fluid bg-primary py-5 wow fadeIn relative overflow-hidden" data-wow-delay="0.1s">
        <!-- Abstract pattern background -->
        <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(circle at 2px 2px, white 1px, transparent 0); background-size: 32px 32px;"></div>
        
        <div class="container relative z-10">
            <div class="row g-4 text-center text-white">
                <div class="col-6 col-lg-3 col-sm-6 p-3 p-md-4 rounded-2xl hover:bg-white/10 transition-colors">
                    <div class="inline-block p-4 rounded-full bg-white/20 mb-3 backdrop-blur-sm">
                        <i class="fa fa-building fa-2x"></i>
                    </div>
                    <h1 class="display-4 fw-bold mb-2">{{ \App\Models\Property::where('status','active')->count() }}+</h1>
                    <p class="mb-0 text-lg font-medium opacity-90">Properti Aktif</p>
                </div>
                <div class="col-6 col-lg-3 col-sm-6 p-3 p-md-4 rounded-2xl hover:bg-white/10 transition-colors">
                    <div class="inline-block p-4 rounded-full bg-white/20 mb-3 backdrop-blur-sm">
                        <i class="fa fa-door-open fa-2x"></i>
                    </div>
                    <h1 class="display-4 fw-bold mb-2">{{ \App\Models\Room::where('status','available')->count() }}+</h1>
                    <p class="mb-0 text-lg font-medium opacity-90">Kamar Tersedia</p>
                </div>
                <div class="col-6 col-lg-3 col-sm-6 p-3 p-md-4 rounded-2xl hover:bg-white/10 transition-colors">
                    <div class="inline-block p-4 rounded-full bg-white/20 mb-3 backdrop-blur-sm">
                        <i class="fa fa-users fa-2x"></i>
                    </div>
                    <h1 class="display-4 fw-bold mb-2">{{ \App\Models\Tenant::count() }}+</h1>
                    <p class="mb-0 text-lg font-medium opacity-90">Tenant Terdaftar</p>
                </div>
                <div class="col-6 col-lg-3 col-sm-6 p-3 p-md-4 rounded-2xl hover:bg-white/10 transition-colors">
                    <div class="inline-block p-4 rounded-full bg-white/20 mb-3 backdrop-blur-sm">
                        <i class="fa fa-check-circle fa-2x"></i>
                    </div>
                    <h1 class="display-4 fw-bold mb-2">{{ \App\Models\Booking::where('status','active')->count() }}+</h1>
                    <p class="mb-0 text-lg font-medium opacity-90">Booking Aktif</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Kamar Terbaru -->
    <div class="container-fluid py-5 bg-gray-50">
        <div class="container">
            <div class="row g-0 gx-5 align-items-end">
                <div class="col-lg-6">
                    <div class="text-start mx-auto mb-5 wow slideInLeft" data-wow-delay="0.1s">
                        <h1 class="mb-3 font-bold text-gray-900">Kamar Terbaru</h1>
                        <p class="text-gray-600">Kamar kos & kontrakan terbaru yang tersedia untuk Anda.</p>
                    </div>
                </div>
                <div class="col-lg-6 text-start text-lg-end wow slideInRight mb-5">
                    <a href="{{ route('rooms.index') }}" class="btn btn-outline-primary rounded-full px-4 py-2 hover:bg-primary hover:text-white transition-colors">
                        Lihat Semua <i class="fa fa-arrow-right ms-2"></i>
                    </a>
                </div>
            </div>
            <div class="row g-4">
                @foreach(\App\Models\Room::with('property')->where('status','available')->latest()->limit(6)->get() as $room)
                    <div class="col-lg-4 col-md-6 wow fadeInUp flex" data-wow-delay="0.1s">
                        <div class="property-item rounded-2xl overflow-hidden shadow-lg bg-white hover:shadow-2xl transition-shadow duration-300 transform hover:-translate-y-1 flex flex-col w-full">
                            <div class="position-relative overflow-hidden group">
                                <a href="{{ route('rooms.show', $room->id) }}">
                                    <img class="img-fluid w-full h-64 object-cover group-hover:scale-110 transition-transform duration-500"
                                        src="{{ $room->coverPhotoUrl() }}" alt="{{ $room->property->name }}">
                                </a>
                                <div class="bg-primary rounded-full text-white position-absolute start-0 top-0 m-4 py-1.5 px-4 text-sm font-semibold shadow-md">
                                    Disewa
                                </div>
                                <div class="bg-white rounded-t-xl text-primary position-absolute start-0 bottom-0 mx-4 pt-2 px-4 font-bold shadow-sm">
                                    {{ match($room->property->type) {
                                        'kos_putra'  => 'Kos Putra',
                                        'kos_putri'  => 'Kos Putri',
                                        'kos_campur' => 'Kos Campur',
                                        'kontrakan'  => 'Kontrakan',
                                    } }}
                                </div>
                            </div>
                            <div class="p-5 pb-0 flex-grow">
                                <h5 class="text-primary mb-3 font-bold text-xl">
                                    Rp {{ number_format($room->price_monthly, 0, ',', '.') }}<span class="text-sm text-gray-500 font-normal">/bulan</span>
                                </h5>
                                <a class="d-block h5 mb-2 font-semibold text-gray-800 hover:text-primary transition-colors line-clamp-2" href="{{ route('rooms.show', $room->id) }}">
                                    {{ $room->property->name }} - Kamar {{ $room->room_number }}
                                </a>
                                <p class="text-gray-500 mb-4">
                                    <i class="fa fa-map-marker-alt text-primary me-2"></i>
                                    {{ $room->property->city }}
                                </p>
                            </div>
                            <div class="d-flex border-t border-gray-100 bg-gray-50 rounded-b-2xl mt-auto">
                                <small class="flex-fill text-center border-e border-gray-200 py-3 text-gray-600">
                                    <i class="fa fa-home text-primary me-2"></i>
                                    {{ ucfirst($room->type) }}
                                </small>
                                <small class="flex-fill text-center border-e border-gray-200 py-3 text-gray-600">
                                    <i class="fa fa-user text-primary me-2"></i>
                                    {{ $room->capacity }} orang
                                </small>
                                <small class="flex-fill text-center py-3 text-gray-600">
                                    <i class="fa fa-ruler-combined text-primary me-2"></i>
                                    {{ $room->size ?? '-' }} m²
                                </small>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- CTA -->
    <div class="container-fluid py-5 my-10">
        <div class="container">
            <div class="bg-gradient-to-br from-primary/10 to-primary/5 rounded-3xl p-2 shadow-lg">
                <div class="bg-white rounded-[1.5rem] p-6 lg:p-12 relative overflow-hidden">
                    <!-- Decorative blob -->
                    <div class="absolute -top-24 -right-24 w-64 h-64 bg-primary/10 rounded-full blur-3xl"></div>
                    <div class="absolute -bottom-24 -left-24 w-64 h-64 bg-secondary/10 rounded-full blur-3xl"></div>
                    
                    <div class="row g-5 align-items-center relative z-10">
                        <div class="col-lg-6 wow fadeIn" data-wow-delay="0.1s">
                            <div class="relative">
                                <img class="img-fluid rounded-2xl w-100 shadow-xl" src="{{ $settings->ctaImageUrl() }}" alt="">
                                <div class="absolute -bottom-6 -right-6 w-24 h-24 bg-primary/20 rounded-full blur-xl -z-10"></div>
                            </div>
                        </div>
                        <div class="col-lg-6 wow fadeIn" data-wow-delay="0.5s">
                            <div class="mb-5">
                                <h1 class="mb-4 font-bold text-gray-900 text-4xl leading-tight">{{ $settings->home_cta_title }}</h1>
                                <p class="text-gray-600 text-lg">{{ $settings->home_cta_description }}</p>
                            </div>
                            <div class="flex flex-wrap gap-3">
                                @auth
                                    <a href="{{ route('rooms.index') }}" class="btn btn-primary py-3 px-5 rounded-full shadow-md hover:shadow-lg transition-all">
                                        <i class="fa fa-search me-2"></i>Cari Kamar
                                    </a>
                                @else
                                    <a href="{{ route('register') }}" class="btn btn-primary py-3 px-5 rounded-full shadow-md hover:shadow-lg transition-all">
                                        <i class="fa fa-user me-2"></i>Daftar Gratis
                                    </a>
                                    <a href="{{ route('login') }}" class="btn btn-dark py-3 px-5 rounded-full shadow-md hover:shadow-lg transition-all hover:bg-gray-800">
                                        <i class="fa fa-sign-in-alt me-2"></i>Masuk
                                    </a>
                                @endauth
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection