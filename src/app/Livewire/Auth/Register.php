<?php

namespace App\Livewire\Auth;

use App\Models\User;
use App\Notifications\WelcomeNotification;
use App\Rules\ValidEmailDomain;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Livewire\Component;

class Register extends Component
{
    public string $name     = '';
    public string $email    = '';
    public string $password = '';
    public string $password_confirmation = '';

    // CAPTCHA
    public int $num1 = 0;
    public int $num2 = 0;
    public string $captchaAnswer = '';

    public function mount(): void
    {
        $this->generateCaptcha();
    }

    /**
     * Generate soal matematika baru (1-10)
     */
    public function generateCaptcha(): void
    {
        $this->num1 = rand(1, 10);
        $this->num2 = rand(1, 10);
        $this->captchaAnswer = '';
    }

    /**
     * Reset semua field form (dipanggil saat captcha salah)
     */
    public function resetForm(): void
    {
        $this->name = '';
        $this->email = '';
        $this->password = '';
        $this->password_confirmation = '';
        $this->generateCaptcha();
    }

    public function register(): void
    {
        // Cek jawaban captcha dulu sebelum validasi lain
        $correctAnswer = $this->num1 + $this->num2;

        if ((int) $this->captchaAnswer !== $correctAnswer) {
            $this->resetForm();
            $this->addError('captchaAnswer', 'Jawaban verifikasi salah! Form telah di-reset, silakan coba lagi.');
            return;
        }

        $this->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users,email', new ValidEmailDomain()],
            'password' => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()],
        ], [
            'name.required'      => 'Nama wajib diisi.',
            'email.required'     => 'Email wajib diisi.',
            'email.email'        => 'Format email tidak valid.',
            'email.unique'       => 'Email sudah terdaftar.',
            'password.required'  => 'Password wajib diisi.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'password.min'       => 'Password minimal 8 karakter.',
            'password.mixed'     => 'Password harus mengandung huruf besar dan huruf kecil.',
            'password.numbers'   => 'Password harus mengandung minimal satu angka.',
        ]);

        $user = User::create([
            'name'      => $this->name,
            'email'     => $this->email,
            'password'  => Hash::make($this->password),
            'is_active' => true,
        ]);

        $user->assignRole('tenant');
        event(new Registered($user));
        $user->notify(new WelcomeNotification());
        Auth::login($user);

        $this->redirect('/dashboard', navigate: false);
    }

    public function render()
    {
        return view('livewire.auth.register')->layout('components.guest-layout');
    }
}