<?php

namespace App\Services;

use App\Models\User;

class PermissionResolver
{
    public static function resolve(User $user): array
    {
        // 1. Get permissions from roles
        $rolePermissions = $user->roles()
            ->with('permissions')
            ->get()
            ->pluck('permissions')
            ->flatten()
            ->pluck('name')
            ->unique()
            ->toArray();

        // 2. Get user-level grants
        $granted = $user->userPermissions()
            ->where('type', 'grant')
            ->with('permission')
            ->get()
            ->pluck('permission.name')
            ->toArray();

        // 3. Get user-level revokes
        $revoked = $user->userPermissions()
            ->where('type', 'revoke')
            ->with('permission')
            ->get()
            ->pluck('permission.name')
            ->toArray();

        // 4. Merge logic
        $final = array_unique(array_merge($rolePermissions, $granted));

        // 5. Remove revoked permissions
        $final = array_diff($final, $revoked);

        return array_values($final);
    }

    public static function hasPermission(User $user, string $permission): bool
    {
        return in_array($permission, self::resolve($user));
    }
}
