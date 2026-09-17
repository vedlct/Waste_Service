<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdminUser
{
    /**
     * @param Closure(Request): Response $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user || $user->status !== 'active' || ! in_array($user->role, ['super_admin', 'admin', 'editor'], true)) {
            abort(Response::HTTP_FORBIDDEN);
        }

        return $next($request);
    }
}
