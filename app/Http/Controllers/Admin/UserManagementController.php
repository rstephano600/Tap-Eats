<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserManagementController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with(['roles', 'supplier']);

        // Filter by role
        if ($request->has('role') && $request->role) {
            $query->role($request->role);
        }

        // Filter by supplier (for super_admin)
        if ($request->has('supplier_id') && $request->supplier_id && auth()->user()->isSuperAdmin()) {
            $query->where('supplier_id', $request->supplier_id);
        } elseif (!auth()->user()->isSuperAdmin() && auth()->user()->supplier_id) {
            // Non-super admins only see users from their supplier
            $query->where('supplier_id', auth()->user()->supplier_id);
        }

        // Search
        if ($request->has('search') && $request->search) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('email', 'like', "%{$request->search}%")
                  ->orWhere('phone', 'like', "%{$request->search}%");
            });
        }

        $users = $query->latest()->paginate(20);
        $roles = Role::all();
        $suppliers = auth()->user()->isSuperAdmin() ? Supplier::where('status', 'active')->get() : collect();

        return view('in.admin.users.index', compact('users', 'roles', 'suppliers'));
    }

    public function create()
    {
        $roles = Role::all();
        $suppliers = auth()->user()->getAccessibleSuppliers();
        
        return view('in.admin.users.create', compact('roles', 'suppliers'));
    }

    public function storefake(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'password' => 'required|string|min:8|confirmed',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'roles' => 'required|array|min:1',
            'roles.*' => 'exists:roles,id',
            'is_active' => 'boolean',
        ]);

        // Check supplier access
        if ($validated['supplier_id'] && !auth()->user()->canManageSupplier($validated['supplier_id'])) {
            abort(403, 'Unauthorized to create users for this supplier');
        }

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'password' => Hash::make($validated['password']),
            'supplier_id' => $validated['supplier_id'] ?? auth()->user()->supplier_id,
            'is_active' => $request->has('is_active'),
            'email_verified_at' => now(),
        ]);

        $user->syncRoles($validated['roles']);

        return redirect()->route('admin.users.index')
            ->with('success', 'User created successfully!');
    }
public function store(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'phone' => 'nullable|string|max:20',
        'password' => 'required|string|min:8|confirmed',
        'supplier_id' => 'nullable|exists:suppliers,id',
        'roles' => 'required|array|min:1',
        'roles.*' => 'exists:roles,id',
        'is_active' => 'boolean',
    ]);

    if ($validated['supplier_id'] && !auth()->user()->canManageSupplier($validated['supplier_id'])) {
        abort(403, 'Unauthorized to create users for this supplier');
    }

    $user = User::create([
        'name' => $validated['name'],
        'email' => $validated['email'],
        'phone' => $validated['phone'] ?? null,
        'password' => Hash::make($validated['password']),
        'supplier_id' => $validated['supplier_id'] ?? auth()->user()->supplier_id,
        'is_active' => $request->has('is_active'),
        'email_verified_at' => now(),
    ]);

    // FIX: Get Role models by IDs
    $roles = Role::whereIn('id', $validated['roles'])->get();
    $user->syncRoles($roles);

    return redirect()->route('admin.users.index')
        ->with('success', 'User created successfully!');
}

public function update(Request $request, User $user)
{
    if ($user->isSuperAdmin() && !auth()->user()->isSuperAdmin()) {
        abort(403);
    }

    if (!auth()->user()->canManageSupplier($user->supplier_id)) {
        abort(403);
    }

    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email,' . $user->id,
        'phone' => 'nullable|string|max:20',
        'password' => 'nullable|string|min:8|confirmed',
        'supplier_id' => 'nullable|exists:suppliers,id',
        'roles' => 'required|array|min:1',
        'roles.*' => 'exists:roles,id',
        'is_active' => 'boolean',
    ]);

    $updateData = [
        'name' => $validated['name'],
        'email' => $validated['email'],
        'phone' => $validated['phone'] ?? null,
        'supplier_id' => $validated['supplier_id'] ?? $user->supplier_id,
        'is_active' => $request->has('is_active'),
    ];

    if (!empty($validated['password'])) {
        $updateData['password'] = Hash::make($validated['password']);
    }

    $user->update($updateData);
    
    // FIX: Get Role models by IDs
    $roles = Role::whereIn('id', $validated['roles'])->get();
    $user->syncRoles($roles);

    return redirect()->route('admin.users.index')
        ->with('success', 'User updated successfully!');
}

public function updateRoles(Request $request, User $user)
{
    $validated = $request->validate([
        'roles' => 'required|array|min:1',
        'roles.*' => 'exists:roles,id',
    ]);

    // FIX: Get Role models by IDs
    $roles = Role::whereIn('id', $validated['roles'])->get();
    $user->syncRoles($roles);

    return response()->json([
        'success' => true,
        'message' => 'User roles updated successfully!'
    ]);
}
    public function show(User $user)
    {
        // Check access
        if (!auth()->user()->canManageSupplier($user->supplier_id)) {
            abort(403);
        }

        $user->load(['roles.permissions', 'supplier', 'assignedOrders', 'customerOrders']);
        
        return view('in.admin.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        // Prevent editing super admin by non-super admins
        if ($user->isSuperAdmin() && !auth()->user()->isSuperAdmin()) {
            abort(403, 'Cannot edit super admin user');
        }

        // Check supplier access
        if (!auth()->user()->canManageSupplier($user->supplier_id)) {
            abort(403);
        }

        $roles = Role::all();
        $suppliers = auth()->user()->getAccessibleSuppliers();
        $userRoles = $user->roles->pluck('id')->toArray();
        
        return view('in.admin.users.edit', compact('user', 'roles', 'suppliers', 'userRoles'));
    }

    public function updatefake(Request $request, User $user)
    {
        // Prevent editing super admin
        if ($user->isSuperAdmin() && !auth()->user()->isSuperAdmin()) {
            abort(403);
        }

        // Check supplier access
        if (!auth()->user()->canManageSupplier($user->supplier_id)) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:8|confirmed',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'roles' => 'required|array|min:1',
            'roles.*' => 'exists:roles,id',
            'is_active' => 'boolean',
        ]);

        $updateData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'supplier_id' => $validated['supplier_id'] ?? $user->supplier_id,
            'is_active' => $request->has('is_active'),
        ];

        if (!empty($validated['password'])) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        $user->update($updateData);
        $user->syncRoles($validated['roles']);

        return redirect()->route('admin.users.index')
            ->with('success', 'User updated successfully!');
    }

    public function destroy(User $user)
    {
        // Prevent deleting super admin
        if ($user->isSuperAdmin()) {
            abort(403, 'Cannot delete super admin user');
        }

        // Check supplier access
        if (!auth()->user()->canManageSupplier($user->supplier_id)) {
            abort(403);
        }

        // Prevent self-deletion
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete your own account');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'User deleted successfully!');
    }

    public function updateRolesfake(Request $request, User $user)
    {
        $validated = $request->validate([
            'roles' => 'required|array|min:1',
            'roles.*' => 'exists:roles,id',
        ]);

        $user->syncRoles($validated['roles']);

        return response()->json([
            'success' => true,
            'message' => 'User roles updated successfully!'
        ]);
    }

    public function toggleStatus(User $user)
    {
        if ($user->isSuperAdmin() && !auth()->user()->isSuperAdmin()) {
            abort(403);
        }

        $user->update(['is_active' => !$user->is_active]);

        return response()->json([
            'success' => true,
            'is_active' => $user->is_active,
            'message' => 'User status updated successfully!'
        ]);
    }
}