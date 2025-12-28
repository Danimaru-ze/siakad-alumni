<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureHrdProfileIsComplete
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if ($user && $user->role === 'hrd') {
            $hrdProfile = $user->hrdProfile;
            
            if (!$hrdProfile || !$hrdProfile->company) {
                // Allow access to the profile setup pages
                if ($request->routeIs('hrd.profile.*')) {
                    return $next($request);
                }
                return redirect()->route('hrd.profile.create')->with('error', 'Anda harus melengkapi profil perusahaan terlebih dahulu untuk mengakses halaman ini.');
            }
        }

        return $next($request);
    }
}