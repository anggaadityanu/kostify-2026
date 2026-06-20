<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\WelcomeNotification;
use App\Notifications\LoginNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    /**
     * Redirect user ke halaman consent Google
     */
    public function redirect(): RedirectResponse
    {
        return Socialite::driver('google')
            ->with(['prompt' => 'select_account'])
            ->redirect();
    }

    /**
     * Handle callback dari Google setelah user login
     * Logika:
     * 1. Ambil data user dari Google
     * 2. Cek apakah email sudah terdaftar
     * 3. Kalau ada → update google_id & login
     * 4. Kalau tidak ada → buat akun baru → assign tenant
     * 5. Redirect sesuai role
     */
    public function callback(): RedirectResponse
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            return redirect('/login')->withErrors([
                'email' => 'Login dengan Google gagal. Silakan coba lagi.',
            ]);
        }

        $user = User::where('email', $googleUser->getEmail())->first();

        if ($user) {
            $user->update([
                'google_id' => $googleUser->getId(),
                'avatar'    => $googleUser->getAvatar(),
            ]);
            $user->notify(new LoginNotification());
        } else {
            $user = User::create([
                'name'      => $googleUser->getName(),
                'email'     => $googleUser->getEmail(),
                'google_id' => $googleUser->getId(),
                'avatar'    => $googleUser->getAvatar(),
                'password'  => bcrypt(\Illuminate\Support\Str::random(16)),
                'is_active' => true,
            ]);
            $user->assignRole('tenant');
            $user->notify(new WelcomeNotification());
        }

        if (!$user->is_active) {
            return redirect('/login')->withErrors([
                'email' => 'Akun Anda telah dinonaktifkan.',
            ]);
        }

        Auth::login($user);

        if ($user->hasRole(['super_admin', 'owner'])) {
            return redirect('/admin');
        }

        return redirect('/dashboard');
    }
}