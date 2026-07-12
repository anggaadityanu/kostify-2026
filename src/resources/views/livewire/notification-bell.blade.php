<div class="nav-item dropdown" wire:poll.20s>
    <a href="#" class="nav-link position-relative" data-bs-toggle="dropdown" style="text-transform: none;">
        <i class="fa fa-bell"></i>
        @if($unreadCount > 0)
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.6rem;">
                {{ $unreadCount > 9 ? '9+' : $unreadCount }}
            </span>
        @endif
    </a>
    <div class="dropdown-menu dropdown-menu-end rounded-3 shadow p-0" style="width: 320px; max-height: 400px; overflow-y: auto;">
        <div class="d-flex justify-content-between align-items-center px-3 py-2 border-bottom">
            <span class="fw-bold">Notifikasi</span>
            @if($unreadCount > 0)
                <button wire:click="markAllAsRead" class="btn btn-sm btn-link p-0 text-primary" style="font-size: 0.8rem;">
                    Tandai semua dibaca
                </button>
            @endif
        </div>

        @forelse($notifications as $notification)
            <a href="{{ $notification->data['url'] ?? '#' }}"
               wire:click="markAsRead('{{ $notification->id }}')"
               class="dropdown-item px-3 py-2 border-bottom {{ is_null($notification->read_at) ? 'bg-light' : '' }}"
               style="white-space: normal;">
                <div class="fw-semibold small">{{ $notification->data['title'] ?? 'Notifikasi' }}</div>
                <div class="text-muted small">{{ $notification->data['message'] ?? '' }}</div>
                <div class="text-muted" style="font-size: 0.7rem;">{{ $notification->created_at->diffForHumans() }}</div>
            </a>
        @empty
            <p class="text-center text-muted p-4 mb-0 small">Belum ada notifikasi.</p>
        @endforelse
    </div>
</div>
