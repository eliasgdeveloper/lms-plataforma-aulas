<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Contracts\LoginResponse;
use App\Http\Responses\LoginResponse as CustomLoginResponse;
use Laravel\Fortify\Fortify;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Cache\RateLimiting\Limit;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * FortifyServiceProvider
     *
     * Este provider faz duas coisas principais para o projeto LMS:
     * 1) Sobrepõe a resposta de login (`LoginResponse`) para usar nossa
     *    implementação customizada (`App\Http\Responses\LoginResponse`).
     * 2) Registra as views que o Laravel Fortify exige para resolver os
     *    contratos do tipo `*ViewResponse` (por exemplo `LoginViewResponse`).
     *
     * Também definimos limitadores de taxa (`RateLimiter::for`) usados
     * pelo Fortify para proteger endpoints de autenticação.
     */
    public function register(): void
    {
    }

    public function boot(): void
    {
        // Override Fortify's LoginResponse as a singleton to ensure our custom implementation is used
        $this->app->singleton(LoginResponse::class, CustomLoginResponse::class);

        // Register the login view so Fortify can resolve LoginViewResponse
        Fortify::loginView(function () {
            return view('auth.login');
        });
        // Register other Fortify views if present in resources/views/auth
        Fortify::registerView(function () {
            return view('auth.register');
        });

        Fortify::requestPasswordResetLinkView(function () {
            return view('auth.forgot-password');
        });

        Fortify::resetPasswordView(function () {
            return view('auth.reset-password');
        });

        Fortify::verifyEmailView(function () {
            return view('auth.verify-email');
        });

        Fortify::confirmPasswordView(function () {
            return view('auth.confirm-password');
        });

        // Define Fortify rate limiters used by the auth controllers
        // Limiter para tentativas de login: por e-mail + IP (evita ataques por força bruta)
        RateLimiter::for('login', function (Request $request) {
            $email = (string) $request->email;

            // Limit: 5 tentativas por minuto por combinação email+ip
            return Limit::perMinute(5)->by($email.$request->ip());
        });

        // Limiter para etapa de two-factor (quando aplicável). Usa o id de login armazenado na sessão.
        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });
    }
}
