<?php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;
use Illuminate\Support\Facades\Log;

class LoginResponse implements LoginResponseContract
{
    public function toResponse($request)
    {
        $user = $request->user();
        \Log::info('=== CUSTOM LOGIN RESPONSE CALLED ===', ['user_id' => $user->id ?? null, 'role' => $user->role ?? null]);

        // Determine target route by role (use named routes)
        if ($user->role === 'admin') {
            $target = route('admin.dashboard');
        } elseif ($user->role === 'professor') {
            $target = route('professor.dashboard');
        } elseif ($user->role === 'aluno') {
            $target = route('aluno.dashboard');
        } else {
            // fallback to generic dashboard
            $target = route('dashboard');
        }

        // Log redirect target for easier debugging
        Log::info('Login redirect', ['user_id' => $user->id ?? null, 'role' => $user->role ?? null, 'target' => $target]);

        // Force redirect to role-specific dashboard (ignore any prior intended URL)
        // Add header to help tests/debugging inspect the computed target
        return redirect()->to($target)->header('X-Login-Target', $target);
    }
}

