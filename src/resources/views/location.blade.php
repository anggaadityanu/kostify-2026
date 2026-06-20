@extends('layouts.makaan')
@section('title', 'Lokasi Kami - Kostify')
@section('content')

@php
    $properties = \App\Models\Property::where('status','active')
        ->whereNotNull('latitude')
        ->get();
    $firstProperty = $properties->first();
@endphp

<div class="container-fluid py-5">
    <div class="container">
        <div class="text-center mx-auto mb-5" style="max-width: 600px;">
            <h1 class="mb-3">Lokasi Properti Kami</h1>
            <p class="text-muted">
                Klik properti di bawah untuk melihat lokasinya di peta
            </p>
        </div>

       
        @if($properties->count() > 0)
            <div class="row g-4 mb-5">
                @foreach($properties as $property)
                    <div class="col-md-6 col-lg-4">
                        <div class="property-item rounded overflow-hidden"
                            onclick="showOnMap({{ $property->latitude }}, {{ $property->longitude }})"
                            style="cursor: pointer; transition: transform 0.2s;">
                            <div class="p-4">
                                <h5 class="text-primary">{{ $property->name }}</h5>
                                <p class="mb-1">
                                    <i class="fa fa-map-marker-alt text-primary me-2"></i>
                                    {{ $property->address }}
                                </p>
                                <p class="text-muted mb-2">
                                    {{ $property->city }}, {{ $property->province }}
                                </p>
                                <div class="d-flex gap-2">
                                    <button type="button"
                                        onclick="event.stopPropagation(); showOnMap({{ $property->latitude }}, {{ $property->longitude }})"
                                        class="btn btn-outline-primary btn-sm">
                                        <i class="fa fa-map me-1"></i>Lihat di Peta
                                    </button>
                                    <a href="https://www.google.com/maps/dir/?api=1&destination={{ $property->latitude }},{{ $property->longitude }}"
                                        target="_blank"
                                        onclick="event.stopPropagation()"
                                        class="btn btn-primary btn-sm">
                                        <i class="fa fa-directions me-1"></i>Rute
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-5">
                <i class="fa fa-map-marker-alt fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">Belum ada properti dengan lokasi terdaftar</h5>
            </div>
        @endif

       
        <div id="map-section" class="rounded overflow-hidden" style="height: 450px;">
            <iframe id="map-frame"
                src="https://www.google.com/maps/embed/v1/place?key={{ config('services.google_maps.key') }}&q={{ $firstProperty?->latitude }},{{ $firstProperty?->longitude }}"
                width="100%" height="450" style="border:0;" allowfullscreen loading="lazy">
            </iframe>
        </div>
    </div>
</div>

<script>
function showOnMap(lat, lng) {
    const mapFrame = document.getElementById('map-frame');
    const apiKey = '{{ config('services.google_maps.key') }}';
    mapFrame.src = `https://www.google.com/maps/embed/v1/place?key=${apiKey}&q=${lat},${lng}`;
    document.getElementById('map-section').scrollIntoView({ behavior: 'smooth', block: 'center' });
}
</script>

@endsection