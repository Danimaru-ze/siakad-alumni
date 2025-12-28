<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                $user = Auth::user();

                // New redirection logic with priority on mahasiswa status
                if ($user->mahasiswa) {
                    $redirectRoute = match ($user->mahasiswa->status) {
                        'lulus' => 'alumni.dashboard',
                        'aktif' => 'mahasiswa.dashboard',
                        default => 'mahasiswa.dashboard',
                    };
                } else {
                    // Fallback to role-based redirection for non-mahasiswa users
                    $redirectRoute = match ($user->role) {
                        'dosen' => 'dosen.dashboard',
                        'superadmin', 'admin_fakultas' => 'admin.dashboard',
                        'hrd' => 'hrd.dashboard',
                        'alumni' => 'alumni.dashboard', 
                        default => 'login',
                    };
                }

                return redirect(route($redirectRoute));
            }
        }

        return $next($request);
    }
}