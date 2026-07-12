<!DOCTYPE html>
<html lang="id" x-data="{ sidebarOpen: false }">
<head>
    <meta charset="utf-8">
    <title>Kostify - @yield('title', 'Portal Penyewa')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ asset('makaan/img/favicon.ico') }}">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="{{ asset('makaan/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('makaan/css/style.css') }}" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    @stack('styles')
</head>
<body class="bg-slate-50 font-sans antialiased text-slate-800">

<div class="min-h-screen flex">

    {{-- Sidebar --}}
    <aside
        class="fixed inset-y-0 left-0 z-40 w-64 bg-white border-r border-slate-200 flex flex-col transition-transform duration-200 -translate-x-full lg:translate-x-0"
        :class="sidebarOpen && '!translate-x-0'"
    >
        <div class="h-16 flex items-center gap-2 px-6 border-b border-slate-200">
            <i class="bi bi-house-door-fill text-primary text-xl"></i>
            <span class="font-bold text-lg text-slate-900">Kostify</span>
        </div>

        <nav class="flex-1 overflow-y-auto py-4 px-3 space-y-1">
            @php
                $navItems = [
                    ['label' => 'Beranda', 'icon' => 'bi-house-door', 'route' => 'dashboard'],
                    ['label' => 'Cari Kamar', 'icon' => 'bi-search', 'route' => 'rooms.index'],
                    ['label' => 'Pembayaran', 'icon' => 'bi-credit-card', 'route' => 'payments.index'],
                    ['label' => 'Pengajuan Keluhan', 'icon' => 'bi-chat-left-text', 'route' => 'complaints.index'],
                    ['label' => 'Lokasi', 'icon' => 'bi-geo-alt', 'route' => 'location'],
                    ['label' => 'Profil Saya', 'icon' => 'bi-person', 'route' => 'profile.complete'],
                    ['label' => 'Pengaturan', 'icon' => 'bi-gear', 'route' => 'profile.edit'],
                    ['label' => 'Tentang Kami', 'icon' => 'bi-info-circle', 'route' => 'about'],
                ];
            @endphp

            @foreach($navItems as $item)
                @php $active = $item['route'] && request()->routeIs($item['route']); @endphp
                <a
                    href="{{ $item['route'] ? route($item['route']) : '#' }}"
                    @class([
                        'flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition',
                        'bg-primary-50 text-primary' => $active,
                        'text-slate-600 hover:bg-slate-50' => !$active,
                    ])
                >
                    <i class="bi {{ $item['icon'] }} text-base"></i>
                    {{ $item['label'] }}
                </a>
            @endforeach
        </nav>

        <div class="p-3 border-t border-slate-200">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-red-500 hover:bg-red-50 transition">
                    <i class="bi bi-box-arrow-left text-base"></i>
                    Keluar
                </button>
            </form>
        </div>
    </aside>

    {{-- Overlay mobile --}}
    <div x-show="sidebarOpen" x-cloak @click="sidebarOpen = false" class="fixed inset-0 bg-black/30 z-30 lg:hidden"></div>

    {{-- Main content --}}
    <div class="flex-1 flex flex-col lg:pl-64">

        {{-- Header --}}
        <header class="h-16 bg-white border-b border-slate-200 flex items-center justify-between px-4 sm:px-6 sticky top-0 z-20">
            <div class="flex items-center gap-3">
                <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden text-slate-500 text-xl">
                    <i class="bi bi-list"></i>
                </button>
                <h1 class="text-base sm:text-lg font-semibold text-slate-900">@yield('page-title', 'Beranda')</h1>
            </div>

            <div class="flex items-center gap-4">
                <livewire:notification-bell />

                <div class="flex items-center gap-2 pl-3 border-l border-slate-200" x-data="{ open: false }">
                    <button @click="open = !open" class="flex items-center gap-2">
                        <div class="w-9 h-9 rounded-full bg-slate-200 flex items-center justify-center text-slate-500">
                            <i class="bi bi-person-fill"></i>
                        </div>
                        <div class="hidden sm:block text-left leading-tight">
                            <p class="text-sm font-semibold text-slate-900">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-slate-500">Penyewa</p>
                        </div>
                        <i class="bi bi-chevron-down text-xs text-slate-400"></i>
                    </button>
                    <div x-show="open" x-cloak @click.outside="open = false"
                         class="absolute right-4 sm:right-6 top-14 w-44 bg-white border border-slate-200 rounded-lg shadow-lg py-1 z-30">
                        <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-slate-600 hover:bg-slate-50">Edit Profil</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-500 hover:bg-red-50">Logout</button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        {{-- Flash messages --}}
        <div class="px-4 sm:px-6 pt-4">
            @if(session('success'))
                <div class="mb-4 rounded-lg bg-green-50 text-green-700 text-sm px-4 py-3">{{ session('success') }}</div>
            @endif
            @if(session('warning'))
                <div class="mb-4 rounded-lg bg-amber-50 text-amber-700 text-sm px-4 py-3">{{ session('warning') }}</div>
            @endif
            @if(session('error'))
                <div class="mb-4 rounded-lg bg-red-50 text-red-700 text-sm px-4 py-3">{{ session('error') }}</div>
            @endif
        </div>

        <main class="flex-1 px-4 sm:px-6 py-6">
            {{ $slot ?? '' }}
            @yield('content')
        </main>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
@livewireScripts
@stack('scripts')
</body>
</html>
