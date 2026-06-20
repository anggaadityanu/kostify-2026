<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Kostify - @yield('title', 'Sistem Manajemen Kos & Kontrakan')</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="Kostify, kos, kontrakan, sewa kamar" name="keywords">
    <meta content="Platform terbaik untuk mencari dan mengelola kos & kontrakan" name="description">

    <!-- Favicon -->
    <link href="{{ asset('makaan/img/favicon.ico') }}" rel="icon">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600&family=Inter:wght@700;800&display=swap" rel="stylesheet">

    <!-- Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries -->
    <link href="{{ asset('makaan/lib/animate/animate.min.css') }}" rel="stylesheet">
    <link href="{{ asset('makaan/lib/owlcarousel/assets/owl.carousel.min.css') }}" rel="stylesheet">

    <!-- Bootstrap & Template CSS -->
    <link href="{{ asset('makaan/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('makaan/css/style.css') }}" rel="stylesheet">

    @livewireStyles
    @stack('styles')
</head>
<body>
<div class="container-fluid bg-white p-0">

    <!-- Spinner -->
    <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
        <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </div>

    <!-- Navbar -->
    <div class="container-fluid nav-bar bg-transparent">
        <nav class="navbar navbar-expand-lg bg-white navbar-light py-0 px-4">
            <a href="{{ url('/') }}" class="navbar-brand d-flex align-items-center text-center">
                <div class="icon p-2 me-2">
                    <img class="img-fluid" src="{{ asset('makaan/img/icon-deal.png') }}" alt="Icon" style="width: 30px; height: 30px;">
                </div>
                <h1 class="m-0 text-primary">Kostify</h1>
            </a>
            <button type="button" class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarCollapse">
                <div class="navbar-nav ms-auto">
                    @guest
                        {{-- SEBELUM LOGIN --}}
                        <a href="{{ url('/') }}"
                            class="nav-item nav-link {{ request()->is('/') ? 'active' : '' }}">
                            Home
                        </a>
                        <a href="{{ route('rooms.index') }}"
                            class="nav-item nav-link {{ request()->routeIs('rooms.*') ? 'active' : '' }}">
                            Cari Kamar
                        </a>
                        <a href="{{ route('about') }}"
                            class="nav-item nav-link {{ request()->routeIs('about') ? 'active' : '' }}">
                            Tentang Kami
                        </a>
                        <a href="{{ route('location') }}"
                            class="nav-item nav-link {{ request()->routeIs('location') ? 'active' : '' }}">
                            Lokasi
                        </a>
                        <a href="{{ route('login') }}" class="nav-item nav-link">Masuk</a>
                        <a href="{{ route('register') }}"
                            class="btn btn-primary px-4 d-none d-lg-flex align-items-center justify-content-center ms-2">
                            Daftar Gratis
                        </a>
                    @else
                    {{-- SETELAH LOGIN --}}
                    @if(Auth::user()->hasRole(['super_admin','owner']))
                        <a href="/admin" class="nav-item nav-link">Admin Panel</a>
                    @else
                        <a href="{{ route('dashboard') }}"
                            class="nav-item nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                            Dashboard
                        </a>
                        <a href="{{ route('rooms.index') }}"
                            class="nav-item nav-link {{ request()->routeIs('rooms.*') ? 'active' : '' }}">
                            Cari Kamar
                        </a>
                        <a href="{{ route('payments.index') }}"
                            class="nav-item nav-link {{ request()->routeIs('payments.*') ? 'active' : '' }}">
                            Tagihan
                        </a>
                        <a href="{{ route('complaints.index') }}"
                            class="nav-item nav-link {{ request()->routeIs('complaints.*') ? 'active' : '' }}">
                            Komplain
                        </a>
                        <a href="{{ route('location') }}"
                            class="nav-item nav-link {{ request()->routeIs('location') ? 'active' : '' }}">
                            Lokasi
                        </a>
                        <a href="{{ route('about') }}"
                            class="nav-item nav-link {{ request()->routeIs('about') ? 'active' : '' }}">
                            Tentang Kami
                        </a>
                    @endif

                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                            {{ Auth::user()?->name }}
                        </a>
                        <div class="dropdown-menu rounded-0 m-0">
                            <a href="{{ route('profile.edit') }}" class="dropdown-item">Edit Profil</a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger">
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                @endguest
                </div>
            </div>
        </nav>
    </div>
    <!-- Navbar End -->

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show m-3" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('warning'))
        <div class="alert alert-warning alert-dismissible fade show m-3" role="alert">
            {{ session('warning') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show m-3" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Content -->
    {{ $slot ?? '' }}
    @yield('content')

    <!-- Footer -->
    <div class="container-fluid bg-dark text-white-50 footer pt-5 mt-5 wow fadeIn" data-wow-delay="0.1s">
        <div class="container py-5">
            <div class="row g-5">
                <div class="col-lg-3 col-md-6">
                    <h5 class="text-white mb-4">Kostify</h5>
                    <p class="mb-2"><i class="fa fa-map-marker-alt me-3"></i>Indonesia</p>
                    <p class="mb-2"><i class="fa fa-phone-alt me-3"></i>+62 xxx xxxx xxxx</p>
                    <p class="mb-2"><i class="fa fa-envelope me-3"></i>info@kostify.com</p>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h5 class="text-white mb-4">Quick Links</h5>
                    <a class="btn btn-link text-white-50" href="{{ url('/') }}">Home</a>
                    <a class="btn btn-link text-white-50" href="{{ route('rooms.index') }}">Cari Kamar</a>
                    @auth
                        <a class="btn btn-link text-white-50" href="{{ route('dashboard') }}">Dashboard</a>
                    @else
                        <a class="btn btn-link text-white-50" href="{{ route('login') }}">Masuk</a>
                        <a class="btn btn-link text-white-50" href="{{ route('register') }}">Daftar</a>
                    @endauth
                </div>
                <div class="col-lg-3 col-md-6">
                    <h5 class="text-white mb-4">Tipe Properti</h5>
                    <a class="btn btn-link text-white-50" href="{{ route('rooms.index') }}?type=kos_putra">Kos Putra</a>
                    <a class="btn btn-link text-white-50" href="{{ route('rooms.index') }}?type=kos_putri">Kos Putri</a>
                    <a class="btn btn-link text-white-50" href="{{ route('rooms.index') }}?type=kos_campur">Kos Campur</a>
                    <a class="btn btn-link text-white-50" href="{{ route('rooms.index') }}?type=kontrakan">Kontrakan</a>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h5 class="text-white mb-4">Newsletter</h5>
                    <p>Dapatkan info kamar terbaru langsung di email Anda.</p>
                    <div class="position-relative mx-auto" style="max-width: 400px;">
                        <input class="form-control bg-transparent w-100 py-3 ps-4 pe-5" type="email" placeholder="Email Anda">
                        <button type="button" class="btn btn-primary py-2 position-absolute top-0 end-0 mt-2 me-2">Daftar</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="copyright">
                <div class="row">
                    <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                        &copy; {{ date('Y') }} <a class="border-bottom" href="#">Kostify</a>. All Rights Reserved.
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Footer End -->

    <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top">
        <i class="bi bi-arrow-up"></i>
    </a>
</div>

<!-- JavaScript Libraries -->
<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('makaan/lib/wow/wow.min.js') }}"></script>
<script src="{{ asset('makaan/lib/easing/easing.min.js') }}"></script>
<script src="{{ asset('makaan/lib/waypoints/waypoints.min.js') }}"></script>
<script src="{{ asset('makaan/lib/owlcarousel/owl.carousel.min.js') }}"></script>
<script src="{{ asset('makaan/js/main.js') }}"></script>

@livewireScripts
@stack('scripts')
</body>
</html>