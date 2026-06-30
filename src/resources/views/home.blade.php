@extends('layouts.makaan')

@section('title', 'Kostify - Temukan Kos & Kontrakan Impian Anda')

@section('content')

@php($settings = \App\Models\Setting::current())

    <!-- Header/Hero -->
    <div class="container-fluid header bg-white p-0">
        <div class="row g-0 align-items-center flex-column-reverse flex-md-row">
            <div class="col-md-6 p-5 mt-lg-5">
                <h1 class="display-5 animated fadeIn mb-4">
                    {{ $settings->home_hero_title }}
                </h1>
                <p class="animated fadeIn mb-4 pb-2">
                    {{ $settings->home_hero_subtitle }}
                </p>
                <a href="{{ route('rooms.index') }}" class="btn btn-primary py-3 px-5 me-3 animated fadeIn">
                    Cari Kamar Sekarang
                </a>
            </div>
            <div class="col-md-6 animated fadeIn">
                <div class="owl-carousel header-carousel">
                    @foreach($settings->heroCarouselUrls() as $imageUrl)
                        <div class="owl-carousel-item">
                            <img class="img-fluid" src="{{ $imageUrl }}"
                                alt="" style="width:100%; height:600px; object-fit:cover;">
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Search Bar -->
    <div class="container-fluid bg-primary mb-5 wow fadeIn" data-wow-delay="0.1s" style="padding: 35px;">
        <div class="container">
            <form action="{{ route('rooms.index') }}" method="GET">
                <div class="row g-2">
                    <div class="col-md-10">
                        <div class="row g-2">
                            <div class="col-md-4">
                                <input type="text" name="search" class="form-control border-0 py-3"
                                    placeholder="Cari nama kos, kota...">
                            </div>
                            <div class="col-md-4">
                                <select name="type" class="form-select border-0 py-3">
                                    <option value="">Semua Tipe</option>
                                    <option value="kos_putra">Kos Putra</option>
                                    <option value="kos_putri">Kos Putri</option>
                                    <option value="kos_campur">Kos Campur</option>
                                    <option value="kontrakan">Kontrakan</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <select name="city" class="form-select border-0 py-3">
                                    <option value="">Semua Kota</option>
                                    @foreach(\App\Models\Property::where('status','active')->whereNotNull('city')->distinct()->pluck('city') as $city)
                                        <option value="{{ $city }}">{{ $city }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-dark border-0 w-100 py-3">
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
                <h1 class="mb-3">Tipe Properti</h1>
                <p>Temukan kos atau kontrakan sesuai kebutuhan Anda</p>
            </div>
            <div class="row g-4">
                <div class="col-lg-3 col-sm-6 wow fadeInUp" data-wow-delay="0.1s">
                    <a class="cat-item d-block bg-light text-center rounded p-3"
                        href="{{ route('rooms.index') }}?type=kos_putra">
                        <div class="rounded p-4">
                            <div class="icon mb-3">
                                <img class="img-fluid" src="{{ asset('makaan/img/icon-apartment.png') }}" alt="">
                            </div>
                            <h6>Kos Putra</h6>
                            <span>{{ \App\Models\Property::where('type','kos_putra')->where('status','active')->count() }} Properti</span>
                        </div>
                    </a>
                </div>
                <div class="col-lg-3 col-sm-6 wow fadeInUp" data-wow-delay="0.3s">
                    <a class="cat-item d-block bg-light text-center rounded p-3"
                        href="{{ route('rooms.index') }}?type=kos_putri">
                        <div class="rounded p-4">
                            <div class="icon mb-3">
                                <img class="img-fluid" src="{{ asset('makaan/img/icon-villa.png') }}" alt="">
                            </div>
                            <h6>Kos Putri</h6>
                            <span>{{ \App\Models\Property::where('type','kos_putri')->where('status','active')->count() }} Properti</span>
                        </div>
                    </a>
                </div>
                <div class="col-lg-3 col-sm-6 wow fadeInUp" data-wow-delay="0.5s">
                    <a class="cat-item d-block bg-light text-center rounded p-3"
                        href="{{ route('rooms.index') }}?type=kos_campur">
                        <div class="rounded p-4">
                            <div class="icon mb-3">
                                <img class="img-fluid" src="{{ asset('makaan/img/icon-house.png') }}" alt="">
                            </div>
                            <h6>Kos Campur</h6>
                            <span>{{ \App\Models\Property::where('type','kos_campur')->where('status','active')->count() }} Properti</span>
                        </div>
                    </a>
                </div>
                <div class="col-lg-3 col-sm-6 wow fadeInUp" data-wow-delay="0.7s">
                    <a class="cat-item d-block bg-light text-center rounded p-3"
                        href="{{ route('rooms.index') }}?type=kontrakan">
                        <div class="rounded p-4">
                            <div class="icon mb-3">
                                <img class="img-fluid" src="{{ asset('makaan/img/icon-housing.png') }}" alt="">
                            </div>
                            <h6>Kontrakan</h6>
                            <span>{{ \App\Models\Property::where('type','kontrakan')->where('status','active')->count() }} Properti</span>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats -->
    <div class="container-fluid bg-primary py-5 wow fadeIn" data-wow-delay="0.1s">
        <div class="container">
            <div class="row g-4 text-center text-white">
                <div class="col-lg-3 col-sm-6">
                    <h1 class="display-4 fw-bold">{{ \App\Models\Property::where('status','active')->count() }}+</h1>
                    <p class="mb-0 opacity-75">Properti Aktif</p>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <h1 class="display-4 fw-bold">{{ \App\Models\Room::where('status','available')->count() }}+</h1>
                    <p class="mb-0 opacity-75">Kamar Tersedia</p>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <h1 class="display-4 fw-bold">{{ \App\Models\Tenant::count() }}+</h1>
                    <p class="mb-0 opacity-75">Tenant Terdaftar</p>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <h1 class="display-4 fw-bold">{{ \App\Models\Booking::where('status','active')->count() }}+</h1>
                    <p class="mb-0 opacity-75">Booking Aktif</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Kamar Terbaru -->
    <div class="container-fluid py-5">
        <div class="container">
            <div class="row g-0 gx-5 align-items-end">
                <div class="col-lg-6">
                    <div class="text-start mx-auto mb-5 wow slideInLeft" data-wow-delay="0.1s">
                        <h1 class="mb-3">Kamar Terbaru</h1>
                        <p>Kamar kos & kontrakan terbaru yang tersedia untuk Anda.</p>
                    </div>
                </div>
                <div class="col-lg-6 text-start text-lg-end wow slideInRight mb-5">
                    <a href="{{ route('rooms.index') }}" class="btn btn-outline-primary">
                        Lihat Semua <i class="fa fa-arrow-right ms-2"></i>
                    </a>
                </div>
            </div>
            <div class="row g-4">
                @foreach(\App\Models\Room::with('property')->where('status','available')->latest()->limit(6)->get() as $room)
                    <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.1s">
                        <div class="property-item rounded overflow-hidden">
                            <div class="position-relative overflow-hidden">
                                <a href="{{ route('rooms.show', $room->id) }}">
                                    <img class="img-fluid" style="height: 220px; width: 100%; object-fit: cover;"
                                        src="{{ $room->coverPhotoUrl() }}" alt="{{ $room->property->name }}">
                                </a>
                                <div class="bg-primary rounded text-white position-absolute start-0 top-0 m-4 py-1 px-3">
                                    Disewa
                                </div>
                                <div class="bg-white rounded-top text-primary position-absolute start-0 bottom-0 mx-4 pt-1 px-3">
                                    {{ match($room->property->type) {
                                        'kos_putra'  => 'Kos Putra',
                                        'kos_putri'  => 'Kos Putri',
                                        'kos_campur' => 'Kos Campur',
                                        'kontrakan'  => 'Kontrakan',
                                    } }}
                                </div>
                            </div>
                            <div class="p-4 pb-0">
                                <h5 class="text-primary mb-3">
                                    Rp {{ number_format($room->price_monthly, 0, ',', '.') }}/bulan
                                </h5>
                                <a class="d-block h5 mb-2" href="{{ route('rooms.show', $room->id) }}">
                                    {{ $room->property->name }} - Kamar {{ $room->room_number }}
                                </a>
                                <p>
                                    <i class="fa fa-map-marker-alt text-primary me-2"></i>
                                    {{ $room->property->city }}
                                </p>
                            </div>
                            <div class="d-flex border-top">
                                <small class="flex-fill text-center border-end py-2">
                                    <i class="fa fa-home text-primary me-2"></i>
                                    {{ ucfirst($room->type) }}
                                </small>
                                <small class="flex-fill text-center border-end py-2">
                                    <i class="fa fa-user text-primary me-2"></i>
                                    {{ $room->capacity }} orang
                                </small>
                                <small class="flex-fill text-center py-2">
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
    <div class="container-fluid py-5">
        <div class="container">
            <div class="bg-light rounded p-3">
                <div class="bg-white rounded p-4" style="border: 1px dashed rgba(0, 185, 142, .3)">
                    <div class="row g-5 align-items-center">
                        <div class="col-lg-6 wow fadeIn" data-wow-delay="0.1s">
                            <img class="img-fluid rounded w-100" src="{{ $settings->ctaImageUrl() }}" alt="">
                        </div>
                        <div class="col-lg-6 wow fadeIn" data-wow-delay="0.5s">
                            <div class="mb-4">
                                <h1 class="mb-3">{{ $settings->home_cta_title }}</h1>
                                <p>{{ $settings->home_cta_description }}</p>
                            </div>
                            @auth
                                <a href="{{ route('rooms.index') }}" class="btn btn-primary py-3 px-4 me-2">
                                    <i class="fa fa-search me-2"></i>Cari Kamar
                                </a>
                            @else
                                <a href="{{ route('register') }}" class="btn btn-primary py-3 px-4 me-2">
                                    <i class="fa fa-user me-2"></i>Daftar Gratis
                                </a>
                                <a href="{{ route('login') }}" class="btn btn-dark py-3 px-4">
                                    <i class="fa fa-sign-in-alt me-2"></i>Masuk
                                </a>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection