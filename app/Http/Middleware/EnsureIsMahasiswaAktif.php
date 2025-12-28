<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureIsMahasiswaAktif
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // A user must have a 'mahasiswa' record and its status must be 'aktif'.
        if ($user->mahasiswa && $user->mahasiswa->status === 'aktif') {
            return $next($request);
        }

        // If the condition is not met, deny access.
        abort(403, 'Akses ini hanya untuk Mahasiswa Aktif.');
    }
}
