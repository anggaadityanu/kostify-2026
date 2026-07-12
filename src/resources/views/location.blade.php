@php
    $isTenant = auth()->check() && !auth()->user()->hasRole(['super_admin', 'owner']);
@endphp
@extends($isTenant ? 'layouts.tenant-portal' : 'layouts.makaan')
@section('title', 'Lokasi Kami - Kostify')
@section('page-title', 'Lokasi')
@section('content')

@php
    $properties = \App\Models\Property::where('status','active')
        ->whereNotNull('latitude')
        ->get();
    $firstProperty = $properties->first();
@endphp

<!-- Hero/Header Section -->
<div class="container-fluid pt-20 pb-10 bg-gradient-to-b from-primary/5 to-white relative overflow-hidden">
    <!-- Decorative background elements -->
    <div class="absolute top-0 right-0 -mr-20 -mt-20 w-96 h-96 rounded-full bg-primary/10 blur-3xl"></div>
    <div class="absolute bottom-0 left-0 -ml-20 -mb-20 w-80 h-80 rounded-full bg-blue-100 blur-3xl"></div>
    
    <div class="container relative z-10 text-center py-10">
        <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 mb-4 animate__animated animate__fadeInDown">Lokasi Properti Kami</h1>
        <p class="text-lg text-gray-600 max-w-2xl mx-auto animate__animated animate__fadeInUp">Temukan kos dan kontrakan impian Anda di berbagai lokasi strategis. Klik properti untuk melihat detail rute dan peta.</p>
    </div>
</div>

<div class="container-fluid py-5">
    <div class="container">
        @if($properties->count() > 0)
            <div class="row g-4 mb-5">
                @foreach($properties as $property)
                    <div class="col-md-6 col-lg-4 wow fadeInUp" data-wow-delay="{{ $loop->iteration * 0.1 }}s">
                        <div class="property-item bg-white rounded-3xl overflow-hidden shadow-sm border border-gray-100 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 h-100 flex flex-col group cursor-pointer"
                            onclick="showOnMap({{ $property->latitude }}, {{ $property->longitude }})">
                            <div class="p-5 flex-grow relative">
                                <!-- Marker Icon Background -->
                                <div class="absolute top-5 right-5 w-12 h-12 bg-primary/10 rounded-full flex items-center justify-center text-primary group-hover:scale-110 group-hover:bg-primary group-hover:text-white transition-all">
                                    <i class="fa fa-map-marker-alt text-xl"></i>
                                </div>
                                
                                <h5 class="text-gray-900 font-bold text-xl mb-3 pr-14">{{ $property->name }}</h5>
                                <p class="mb-2 text-gray-600 flex items-start gap-2">
                                    <i class="fa fa-map text-primary mt-1"></i>
                                    <span>{{ $property->address }}</span>
                                </p>
                                <p class="text-gray-500 mb-4 text-sm font-medium bg-gray-50 inline-block px-3 py-1 rounded-full border border-gray-200">
                                    {{ $property->city }}, {{ $property->province }}
                                </p>
                            </div>
                            <div class="p-4 bg-gray-50 border-t border-gray-100 flex gap-3 mt-auto">
                                <button type="button"
                                    onclick="event.stopPropagation(); showOnMap({{ $property->latitude }}, {{ $property->longitude }})"
                                    class="flex-1 btn btn-outline-primary rounded-xl py-2 px-3 flex items-center justify-center gap-2 hover:bg-primary hover:text-white transition-colors">
                                    <i class="fa fa-map-location-dot"></i> <span class="text-sm font-semibold">Lihat Peta</span>
                                </button>
                                <a href="https://www.google.com/maps/dir/?api=1&destination={{ $property->latitude }},{{ $property->longitude }}"
                                    target="_blank"
                                    onclick="event.stopPropagation()"
                                    class="flex-1 btn btn-primary rounded-xl py-2 px-3 flex items-center justify-center gap-2 shadow-sm hover:shadow-md transition-all">
                                    <i class="fa fa-directions"></i> <span class="text-sm font-semibold">Rute</span>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-16 bg-gray-50 rounded-3xl border border-gray-100">
                <div class="w-24 h-24 bg-white rounded-full mx-auto flex items-center justify-center mb-4 shadow-sm">
                    <i class="fa fa-map-marker-alt text-4xl text-gray-300"></i>
                </div>
                <h4 class="text-gray-700 font-bold mb-2">Belum Ada Lokasi</h4>
                <p class="text-gray-500">Belum ada properti dengan titik lokasi terdaftar di sistem.</p>
            </div>
        @endif

        @if($properties->count() > 0)
            {{-- Pemisah antara list properti dan peta --}}
            <div class="flex items-center gap-4 my-12 opacity-70">
                <div class="flex-grow h-px bg-gradient-to-r from-transparent via-gray-300 to-gray-300"></div>
                <span class="text-gray-500 font-bold px-4 py-2 bg-gray-50 rounded-full border border-gray-200 shadow-sm flex items-center gap-2">
                    <i class="fa fa-globe-asia text-primary"></i> Peta Interaktif
                </span>
                <div class="flex-grow h-px bg-gradient-to-l from-transparent via-gray-300 to-gray-300"></div>
            </div>

            <div id="map-section" class="rounded-3xl overflow-hidden shadow-2xl border-4 border-primary relative group" style="height: 500px;">
                <div class="absolute inset-0 bg-primary/5 flex items-center justify-center z-[-1]">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading map...</span>
                    </div>
                </div>
                <iframe id="map-frame"
                    src="https://www.google.com/maps/embed/v1/place?key={{ config('services.google_maps.key') }}&q={{ $firstProperty?->latitude }},{{ $firstProperty?->longitude }}"
                    width="100%" height="100%" style="border:0;" allowfullscreen loading="lazy" class="relative z-10 transition-opacity duration-300">
                </iframe>
                
                <!-- Overlay instructions that fade out on hover -->
                <div class="absolute top-4 left-1/2 transform -translate-x-1/2 bg-white/90 backdrop-blur-sm px-6 py-2 rounded-full shadow-lg border border-gray-200 z-20 text-sm font-semibold text-gray-700 pointer-events-none group-hover:opacity-0 transition-opacity duration-300 flex items-center gap-2">
                    <i class="fa fa-mouse-pointer text-primary"></i> Anda bisa menggeser dan zoom peta ini
                </div>
            </div>
        @endif
    </div>
</div>

<script>
function showOnMap(lat, lng) {
    const mapFrame = document.getElementById('map-frame');
    const apiKey = '{{ config('services.google_maps.key') }}';
    
    // Add visual feedback
    mapFrame.style.opacity = '0.5';
    
    setTimeout(() => {
        mapFrame.src = `https://www.google.com/maps/embed/v1/place?key=${apiKey}&q=${lat},${lng}`;
        
        mapFrame.onload = function() {
            mapFrame.style.opacity = '1';
        };
        
        document.getElementById('map-section').scrollIntoView({ behavior: 'smooth', block: 'center' });
    }, 200);
}
</script>

@endsection