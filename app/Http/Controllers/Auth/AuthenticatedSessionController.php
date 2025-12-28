<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        $user = $request->user();

        // New redirection logic with priority on mahasiswa status
        if ($user->mahasiswa) {
            $status = $user->mahasiswa->status;
            if ($status === 'lulus') {
                return redirect()->intended(route('alumni.dashboard', absolute: false));
            } elseif ($status === 'aktif') {
                return redirect()->intended(route('mahasiswa.dashboard', absolute: false));
            } else {
                // For 'cuti', 'do', or other inactive statuses
                Auth::guard('web')->logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                return redirect()->route('login')->with('error', 'Status mahasiswa Anda saat ini ('.ucfirst($status).') tidak aktif. Silakan hubungi administrasi.');
            }
        }

        // Fallback to role-based redirection for non-mahasiswa users
        $redirectRoute = match ($user->role) {
            'dosen' => 'dosen.dashboard',
            'superadmin', 'admin_fakultas' => 'admin.dashboard',
            'hrd' => 'hrd.dashboard',
            'alumni' => 'alumni.dashboard', 
            default => 'login',
        };

        return redirect()->intended(route($redirectRoute, absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
