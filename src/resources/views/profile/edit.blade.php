@extends('layouts.tenant-portal')
@section('page-title', 'Pengaturan')
@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <h2 class="mb-4 fw-bold text-dark">Edit Profil </h2>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                     {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Info Profil -->
            <div class="card shadow-sm border-0 rounded-3 mb-4">
                <div class="card-header bg-primary text-white py-3">
                    <h5 class="mb-0"><i class="bi bi-person-circle me-2"></i>Informasi Akun</h5>
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('profile.update') }}">
                        @csrf
                        @method('PATCH')

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nama Lengkap</label>
                            <input type="text" name="name"
                                value="{{ old('name', $user->name) }}"
                                class="form-control @error('name') is-invalid @enderror"
                                placeholder="Masukkan nama lengkap">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Email</label>
                            <input type="email" name="email"
                                value="{{ old('email', $user->email) }}"
                                class="form-control @error('email') is-invalid @enderror"
                                placeholder="Masukkan email">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-save me-1"></i> Simpan Perubahan
                        </button>
                    </form>
                </div>
            </div>

            <!-- Ganti Password -->
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-header bg-warning text-dark py-3">
                    <h5 class="mb-0"><i class="bi bi-lock me-2"></i>Ganti Password</h5>
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('profile.password') }}">
                        @csrf
                        @method('PATCH')

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Password Lama</label>
                            <input type="password" name="current_password"
                                class="form-control @error('current_password') is-invalid @enderror"
                                placeholder="Masukkan password lama">
                            @error('current_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Password Baru</label>
                            <input type="password" name="password"
                                class="form-control @error('password') is-invalid @enderror"
                                placeholder="Minimal 8 karakter">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Konfirmasi Password Baru</label>
                            <input type="password" name="password_confirmation"
                                class="form-control"
                                placeholder="Ulangi password baru">
                        </div>

                        <button type="submit" class="btn btn-warning w-100">
                            <i class="bi bi-shield-lock me-1"></i> Ganti Password
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>
@endsection
