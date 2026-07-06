<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\WelcomeNotification;
use App\Rules\ValidEmailDomain;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:'.User::class, new ValidEmailDomain()],
            'password' => ['required', 'confirmed', Rules\Password::min(8)->mixedCase()->numbers()],
            'captcha'  => ['required', 'numeric', function ($attribute, $value, $fail) {
                if ((int) $value !== session('captcha_answer')) {
                    $fail('Jawaban verifikasi salah.');
                }
            }],
        ], [
            'name.required'      => 'Nama wajib diisi.',
            'email.required'     => 'Email wajib diisi.',
            'email.email'        => 'Format email tidak valid. Contoh: nama@gmail.com',
            'email.unique'       => 'Email sudah terdaftar.',
            'password.required'  => 'Password wajib diisi.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'password.min'       => 'Password minimal 8 karakter.',
            'password.mixed'     => 'Password harus mengandung huruf besar dan huruf kecil.',
            'password.numbers'   => 'Password harus mengandung minimal satu angka.',
            'captcha.required'   => 'Mohon isi hasil perhitungan.',
            'captcha.numeric'    => 'Jawaban harus berupa angka.',
        ]);

        $user = User::create([
            'name'      => $request->name,
            'email'     => $request->email,
            'password'  => Hash::make($request->password),
            'is_active' => true,
        ]);

        $user->assignRole('tenant');
        event(new Registered($user));
        $user->notify(new WelcomeNotification());
        Auth::login($user);

        return redirect('/dashboard');
    }
}
