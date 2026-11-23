<?php

namespace App\Services\Auth;

use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AuthenticationService
{
    private const int MaxLoginAttempts = 5;

    /**
     * @throws ValidationException
     */
    public function login(Request $request, string $identifier, string $password, bool $remember = false): void
    {
        $this->ensureIsNotRateLimited($request, $identifier);

        $field = filter_var($identifier, FILTER_VALIDATE_EMAIL) ? 'email' : 'login';

        if (! Auth::attempt([$field => $identifier, 'password' => $password], $remember)) {
            RateLimiter::hit($this->throttleKey($request, $identifier));

            throw ValidationException::withMessages([
                'identifier' => trans('auth.failed'),
            ]);
        }

        RateLimiter::clear($this->throttleKey($request, $identifier));
    }

    public function register(string $login, string $email, string $password): User
    {
        $role = Role::query()->firstOrCreate(['name' => 'User']);

        return User::query()->create([
            'role_id' => $role->id,
            'login' => $login,
            'email' => $email,
            'password_hash' => $password,
        ]);
    }

    public function logout(Request $request): void
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();
    }

    /**
     * @throws ValidationException
     */
    private function ensureIsNotRateLimited(Request $request, string $identifier): void
    {
        $key = $this->throttleKey($request, $identifier);

        if (! RateLimiter::tooManyAttempts($key, self::MaxLoginAttempts)) {
            return;
        }

        event(new Lockout($request));

        $seconds = RateLimiter::availableIn($key);

        throw ValidationException::withMessages([
            'identifier' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => (int) ceil($seconds / 60),
            ]),
        ]);
    }

    private function throttleKey(Request $request, string $identifier): string
    {
        return Str::transliterate(Str::lower($identifier).'|'.$request->ip());
    }
}
