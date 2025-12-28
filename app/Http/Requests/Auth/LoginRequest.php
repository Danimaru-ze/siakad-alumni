<?php

namespace App\Http\Requests\Auth;

use App\Models\Mahasiswa;
use App\Models\Dosen;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            // 'email' diubah menjadi 'login' di form, dan tidak divalidasi sebagai email
            'login' => ['required', 'string'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        $login = $this->input('login');
        $password = $this->input('password');

        // Coba autentikasi via NIM
        $mahasiswa = Mahasiswa::where('nim', $login)->first();
        if ($mahasiswa) {
            // Jika mahasiswa ditemukan, coba login dengan user_id terkait
            if (Auth::attempt(['id' => $mahasiswa->user_id, 'password' => $password], $this->boolean('remember'))) {
                RateLimiter::clear($this->throttleKey());
                return;
            }
        }

        // Coba autentikasi via NIDN
        $dosen = Dosen::where('nidn', $login)->first();
        if ($dosen) {
            // Jika dosen ditemukan, coba login dengan user_id terkait
            if (Auth::attempt(['id' => $dosen->user_id, 'password' => $password], $this->boolean('remember'))) {
                RateLimiter::clear($this->throttleKey());
                return;
            }
        }

        // Jika tidak berhasil dengan NIM atau bukan NIM, coba dengan email
        if (! Auth::attempt(['email' => $login, 'password' => $password], $this->boolean('remember'))) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'login' => trans('auth.failed'),
            ]);
        }

        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Ensure the login request is not rate limited.
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'login' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->string('login')).'|'.$this->ip());
    }
}
