<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Notifications\LoginNotification;
use App\Rules\ValidEmailDomain;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Tampilkan halaman login
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle login request
     * Logika: validasi → cek credentials → cek role → redirect
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email'    => ['required', 'email', new ValidEmailDomain()],
            'password' => ['required'],
        ], [
            'email.required'    => 'Email wajib diisi.',
            'email.email'       => 'Format email tidak valid. Contoh: nama@gmail.com',
            'password.required' => 'Password wajib diisi.',
        ]);

        if (!Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            return back()->withErrors([
                'email' => 'Email atau password salah.',
            ]);
        }

        $request->session()->regenerate();
        $user = Auth::user();

        $request->session()->put(
            'password_hash_' . Auth::getDefaultDriver(),
            $user->getAuthPassword()
        );

        if (!$user->is_active) {
            Auth::logout();
            return back()->withErrors([
                'email' => 'Akun Anda telah dinonaktifkan.',
            ]);
        }

        $user->notify(new LoginNotification());

        // Super Admin & Owner → Admin Panel
        if ($user->hasRole(['super_admin', 'owner'])) {
            return redirect('/admin');
        }

        // Tenant → selalu ke dashboard
        return redirect('/dashboard');
    }

    /**
     * Handle logout
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}