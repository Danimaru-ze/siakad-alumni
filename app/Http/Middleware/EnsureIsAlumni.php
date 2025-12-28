<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureIsAlumni
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

        $isGraduatedStudent = $user->mahasiswa && $user->mahasiswa->status === 'lulus';
        $isManualAlumni = !$user->mahasiswa && $user->hasRole('alumni');

        if ($isGraduatedStudent || $isManualAlumni) {
            return $next($request);
        }

        abort(403, 'Akses ini hanya untuk Alumni.');
    }
}
