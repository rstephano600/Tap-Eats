<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use App\Models\SupplierUser;

class BaseController extends Controller
{
    /**
     * Check if current user is super admin
     */
    protected function isSuperAdmin(): bool
    {
        return auth()->user()->hasRole('super_admin');
    }

    /**
     * Get the supplier that belongs to the currently logged-in user.
     * Returns null ONLY for super_admin.
     * Returns false if user has no supplier at all.
     * Returns Supplier if found.
     */
    protected function getAuthSupplier(): Supplier|null|false
    {
        $user = auth()->user();

        // Super admin — return null (sees everything)
        if ($user->hasRole('super_admin')) {
            return null;
        }

        // Check direct ownership
        $supplier = Supplier::where('user_id', $user->id)->first();
        if ($supplier) {
            return $supplier;
        }

        // Check supplier_user pivot table
        $supplierUser = SupplierUser::where('user_id', $user->id)->first();
        if ($supplierUser) {
            return Supplier::find($supplierUser->supplier_id);
        }

        // User has NO supplier — return false (sees nothing)
        return false;
    }

    /**
     * Apply supplier filter to any query.
     *
     * null  = super_admin  → no filter (sees all)
     * false = no supplier  → sees nothing (where 0=1)
     * Supplier = scoped    → filtered by supplier_id
     */
    protected function scopeToSupplier($query, string $column = 'supplier_id')
    {
        $supplier = $this->getAuthSupplier();

        if ($supplier === null) {
            // Super admin — no filter
            return $query;
        }

        if ($supplier === false) {
            // No supplier found — return empty results
            return $query->whereRaw('0 = 1');
        }

        // Scoped to their supplier
        return $query->where($column, $supplier->id);
    }
}