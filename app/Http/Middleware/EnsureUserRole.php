<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class EnsureUserRole
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string ...$roles)
    {
        $user = $request->user();

        if (! $user) {
            throw new AccessDeniedHttpException('Authentification requise.');
        }

        if ($roles && ! in_array($user->role, $roles, true)) {
            throw new AccessDeniedHttpException('Vous n\'avez pas les droits suffisants.');
        }

        return $next($request);
    }
}
