<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Override Fortify's LoginResponse EARLY in the register phase
        // This ensures our binding takes precedence before Fortify registers its own
        $this->app->singleton(
            \Laravel\Fortify\Contracts\LoginResponse::class,
            \App\Http\Responses\LoginResponse::class
        );

        // Register a login event listener that sets the intended URL based on role
        \Illuminate\Support\Facades\Event::listen(\Illuminate\Auth\Events\Login::class, function ($event) {
            $user = $event->user;
            $request = request();

            if (! $request || ! $request->hasSession()) {
                return;
            }

            if ($user->role === 'admin') {
                $target = route('admin.dashboard');
            } elseif ($user->role === 'professor') {
                $target = route('professor.dashboard');
            } elseif ($user->role === 'aluno') {
                $target = route('aluno.dashboard');
            } else {
                $target = route('dashboard');
            }

            \Illuminate\Support\Facades\Log::info('SetIntendedAfterLogin (closure)', ['user_id' => $user->id ?? null, 'role' => $user->role ?? null, 'target' => $target]);

            $request->session()->put('url.intended', $target);
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
