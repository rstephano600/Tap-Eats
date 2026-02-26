<?php

namespace App\Http\Controllers\Role;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Str;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::with('permissions')->withCount('users')->get();
        return view('in.admin.roles.index', compact('roles'));
    }

    public function create()
    {
        $permissions = Permission::all()->groupBy(function($permission) {
            $parts = explode('_', $permission->name);
            return count($parts) > 1 ? $parts[1] : 'other';
        });
        
        return view('in.admin.roles.create', compact('permissions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
            'display_name' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:500',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $role = Role::create([
            'name' => Str::slug($validated['name'], '_'),
            'guard_name' => 'web',
        ]);

        // FIX: Get Permission models by IDs, not just the IDs
        if (!empty($validated['permissions'])) {
            $permissions = Permission::whereIn('id', $validated['permissions'])->get();
            $role->syncPermissions($permissions);
        }

        return redirect()->route('admin.roles.index')
            ->with('success', 'Role created successfully!');
    }

    public function show(Role $role)
    {
        $role->load('permissions', 'users');
        return view('in.admin.roles.show', compact('role'));
    }

    public function edit(Role $role)
    {
        if ($role->name === 'super_admin' && !auth()->user()->isSuperAdmin()) {
            abort(403, 'Cannot edit super admin role');
        }

        $permissions = Permission::all()->groupBy(function($permission) {
            $parts = explode('_', $permission->name);
            return count($parts) > 1 ? $parts[1] : 'other';
        });

        $rolePermissions = $role->permissions->pluck('id')->toArray();
        
        return view('in.admin.roles.edit', compact('role', 'permissions', 'rolePermissions'));
    }

    public function update(Request $request, Role $role)
    {
        if ($role->name === 'super_admin') {
            abort(403, 'Cannot edit super admin role');
        }

        $validated = $request->validate([
            'display_name' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:500',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        // FIX: Get Permission models by IDs
        $permissions = Permission::whereIn('id', $request->input('permissions', []))->get();
        $role->syncPermissions($permissions);

        return redirect()->route('admin.roles.index')
            ->with('success', 'Role updated successfully!');
    }

    public function destroy(Role $role)
    {
        if ($role->name === 'super_admin') {
            abort(403, 'Cannot delete super admin role');
        }

        if ($role->users()->count() > 0) {
            return back()->with('error', 'Cannot delete role with assigned users. Please reassign users first.');
        }

        $role->delete();

        return redirect()->route('admin.roles.index')
            ->with('success', 'Role deleted successfully!');
    }

    public function updatePermissions(Request $request, Role $role)
    {
        $validated = $request->validate([
            'permissions' => 'required|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        // FIX: Get Permission models by IDs
        $permissions = Permission::whereIn('id', $validated['permissions'])->get();
        $role->syncPermissions($permissions);

        return response()->json([
            'success' => true,
            'message' => 'Permissions updated successfully!'
        ]);
    }
}