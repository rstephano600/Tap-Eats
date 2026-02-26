<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class StaffController extends Controller
{

    public function index(Request $request)
    {
        $user = auth()->user();
        
        // Get selected supplier or default to primary
        $supplierId = $request->input('supplier_id', $user->primarySupplier()?->id);
        
        // Check access
        if (!$user->hasAccessToSupplier($supplierId)) {
            abort(403, 'Unauthorized access to this restaurant.');
        }
        
        $supplier = Supplier::findOrFail($supplierId);
        
        // Get all staff for this supplier
        $staff = $supplier->users()
            ->with('suppliers')
            ->get()
            ->map(function ($member) use ($supplierId) {
                $role = $member->getRoleForSupplier($supplierId);
                $member->current_role = $role;
                $member->pivot_data = $member->pivot;
                return $member;
            });
        
        // Get available roles (exclude super_admin)
        $roles = Role::where('name', '!=', 'super_admin')->get();
        
        // Get accessible suppliers for dropdown
        $suppliers = $user->getAccessibleSuppliers();
        
        return view('in.admin.staff.index', compact('staff', 'supplier', 'suppliers', 'roles'));
    }

    /**
     * Show form to add staff member
     */
    public function create(Request $request)
    {
        $user = auth()->user();
        $supplierId = $request->input('supplier_id');
        
        if (!$user->canManageSupplier($supplierId)) {
            abort(403, 'Unauthorized.');
        }
        
        $supplier = Supplier::findOrFail($supplierId);
        $roles = Role::where('name', '!=', 'super_admin')->get();
        
        return view('in.admin.staff.create', compact('supplier', 'roles'));
    }

    /**
     * Store new staff member
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|max:20',
            'password' => 'required|string|min:8|confirmed',
            'role_id' => 'required|exists:roles,id',
            'is_primary' => 'boolean',
        ]);
        
        // Check permission
        if (!auth()->user()->canManageSupplier($validated['supplier_id'])) {
            abort(403);
        }
        
        try {
            DB::beginTransaction();
            
            // Create user
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'password' => Hash::make($validated['password']),
                'is_active' => true,
                'email_verified_at' => now(),
            ]);
            
            // Attach to supplier with role
            $user->attachSupplierWithRole(
                $validated['supplier_id'],
                $validated['role_id'],
                $validated['is_primary'] ?? false,
                auth()->id()
            );
            
            // Assign global role to user
            $role = Role::findOrFail($validated['role_id']);
            $user->assignRole($role);
            
            DB::commit();
            
            return redirect()
                ->route('admin.staff.index', ['supplier_id' => $validated['supplier_id']])
                ->with('success', 'Staff member added successfully!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Failed to add staff member: ' . $e->getMessage());
        }
    }

    /**
     * Update staff member's role
     */
    public function updateRole(Request $request, User $user)
    {
        $validated = $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'role_id' => 'required|exists:roles,id',
        ]);
        
        // Check permission
        if (!auth()->user()->canManageSupplier($validated['supplier_id'])) {
            abort(403);
        }
        
        // Prevent modifying super admin
        if ($user->isSuperAdmin()) {
            return back()->with('error', 'Cannot modify super admin role.');
        }
        
        try {
            DB::beginTransaction();
            
            // Update pivot table
            $user->updateSupplierRole($validated['supplier_id'], $validated['role_id']);
            
            // Update global role
            $role = Role::findOrFail($validated['role_id']);
            $user->syncRoles([$role]);
            
            DB::commit();
            
            return back()->with('success', 'Role updated successfully!');
            
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to update role: ' . $e->getMessage());
        }
    }

    /**
     * Remove staff member from supplier
     */
    public function destroy(Request $request, User $user)
    {
        $supplierId = $request->input('supplier_id');
        
        // Check permission
        if (!auth()->user()->canManageSupplier($supplierId)) {
            abort(403);
        }
        
        // Prevent removing super admin
        if ($user->isSuperAdmin()) {
            return back()->with('error', 'Cannot remove super admin.');
        }
        
        // Prevent self-removal
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot remove yourself.');
        }
        
        $user->detachSupplier($supplierId);
        
        return back()->with('success', 'Staff member removed successfully!');
    }

    /**
     * Toggle staff member active status
     */
    public function toggleStatus(Request $request, User $user)
    {
        $supplierId = $request->input('supplier_id');
        
        if (!auth()->user()->canManageSupplier($supplierId)) {
            abort(403);
        }
        
        $pivot = $user->suppliers()->where('supplier_id', $supplierId)->first()?->pivot;
        
        if ($pivot) {
            $user->suppliers()->updateExistingPivot($supplierId, [
                'is_active' => !$pivot->is_active,
            ]);
            
            $status = !$pivot->is_active ? 'activated' : 'deactivated';
            return back()->with('success', "Staff member {$status} successfully!");
        }
        
        return back()->with('error', 'Staff member not found.');
    }
}