<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Controleert of de ingelogde gebruiker beheerdersrechten heeft.
 *
 * Moet na de 'auth' middleware worden gebruikt, zodat
 * $request->user() gegarandeerd niet null is.
 */
class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->user()->isAdmin()) {
            abort(403, 'Geen toegang. Alleen beheerders hebben toegang tot deze pagina.');
        }

        return $next($request);
    }
}
