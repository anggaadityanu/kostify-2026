<?php

namespace App\Filament\Pages\Auth;

use Filament\Pages\Auth\Login as BaseLogin;

class Login extends BaseLogin
{
    /**
     * Setiap kali /admin/login diakses, langsung lempar
     * ke halaman login utama kita (route: login).
     */
    public function mount(): void
    {
        $this->redirect(route('login'));
    }
}