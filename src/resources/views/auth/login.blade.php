<x-guest-layout>
    @if (session('status'))
        <div class="alert alert-success mb-4">{{ session('status') }}</div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="mb-3">
            <label class="form-label">Email</label>
            <input id="email" type="email" name="email"
                value="{{ old('email') }}" required autofocus
                placeholder="contoh@gmail.com"
                class="form-control @error('email') is-invalid @enderror" />
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Password</label>
            <div class="input-group">
                <input id="password" type="password" name="password"
                    required placeholder="••••••••"
                    class="form-control @error('password') is-invalid @enderror" />
                <button type="button" class="btn btn-outline-secondary"
                    onclick="togglePassword()">
                    <i id="eye-icon" class="fa fa-eye"></i>
                </button>
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-3">
            <div class="form-check">
                <input type="checkbox" class="form-check-input" name="remember" id="remember">
                <label class="form-check-label" for="remember">Ingat Saya</label>
            </div>
            <a href="#" class="text-primary text-decoration-none">Lupa Password?</a>
        </div>

        <button type="submit" class="btn btn-primary w-100 py-3 mb-3">
            Masuk
        </button>

        {{-- Google Login --}}
        <a href="{{ route('auth.google') }}"
            class="btn btn-outline-secondary w-100 py-3 mb-3 d-flex align-items-center justify-content-center gap-2">
            <svg style="width:20px;height:20px" viewBox="0 0 24 24">
                <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
            </svg>
            Masuk dengan Google
        </a>

        <p class="text-center text-muted mb-0">
            Belum punya akun?
            <a href="{{ route('register') }}" class="text-primary">Daftar di sini</a>
        </p>
    </form>

    <script>
        function togglePassword() {
            const input = document.getElementById('password');
            const icon  = document.getElementById('eye-icon');
            if (input.type === 'password') {
                input.type = 'text';
                icon.className = 'fa fa-eye-slash';
            } else {
                input.type = 'password';
                icon.className = 'fa fa-eye';
            }
        }
    </script>
</x-guest-layout>