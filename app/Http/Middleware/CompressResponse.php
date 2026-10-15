<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CompressResponse
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $content = $response->getContent();
        if (!is_string($content) || strlen($content) < 2048) {
            return $response;
        }
        if (!str_contains((string) $request->header('Accept-Encoding'), 'gzip')) {
            return $response;
        }
        if ($response->headers->has('Content-Encoding')) {
            return $response;
        }

        $type = (string) $response->headers->get('Content-Type');
        if (!str_contains($type, 'text/html')) {
            return $response;
        }

        $encoded = gzencode($content, 4);
        if ($encoded === false) {
            return $response;
        }

        $response->setContent($encoded);
        $response->headers->set('Content-Encoding', 'gzip');
        $response->headers->set('Content-Length', (string) strlen($encoded));
        $response->headers->set('Vary', 'Accept-Encoding');

        return $response;
    }
}
