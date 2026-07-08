@php
    $user = auth()->user();
    $profileUrl = \Joaopaulolndev\FilamentEditProfile\Pages\EditProfilePage::getUrl();
@endphp

<div class="kostify-sidebar-profile">
    <a href="{{ $profileUrl }}" class="kostify-sidebar-profile-link">
        <span class="kostify-sidebar-profile-avatar">
            {{ collect(explode(' ', $user->name))->map(fn ($part) => mb_substr($part, 0, 1))->take(2)->join('') }}
        </span>

        <span class="kostify-sidebar-profile-info">
            <span class="kostify-sidebar-profile-name">{{ $user->name }}</span>
            <span class="kostify-sidebar-profile-email">{{ $user->email }}</span>
        </span>
    </a>

    <form method="POST" action="{{ route('filament.admin.auth.logout') }}" class="kostify-sidebar-profile-logout">
        @csrf
        <button type="submit" title="Logout">
            <x-heroicon-o-arrow-left-on-rectangle class="kostify-sidebar-profile-logout-icon" />
        </button>
    </form>
</div>