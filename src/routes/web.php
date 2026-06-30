<?php

use App\Http\Controllers\Auth\GoogleAuthController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InvoiceController;

//Halaman Publik - Tidak Butuh Login
Route::get('/', function () {
    if (auth()->check()) {
        if (auth()->user()->hasRole(['super_admin', 'owner'])) {
            return redirect('/admin');
        }
        return redirect('/dashboard');
    }
    return view('home');
});

Route::get('/about', function () {
    return view('about');
})->name('about');

Route::get('/location', function () {
    return view('location');
})->name('location');

// Cari kamar bisa diakses tanpa login
Route::get('/rooms', \App\Livewire\Tenant\RoomList::class)
    ->name('rooms.index');
Route::get('/rooms/{id}', \App\Livewire\Tenant\RoomDetail::class)
    ->name('rooms.show');


//Google OAuth - Hanya untuk Guest
Route::middleware('guest')->group(function () {
    Route::get('/auth/google', [GoogleAuthController::class, 'redirect'])
        ->name('auth.google');
    Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback'])
        ->name('auth.google.callback');
});

//Portal Tenant - Butuh Login

Route::middleware(['auth'])->group(function () {

    // Dashboard - halaman pertama setelah login
    Route::get('/dashboard', \App\Livewire\Tenant\Dashboard::class)
        ->name('dashboard');

    // Lengkapi profil
    Route::get('/profile/complete', \App\Livewire\Tenant\CompleteProfile::class)
        ->name('profile.complete');

    // Booking - butuh login
    Route::get('/booking/{roomId}', \App\Livewire\Tenant\BookingForm::class)
        ->name('booking.form');

    // Pembayaran
    Route::get('/dashboard/payments', \App\Livewire\Tenant\PaymentList::class)
        ->name('payments.index');

    // Komplain
    Route::get('/dashboard/complaints', \App\Livewire\Tenant\ComplaintList::class)
        ->name('complaints.index');

    Route::get('/dashboard/complaints/{complaint}', \App\Livewire\Tenant\ComplaintDetail::class)
        ->name('complaints.show');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');
    Route::patch('/profile/password', [ProfileController::class, 'updatePassword'])
    ->name('profile.password');

    // Invoice download
    Route::get('/invoice/{paymentId}/download', [InvoiceController::class, 'download'])
        ->name('invoice.download');
});



require __DIR__.'/auth.php';