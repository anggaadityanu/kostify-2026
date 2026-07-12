<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class NotificationBell extends Component
{
    public function markAsRead(string $id): void
    {
        Auth::user()->notifications()->find($id)?->markAsRead();
    }

    public function markAllAsRead(): void
    {
        Auth::user()->unreadNotifications->markAsRead();
    }

    public function render()
    {
        $notifications = Auth::check()
            ? Auth::user()->notifications()->latest()->limit(10)->get()
            : collect();

        $unreadCount = Auth::check()
            ? Auth::user()->unreadNotifications()->count()
            : 0;

        return view('livewire.notification-bell', [
            'notifications' => $notifications,
            'unreadCount'   => $unreadCount,
        ]);
    }
}
