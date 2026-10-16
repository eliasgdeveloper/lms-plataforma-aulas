<?php

namespace App\Http\Middleware;

use App\Support\ViewAs;
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

        if (! $user) {
            abort(403, 'Acesso negado');
        }

        $effective = ViewAs::effectiveRole();
        if ($effective !== $role) {
            // Usuário logado no papel errado: redireciona com aviso amigável
            // em vez de só mostrar 403 seco (páginas customizadas cobrem o restante).
            if ($request->expectsJson()) {
                abort(403, 'Acesso negado');
            }

            return redirect()
                ->to(ViewAs::homeFor($effective))
                ->with('status', 'Essa área é para outro perfil. Te trouxemos de volta para a sua área.');
        }

        return $next($request);
    }
}


