<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, $role)
    {
        // Garante que o usuário autenticado possua o papel (role) exigido
        // pelo grupo de rotas. O middleware é registrado em `App\Http\Kernel.php`
        // como 'role' e usado assim: ->middleware('role:professor')
        $user = $request->user();

        if (! $user || $user->role !== $role) {
            // Retorna 403 quando o usuário não possui o papel correto.
            abort(403, 'Acesso negado');
        }

        return $next($request);
    }
}


