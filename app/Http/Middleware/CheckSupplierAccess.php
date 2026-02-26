<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckSupplierAccess
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();
        
        // Super admin can access everything
        if ($user->isSuperAdmin()) {
            return $next($request);
        }
        
        // Get supplier_id from route parameter
        $supplierId = $request->route('supplier') ?? $request->route('supplier_id');
        
        // If no supplier in route, check if user has a supplier assigned
        if (!$supplierId && $user->supplier_id) {
            return $next($request);
        }
        
        // Check if user can manage this supplier
        if ($supplierId && !$user->canManageSupplier($supplierId)) {
            abort(403, 'Unauthorized access to this restaurant.');
        }
        
        return $next($request);
    }
}