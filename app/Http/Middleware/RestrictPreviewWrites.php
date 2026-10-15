<?php

namespace App\Http\Middleware;

use App\Support\ViewAs;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RestrictPreviewWrites
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! ViewAs::isPreview() || $request->isMethodSafe()) {
            return $next($request);
        }

        if ($request->routeIs('view-as.update', 'logout', 'conteudos.progress', 'conteudos.desafio.judge', 'conteudos.desafio.quiz')) {
            return $next($request);
        }

        abort(403, 'Na visualização de outro papel só é possível olhar. Volte ao seu papel para editar.');
    }
}
