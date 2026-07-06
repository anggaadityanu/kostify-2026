<div>
    <!-- Search Header -->
    <div class="container-fluid bg-primary mb-5 py-4" style="border-radius: 0 0 2rem 2rem; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);">
        <div class="container">
            <div class="row g-2">
                <div class="col-md-10">
                    <div class="row g-2">
                        <div class="col-md-4">
                            <input wire:model.live.debounce.300ms="search"
                                type="text" class="form-control border-0 py-3 rounded-lg shadow-sm focus:ring-2 focus:ring-primary/50"
                                placeholder="Cari nama kos, kota...">
                        </div>
                        <div class="col-md-4">
                            <select wire:model.live="type" class="form-select border-0 py-3 rounded-lg shadow-sm focus:ring-2 focus:ring-primary/50">
                                <option value="">Semua Tipe</option>
                                <option value="kos_putra">Kos Putra</option>
                                <option value="kos_putri">Kos Putri</option>
                                <option value="kos_campur">Kos Campur</option>
                                <option value="kontrakan">Kontrakan</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <select wire:model.live="city" class="form-select border-0 py-3 rounded-lg shadow-sm focus:ring-2 focus:ring-primary/50">
                                <option value="">Semua Kota</option>
                                @foreach($cities as $c)
                                    <option value="{{ $c }}">{{ $c }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <button class="btn btn-dark border-0 w-100 py-3 rounded-lg shadow-sm hover:bg-gray-800 transition-colors">
                        <i class="fa fa-search me-2"></i>Cari
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <!-- Filter Harga & Sort -->
        <div class="row g-3 mb-5 bg-white p-4 rounded-xl shadow-sm border border-gray-100">
            <div class="col-md-3">
                <div class="input-group">
                    <span class="input-group-text bg-gray-50 border-gray-200 text-gray-500 rounded-l-lg border-r-0">Rp</span>
                    <input wire:model.live.debounce.500ms="priceMin"
                        type="number" class="form-control border-gray-200 rounded-r-lg focus:ring-primary/50 focus:border-primary"
                        placeholder="Harga min">
                </div>
            </div>
            <div class="col-md-3">
                <div class="input-group">
                    <span class="input-group-text bg-gray-50 border-gray-200 text-gray-500 rounded-l-lg border-r-0">Rp</span>
                    <input wire:model.live.debounce.500ms="priceMax"
                        type="number" class="form-control border-gray-200 rounded-r-lg focus:ring-primary/50 focus:border-primary"
                        placeholder="Harga max">
                </div>
            </div>
            <div class="col-md-3">
                <select wire:model.live="sortBy" class="form-select border-gray-200 rounded-lg focus:ring-primary/50 focus:border-primary">
                    <option value="price_monthly">Urutkan: Harga</option>
                    <option value="created_at">Urutkan: Terbaru</option>
                </select>
            </div>
            <div class="col-md-3">
                <select wire:model.live="sortDir" class="form-select border-gray-200 rounded-lg focus:ring-primary/50 focus:border-primary">
                    <option value="asc">Termurah</option>
                    <option value="desc">Termahal</option>
                </select>
            </div>
        </div>

        <!-- Loading -->
        <div wire:loading class="text-center py-5 w-full">
            <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-3 text-gray-500 font-medium">Mencari kamar terbaik untukmu...</p>
        </div>

        <!-- Room Grid -->
        <div wire:loading.remove>
            @if($rooms->count() > 0)
                <div class="flex justify-between items-center mb-4">
                    <p class="text-gray-500 font-medium mb-0">
                        Menampilkan <span class="text-gray-900 font-bold">{{ $rooms->count() }}</span> dari <span class="text-gray-900 font-bold">{{ $rooms->total() }}</span> kamar
                    </p>
                </div>
                
                <div class="row g-4">
                    @foreach($rooms as $room)
                        <div class="col-lg-4 col-md-6 wow fadeInUp flex">
                            <div class="property-item rounded-2xl overflow-hidden shadow-lg bg-white hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1 flex flex-col w-full">
                                <div class="position-relative overflow-hidden group">
                                    <a href="{{ route('rooms.show', $room->id) }}">
                                        <img class="img-fluid w-full h-60 object-cover group-hover:scale-110 transition-transform duration-500"
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
                                        <i class="fa fa-home text-primary me-2"></i>{{ ucfirst($room->type) }}
                                    </small>
                                    <small class="flex-fill text-center border-e border-gray-200 py-3 text-gray-600">
                                        <i class="fa fa-user text-primary me-2"></i>{{ $room->capacity }} org
                                    </small>
                                    <small class="flex-fill text-center py-3 text-gray-600">
                                        <i class="fa fa-ruler-combined text-primary me-2"></i>{{ $room->size ?? '-' }} m²
                                    </small>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-8 flex justify-center">
                    {{ $rooms->links() }}
                </div>
            @else
                <div class="text-center py-12 bg-gray-50 rounded-2xl border border-gray-100">
                    <img src="{{ asset('makaan/img/icon-house.png') }}" style="width: 100px; opacity: 0.2;" class="mx-auto mb-4">
                    <h4 class="text-gray-700 font-bold mb-2">Oops! Tidak ada kamar yang sesuai.</h4>
                    <p class="text-gray-500 mb-6">Coba sesuaikan filter pencarianmu untuk melihat lebih banyak pilihan.</p>
                    <button wire:click="$set('search', ''); $set('type', ''); $set('city', ''); $set('priceMin', null); $set('priceMax', null);" class="btn btn-primary rounded-full px-6 py-2.5 shadow-md hover:shadow-lg transition-all">
                        <i class="fa fa-sync-alt me-2"></i> Reset Filter
                    </button>
                </div>
            @endif
        </div>
    </div>
</div>