@extends('layouts.makaan')
@section('title', 'Reset Password - Kostify')
@section('content')

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card border-0 shadow-sm rounded-4 p-4">
                <div class="text-center mb-4">
                    <h4 class="fw-bold">Buat Password Baru</h4>
                    <p class="text-muted small">Masukkan password baru kamu di bawah ini.</p>
                </div>

                @if($errors->any())
                    <div class="alert alert-danger">{{ $errors->first() }}</div>
                @endif

                <form method="POST" action="{{ route('password.update') }}">
                    @csrf
                    <input type="hidden" name="token" value="{{ $request->route('token') }}">

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Email</label>
                        <input type="email" name="email" value="{{ old('email', $request->email) }}"
                            class="form-control @error('email') is-invalid @enderror"
                            required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Password Baru</label>
                        <input type="password" name="password"
                            class="form-control @error('password') is-invalid @enderror"
                            placeholder="Minimal 8 karakter" required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation"
                            class="form-control" placeholder="Ulangi password baru" required>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 py-2">
                        Reset Password
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection