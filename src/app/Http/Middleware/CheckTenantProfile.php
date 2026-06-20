<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckTenantProfile
{
    /**
     * Cek apakah tenant sudah lengkapi profil
     * Kalau belum → redirect ke halaman complete profile
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // Skip untuk non-tenant & halaman complete profile
        if (!$user || !$user->hasRole('tenant')) {
            return $next($request);
        }

        // Skip kalau sedang di halaman complete profile
        if ($request->routeIs('profile.complete')) {
            return $next($request);
        }

        // Cek apakah data tenant sudah ada
        if (!$user->tenant) {
            return redirect()->route('profile.complete')
                ->with('warning', 'Lengkapi data diri Anda terlebih dahulu!');
        }

        return $next($request);
    }
}