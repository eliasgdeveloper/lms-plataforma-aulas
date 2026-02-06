<?php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;
use Illuminate\Support\Facades\Log;

class LoginResponse implements LoginResponseContract
{
    public function toResponse($request)
    {
        // Esta resposta personalizada é chamada pelo Fortify após autenticação
        // para redirecionar o usuário para a dashboard correta baseada em seu papel.
        $user = $request->user();

        \Log::info('=== CUSTOM LOGIN RESPONSE CALLED ===', [
            'user_id' => $user->id ?? null,
            'role' => $user->role ?? null,
        ]);

        // Escolhe a rota nomeada de destino com base no campo `role` do usuário.
        // Observação: usamos rotas nomeadas (ex.: `admin.dashboard`) para que
        // o redirecionamento continue a funcionar mesmo se o prefixo mudar.
        if ($user->role === 'admin') {
            $target = route('admin.dashboard');
        } elseif ($user->role === 'professor') {
            $target = route('professor.dashboard');
        } elseif ($user->role === 'aluno') {
            $target = route('aluno.dashboard');
        } else {
            // fallback para um dashboard genérico quando role não estiver definida
            $target = route('dashboard');
        }

        // Log do target final para facilitar debugging (ver storage/logs/laravel.log)
        Log::info('Login redirect', [
            'user_id' => $user->id ?? null,
            'role' => $user->role ?? null,
            'target' => $target,
        ]);

        // Redireciona o usuário para a rota escolhida e adiciona um header
        // `X-Login-Target` útil para testes automatizados/debug.
        return redirect()->to($target)->header('X-Login-Target', $target);
    }
}

