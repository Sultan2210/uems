<?php

namespace App\Http\Requests\Auth;

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
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ];

        // If login_role is present (role selection step), validate it
        if ($this->has('login_role')) {
            $rules['login_role'] = ['required', 'in:organizer,student'];
        }

        return $rules;
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        $email = $this->string('email');
        $password = $this->string('password');
        $loginRole = $this->string('login_role');

        // If login_role is specified, authenticate with that specific role
        if ($loginRole) {
            $user = \App\Models\User::where('email', $email)
                ->where('role', $loginRole)
                ->first();

            if (!$user || !\Illuminate\Support\Facades\Hash::check($password, $user->password)) {
                RateLimiter::hit($this->throttleKey());
                throw ValidationException::withMessages([
                    'email' => trans('auth.failed'),
                ]);
            }

            Auth::login($user, $this->boolean('remember'));
        } else {
            // Standard authentication - but check if multiple roles exist
            $users = \App\Models\User::where('email', $email)->get();
            
            // Check if email exists with both organizer and student roles
            $hasOrganizer = $users->contains('role', 'organizer');
            $hasStudent = $users->contains('role', 'student');
            
            if ($hasOrganizer && $hasStudent) {
                // Verify password matches at least one of the accounts
                $passwordValid = false;
                foreach ($users as $user) {
                    if ($user->role === 'organizer' || $user->role === 'student') {
                        if (\Illuminate\Support\Facades\Hash::check($password, $user->password)) {
                            $passwordValid = true;
                            break;
                        }
                    }
                }
                
                if (!$passwordValid) {
                    RateLimiter::hit($this->throttleKey());
                    throw ValidationException::withMessages([
                        'email' => trans('auth.failed'),
                    ]);
                }
                
                // Password is valid - need role selection (don't increment rate limiter)
                throw ValidationException::withMessages([
                    'email' => 'multiple_roles',
                ]);
            }

            // Standard authentication attempt
            if (! Auth::attempt($this->only('email', 'password'), $this->boolean('remember'))) {
                RateLimiter::hit($this->throttleKey());

                throw ValidationException::withMessages([
                    'email' => trans('auth.failed'),
                ]);
            }
        }

        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
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
        return Str::transliterate(Str::lower($this->string('email')).'|'.$this->ip());
    }
}
