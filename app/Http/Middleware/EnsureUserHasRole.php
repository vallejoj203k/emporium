<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRole
{
    public function handle(Request $request, Closure $next, string $roles): Response
    {
        $user = $request->user();

        if (! $user || ! $user->relationLoaded('role') && ! $user->role) {
            abort(403);
        }

        $allowedRoles = array_map('trim', explode(',', $roles));
        $currentRole = $user->role?->slug ?? '';

        if (! in_array($currentRole, $allowedRoles, true)) {
            abort(403);
        }

        return $next($request);
    }
}
