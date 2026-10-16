<?php

namespace App\Http\Requests\Auth;

use App\Models\User;
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
        return [
            'email' => ['required', 'string', 'max:255'],
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

        $account = $this->accountByLogin(trim((string) $this->input('email')));

        if (! $account || ! Auth::attempt([
            'email' => $account->email,
            'password' => (string) $this->input('password'),
        ], $this->boolean('remember'))) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
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

    /**
     * Aceita e-mail ou usuário. O campo continua se chamando email
     * para não quebrar o formulário já publicado.
     */
    private function accountByLogin(string $login): ?User
    {
        $login = trim($login);
        if ($login === '') {
            return null;
        }

        if (str_contains($login, '@')) {
            return User::query()->whereRaw('lower(email) = ?', [Str::lower($login)])->first();
        }

        $aliases = [
            'elias' => 'elias@codar.local',
            'admin' => 'admin@example.com',
            'professor' => 'professor@example.com',
            'aluno' => 'aluno@example.com',
            'leandro' => 'leandro@codar.local',
            'usertesteai' => 'usertesteai@codar.local',
        ];

        $key = Str::lower($login);
        if (isset($aliases[$key])) {
            return User::query()->whereRaw('lower(email) = ?', [$aliases[$key]])->first();
        }

        return User::query()->whereRaw('lower(name) = ?', [$key])->first();
    }
}
