<div>
    <div class="container py-5" style="max-width: 700px;">
        <h2 class="fw-bold mb-4">Form Booking 📋</h2>

        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="card border-0 shadow-sm">
            {{-- Info Kamar --}}
            <div class="card-header bg-primary text-white py-3">
                <h6 class="mb-1">{{ $room->property->name }}</h6>
                <small>Kamar {{ $room->room_number }} · {{ ucfirst($room->type) }} ·
                    Rp {{ number_format($room->price_monthly, 0, ',', '.') }}/bulan
                </small>
            </div>

            <div class="card-body p-4">
                <form wire:submit="submit">

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Tanggal Masuk *</label>
                        <input type="date" wire:model="checkInDate"
                            class="form-control @error('checkInDate') is-invalid @enderror" />
                        @error('checkInDate')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Durasi Sewa *</label>
                        <select wire:model.live="durationMonths"
                            class="form-select @error('durationMonths') is-invalid @enderror">
                            <option value="1">1 Bulan</option>
                            <option value="3">3 Bulan</option>
                            <option value="6">6 Bulan</option>
                            <option value="12">12 Bulan</option>
                        </select>
                        @error('durationMonths')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">Catatan (opsional)</label>
                        <textarea wire:model="notes" rows="3"
                            placeholder="Kebutuhan khusus, pertanyaan, dll"
                            class="form-control"></textarea>
                    </div>

                    {{-- Ringkasan Harga --}}
                    <div class="bg-light rounded p-4 mb-4">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Harga/bulan</span>
                            <span class="fw-semibold">Rp {{ number_format($room->price_monthly, 0, ',', '.') }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <span class="text-muted">Durasi</span>
                            <span class="fw-semibold">{{ $durationMonths }} bulan</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between">
                            <span class="fw-bold">Total</span>
                            <span class="fw-bold text-primary fs-5">
                                Rp {{ number_format($totalPrice, 0, ',', '.') }}
                            </span>
                        </div>
                        <p class="text-muted small mt-3 mb-0">
                            <i class="fa fa-info-circle me-1"></i>
                            Pembayaran pertama akan jatuh tempo 3 hari setelah booking disetujui
                        </p>
                    </div>

                    <div class="d-flex gap-3">
                        <a href="{{ route('rooms.show', $room->id) }}"
                            class="btn btn-outline-secondary flex-fill py-3">
                            <i class="fa fa-arrow-left me-1"></i>Kembali
                        </a>
                        <button type="submit" class="btn btn-primary flex-fill py-3">
                            <span wire:loading.remove>
                                <i class="fa fa-check me-1"></i>Konfirmasi Booking
                            </span>
                            <span wire:loading>Memproses...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>