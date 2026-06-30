<div>
    <!-- Search Header -->
    <div class="container-fluid bg-primary mb-5" style="padding: 35px;">
        <div class="container">
            <div class="row g-2">
                <div class="col-md-10">
                    <div class="row g-2">
                        <div class="col-md-4">
                            <input wire:model.live.debounce.300ms="search"
                                type="text" class="form-control border-0 py-3"
                                placeholder="Cari nama kos, kota...">
                        </div>
                        <div class="col-md-4">
                            <select wire:model.live="type" class="form-select border-0 py-3">
                                <option value="">Semua Tipe</option>
                                <option value="kos_putra">Kos Putra</option>
                                <option value="kos_putri">Kos Putri</option>
                                <option value="kos_campur">Kos Campur</option>
                                <option value="kontrakan">Kontrakan</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <select wire:model.live="city" class="form-select border-0 py-3">
                                <option value="">Semua Kota</option>
                                @foreach($cities as $c)
                                    <option value="{{ $c }}">{{ $c }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <button class="btn btn-dark border-0 w-100 py-3">
                        <i class="fa fa-search me-2"></i>Cari
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <!-- Filter Harga & Sort -->
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <input wire:model.live.debounce.500ms="priceMin"
                    type="number" class="form-control"
                    placeholder="Harga min (Rp)">
            </div>
            <div class="col-md-3">
                <input wire:model.live.debounce.500ms="priceMax"
                    type="number" class="form-control"
                    placeholder="Harga max (Rp)">
            </div>
            <div class="col-md-3">
                <select wire:model.live="sortBy" class="form-select">
                    <option value="price_monthly">Urutkan: Harga</option>
                    <option value="created_at">Urutkan: Terbaru</option>
                </select>
            </div>
            <div class="col-md-3">
                <select wire:model.live="sortDir" class="form-select">
                    <option value="asc">Termurah</option>
                    <option value="desc">Termahal</option>
                </select>
            </div>
        </div>

        <!-- Loading -->
        <div wire:loading class="text-center py-5">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>

        <!-- Room Grid -->
        <div wire:loading.remove>
            @if($rooms->count() > 0)
                <p class="text-muted mb-4">
                    Menampilkan {{ $rooms->count() }} dari {{ $rooms->total() }} kamar
                </p>
                <div class="row g-4">
                    @foreach($rooms as $room)
                        <div class="col-lg-4 col-md-6 wow fadeInUp">
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
                                        <i class="fa fa-home text-primary me-2"></i>{{ ucfirst($room->type) }}
                                    </small>
                                    <small class="flex-fill text-center border-end py-2">
                                        <i class="fa fa-user text-primary me-2"></i>{{ $room->capacity }} orang
                                    </small>
                                    <small class="flex-fill text-center py-2">
                                        <i class="fa fa-ruler-combined text-primary me-2"></i>{{ $room->size ?? '-' }} m²
                                    </small>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-5">
                    {{ $rooms->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <img src="{{ asset('makaan/img/icon-house.png') }}" style="width: 80px; opacity: 0.3;" class="mb-3">
                    <h5 class="text-muted">Tidak ada kamar yang sesuai filter</h5>
                    <button wire:click="$set('search', '')" class="btn btn-outline-primary mt-2">
                        Reset Filter
                    </button>
                </div>
            @endif
        </div>
    </div>
</div>