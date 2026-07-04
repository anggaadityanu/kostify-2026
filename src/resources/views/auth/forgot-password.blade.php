@extends('layouts.makaan')
@section('title', 'Lupa Password - Kostify')
@section('content')

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card border-0 shadow-sm rounded-4 p-4">
                <div class="text-center mb-4">
                    <h4 class="fw-bold">Lupa Password?</h4>
                    <p class="text-muted small">Masukkan email kamu, kami akan kirimkan link reset password.</p>
                </div>

                @if(session('status'))
                    <div class="alert alert-success">{{ session('status') }}</div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger">{{ $errors->first() }}</div>
                @endif

                <form method="POST" action="{{ route('password.email') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}"
                            class="form-control @error('email') is-invalid @enderror"
                            placeholder="emailkamu@gmail.com" required autofocus>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary w-100 py-2">
                        Kirim Link Reset Password
                    </button>
                </form>

                <div class="text-center mt-3">
                    <a href="{{ route('login') }}" class="text-muted small">
                        Kembali ke halaman login
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection