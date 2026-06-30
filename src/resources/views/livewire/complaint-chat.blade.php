<div wire:poll.5s="$refresh">
    <div id="chat-box" style="max-height: 400px; overflow-y: auto; padding: 1rem; background: #f8f9fa; border: 1px solid #dee2e6; border-radius: 8px; margin-bottom: 1rem;">
        @forelse($messages as $msg)
            @php($isMine = $msg->user_id === auth()->id())
            <div style="display: flex; margin-bottom: 0.75rem; justify-content: {{ $isMine ? 'flex-end' : 'flex-start' }};">
                <div style="max-width: 75%; padding: 0.75rem 1rem; border-radius: 10px;
                    background: {{ $isMine ? '#4F46E5' : '#ffffff' }};
                    color: {{ $isMine ? '#ffffff' : '#1f2937' }};
                    border: {{ $isMine ? 'none' : '1px solid #dee2e6' }};">
                    <p style="margin: 0 0 4px 0; font-size: 0.8rem; font-weight: bold; opacity: 0.85;">
                        {{ $msg->user->name }}
                        @if($msg->user->hasRole(['super_admin', 'owner']))
                            <span style="background:#6b7280; color:#fff; font-size:0.65rem; padding:1px 6px; border-radius:10px; margin-left:4px;">Admin</span>
                        @endif
                    </p>
                    <p style="margin: 0 0 4px 0;">{{ $msg->message }}</p>
                    <p style="margin: 0; font-size: 0.7rem; opacity: 0.7;">
                        {{ $msg->created_at->format('d M Y, H:i') }}
                    </p>
                </div>
            </div>
        @empty
            <p style="text-align: center; color: #6b7280; padding: 2rem 0; margin: 0;">
                Belum ada percakapan. Mulai chat di bawah.
            </p>
        @endforelse
    </div>

    @if(!in_array($complaint->status, ['resolved', 'closed']))
        <form wire:submit="sendMessage" style="display: flex; gap: 8px;">
            <input type="text" wire:model="newMessage" placeholder="Tulis pesan..."
                style="flex: 1; padding: 10px 14px; border: 1px solid #d1d5db; border-radius: 8px; outline: none;">
            <button type="submit" style="background:#4F46E5; color:#fff; border:none; padding:10px 20px; border-radius:8px; cursor:pointer;">
                Kirim
            </button>
        </form>
        @error('newMessage')
            <small style="color: #dc2626;">{{ $message }}</small>
        @enderror
    @else
        <div style="background:#e5e7eb; color:#374151; padding:12px 16px; border-radius:8px;">
            Komplain ini sudah {{ $complaint->status === 'resolved' ? 'diselesaikan' : 'ditutup' }}, percakapan dikunci.
        </div>
    @endif
</div>