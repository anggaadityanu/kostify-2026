<div>
    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">Komplain Saya</h2>
            <p class="text-muted mb-0">Laporkan masalah di kamar Anda</p>
        </div>
        <button wire:click="$toggle('showForm')"
            class="btn btn-primary">
            <i class="fa fa-plus me-2"></i>Buat Komplain
        </button>
    </div>

    {{-- Flash --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4">
            ✅ {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Form Komplain --}}
    @if($showForm)
        <div class="card mb-4 border-0 shadow-sm">
            <div class="card-header bg-primary text-white">
                <h6 class="mb-0"><i class="fa fa-edit me-2"></i>Form Komplain Baru</h6>
            </div>
            <div class="card-body p-4">
                <form wire:submit="submit">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Kamar *</label>
                            <select wire:model="roomId"
                                class="form-select @error('roomId') is-invalid @enderror">
                                <option value="">Pilih kamar...</option>
                                @foreach($rooms as $room)
                                    <option value="{{ $room->id }}">
                                        {{ $room->property->name }} - {{ $room->room_number }}
                                    </option>
                                @endforeach
                            </select>
                            @error('roomId')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Kategori *</label>
                            <select wire:model="category"
                                class="form-select @error('category') is-invalid @enderror">
                                <option value="">Pilih kategori...</option>
                                <option value="electrical">⚡ Listrik</option>
                                <option value="plumbing">💧 Air/Pipa</option>
                                <option value="furniture">🪑 Furnitur</option>
                                <option value="cleanliness">🧹 Kebersihan</option>
                                <option value="security">🔒 Keamanan</option>
                                <option value="other">📝 Lainnya</option>
                            </select>
                            @error('category')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-8">
                            <label class="form-label fw-semibold">Judul *</label>
                            <input wire:model="title" type="text"
                                placeholder="Contoh: AC tidak dingin"
                                class="form-control @error('title') is-invalid @enderror" />
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Prioritas</label>
                            <select wire:model="priority" class="form-select">
                                <option value="low">🟢 Rendah</option>
                                <option value="medium">🟡 Sedang</option>
                                <option value="high">🔴 Tinggi</option>
                            </select>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold">Deskripsi *</label>
                            <textarea wire:model="description" rows="4"
                                placeholder="Jelaskan masalah secara detail..."
                                class="form-control @error('description') is-invalid @enderror">
                            </textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="d-flex gap-3 mt-4">
                        <button type="button" wire:click="$set('showForm', false)"
                            class="btn btn-outline-secondary flex-fill">
                            <i class="fa fa-times me-1"></i>Batal
                        </button>
                        <button type="submit" class="btn btn-primary flex-fill">
                            <span wire:loading.remove>
                                <i class="fa fa-paper-plane me-1"></i>Kirim Komplain
                            </span>
                            <span wire:loading>Mengirim...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    {{-- List Komplain --}}
    @forelse($complaints as $complaint)
        <div class="card mb-3 border-0 shadow-sm">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-start">
                    <div class="flex-grow-1">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <span class="badge bg-secondary">
                                {{ $complaint->ticket_number }}
                            </span>
                            <span class="badge {{ match($complaint->priority) {
                                'high'   => 'bg-danger',
                                'medium' => 'bg-warning text-dark',
                                'low'    => 'bg-success',
                            } }}">
                                {{ match($complaint->priority) {
                                    'high'   => '🔴 Tinggi',
                                    'medium' => '🟡 Sedang',
                                    'low'    => '🟢 Rendah',
                                } }}
                            </span>
                            <span class="badge {{ match($complaint->category) {
                                'electrical'  => 'bg-warning text-dark',
                                'plumbing'    => 'bg-info',
                                'furniture'   => 'bg-secondary',
                                'cleanliness' => 'bg-success',
                                'security'    => 'bg-danger',
                                default       => 'bg-dark',
                            } }}">
                                {{ match($complaint->category) {
                                    'electrical'  => '⚡ Listrik',
                                    'plumbing'    => '💧 Air/Pipa',
                                    'furniture'   => '🪑 Furnitur',
                                    'cleanliness' => '🧹 Kebersihan',
                                    'security'    => '🔒 Keamanan',
                                    default       => '📝 Lainnya',
                                } }}
                            </span>
                        </div>

                        <h6 class="fw-bold mb-1">{{ $complaint->title }}</h6>
                        <p class="text-muted small mb-2">
                            <i class="fa fa-home me-1"></i>
                            {{ $complaint->room->property->name }} -
                            Kamar {{ $complaint->room->room_number }}
                        </p>
                        <p class="text-muted mb-2">{{ $complaint->description }}</p>
                        <small class="text-muted">
                            <i class="fa fa-clock me-1"></i>
                            {{ $complaint->created_at->diffForHumans() }}
                        </small>
                    </div>

                    <div class="ms-3">
                        <span class="badge fs-6 {{ match($complaint->status) {
                            'open'        => 'bg-warning text-dark',
                            'in_progress' => 'bg-info',
                            'resolved'    => 'bg-success',
                            'closed'      => 'bg-secondary',
                        } }}">
                            {{ match($complaint->status) {
                                'open'        => '🔴 Open',
                                'in_progress' => '🔵 In Progress',
                                'resolved'    => '🟢 Resolved',
                                'closed'      => '⚫ Closed',
                            } }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="text-center py-5">
            <div class="mb-3" style="font-size: 4rem;">😊</div>
            <h5 class="text-muted">Belum ada komplain</h5>
            <p class="text-muted">Semoga semuanya berjalan lancar!</p>
        </div>
    @endforelse
</div>