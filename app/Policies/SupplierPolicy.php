<?php

namespace App\Policies;

use App\Models\Supplier;
use App\Models\User;

class SupplierPolicy
{
    public function view(User $user, Supplier $supplier): bool
    {
        return $supplier->user_id === $user->id;
    }

    public function update(User $user, Supplier $supplier): bool
    {
        return $supplier->user_id === $user->id;
    }

    public function delete(User $user, Supplier $supplier): bool
    {
        return $supplier->user_id === $user->id;
    }
}
