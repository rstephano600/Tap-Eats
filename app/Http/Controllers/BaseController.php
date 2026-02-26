<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use App\Models\SupplierUser;

class BaseController extends Controller
{
    /**
     * Get the supplier that belongs to the currently logged-in user.
     * Checks both: direct owner (user_id) AND supplier_user pivot table.
     * Returns null if super_admin or no supplier found.
     */
    protected function getAuthSupplier(): ?Supplier
    {
        $user = auth()->user();

        // Super admin sees everything — no supplier filter
        if ($user->hasRole('super_admin')) {
            return null;
        }

        // Check if user is the direct owner of a supplier
        $supplier = Supplier::where('user_id', $user->id)->first();

        if ($supplier) {
            return $supplier;
        }

        // Check if user belongs to a supplier via supplier_user table
        $supplierUser = SupplierUser::where('user_id', $user->id)->first();

        if ($supplierUser) {
            return Supplier::find($supplierUser->supplier_id);
        }

        return null; // No supplier found
    }

    /**
     * Apply supplier filter to any query.
     * Pass your query and it will automatically scope it.
     */
    protected function scopeToSupplier($query, string $column = 'supplier_id')
    {
        $supplier = $this->getAuthSupplier();

        // null means super_admin — no filter applied
        if ($supplier) {
            $query->where($column, $supplier->id);
        }

        return $query;
    }
}