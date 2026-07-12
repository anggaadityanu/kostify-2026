<div class="py-4 py-md-5">
    <div class="container" style="max-width: 640px;">
        <h1 class="fw-bold text-gray-900 mb-2">Perpanjang Sewa</h1>
        <p class="text-gray-500 mb-4">
            {{ $booking->room->property->name }} &bull; Kamar {{ $booking->room->room_number }}
        </p>

        <div class="tenant-card mb-4">
            <p class="tenant-card-label mb-1">Sisa Sewa Saat Ini</p>
            <h5 class="mb-0">{{ $booking->duration_months }} bulan (s/d {{ $booking->check_out_date?->format('d M Y') ?? '-' }})</h5>
        </div>

        <form wire:submit="submit" class="tenant-panel p-4">
            <div class="mb-3">
                <label class="form-label fw-semibold">Tambah Durasi (bulan)</label>
                <input type="number" wire:model.live="extraMonths" min="1" max="24" class="form-control">
                @error('extraMonths') <span class="text-danger small">{{ $message }}</span> @enderror
            </div>

            <div class="d-flex justify-content-between align-items-center mb-4">
                <span class="text-gray-500">Total Tambahan Biaya</span>
                <span class="fs-4 fw-bold text-primary">Rp {{ number_format($totalPrice, 0, ',', '.') }}</span>
            </div>

            <button type="submit" class="btn btn-primary w-100 py-3 rounded-pill fw-semibold" wire:loading.attr="disabled">
                <span wire:loading.remove>Perpanjang Sekarang</span>
                <span wire:loading>Memproses...</span>
            </button>
        </form>
    </div>
</div>
