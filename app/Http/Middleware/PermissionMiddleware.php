<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PermissionMiddleware
{
    public function handle(Request $request, Closure $next, $permission)
    {
        if (!auth()->check()) {
            abort(403, 'Unauthorized');
        }

        if (!auth()->user()->hasPermission($permission)) {
            abort(403, 'Permission denied');
        }

        return $next($request);
    }
}
