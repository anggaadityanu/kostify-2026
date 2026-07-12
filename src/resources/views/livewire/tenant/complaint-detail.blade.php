@section("page-title", "Detail Keluhan")

<div>
    <div class="mb-4">
        <a href="{{ route('complaints.index') }}" class="text-muted small">
            <i class="fa fa-arrow-left me-1"></i>Kembali ke Komplain Saya
        </a>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-4">
            <div class="d-flex align-items-center gap-2 mb-2">
                <span class="badge bg-secondary">{{ $complaint->ticket_number }}</span>
                <span class="badge {{ match($complaint->status) {
                    'open'        => 'bg-warning text-dark',
                    'in_progress' => 'bg-info',
                    'resolved'    => 'bg-success',
                    'closed'      => 'bg-secondary',
                } }}">
                    {{ match($complaint->status) {
                        'open'        => 'Open',
                        'in_progress' => 'In Progress',
                        'resolved'    => 'Resolved',
                        'closed'      => 'Closed',
                    } }}
                </span>
            </div>
            <h5 class="fw-bold mb-1">{{ $complaint->title }}</h5>
            <p class="text-muted small mb-2">
                <i class="fa fa-home me-1"></i>
                {{ $complaint->room->property->name }} - Kamar {{ $complaint->room->room_number }}
            </p>
            <p class="mb-0">{{ $complaint->description }}</p>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white">
            <h6 class="fw-bold mb-0">Percakapan dengan Admin</h6>
        </div>
        <div class="card-body p-4">
            <livewire:complaint-chat :complaint="$complaint" :key="'chat-'.$complaint->id" />
        </div>
    </div>
</div>