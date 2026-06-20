<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Kostify - Portal Tenant</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <!-- Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Bootstrap & Template CSS -->
    <link href="{{ asset('makaan/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('makaan/css/style.css') }}" rel="stylesheet">

    @livewireStyles
</head>
<body>
<div class="container-fluid bg-white p-0">

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
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                            {{ Auth::user()?->name ?? 'User' }}
                        </a>
                        <div class="dropdown-menu rounded-0 m-0">
                            <a href="{{ route('profile.edit') }}" class="dropdown-item">Edit Profil</a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger">Logout</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
    </div>

    <!-- Flash Messages -->
    @if(session('warning'))
        <div class="alert alert-warning alert-dismissible fade show m-3">
            {{ session('warning') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show m-3">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Content -->
    <div class="container py-5">
        {{ $slot }}
    </div>

    <!-- Footer simple -->
    <div class="container-fluid bg-dark text-white-50 py-4">
        <div class="container text-center">
            <p class="mb-0">&copy; {{ date('Y') }} Kostify. All Rights Reserved.</p>
        </div>
    </div>

    <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top">
        <i class="bi bi-arrow-up"></i>
    </a>
</div>

<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('makaan/js/main.js') }}"></script>

@livewireScripts
</body>
</html>