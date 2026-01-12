<?php

namespace App\Http\Controllers\Role;

use App\Http\Controllers\Controller;
use App\Models\PermissionRole;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PermissionRoleController extends Controller
{
    public function index()
    {
        $permissionRoles = PermissionRole::with(['role', 'permission'])            
        ->latest()
        ->paginate(10);
        return view('in.admin.permission_roles.index', compact('permissionRoles'));
    }

    public function create()
    {
        $roles = Role::all();
        $permissions = Permission::all();
        return view('in.admin.permission_roles.create', compact('roles', 'permissions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'role_id' => 'required|exists:roles,id',
            'permission_id' => 'required|exists:permissions,id',
        ]);

        PermissionRole::create([
            'role_id' => $request->role_id,
            'permission_id' => $request->permission_id,
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('permission-roles.index')->with('success', 'Permission assigned');
    }

    public function destroy(PermissionRole $permissionRole)
    {
        $permissionRole->delete();
        return back()->with('success', 'Removed successfully');
    }
}

// class PermissionRoleController extends Controller
// {
//     public function index()
//     {
//         return view('admin.permission_roles.index', [
//             'roles' => Role::with('permissions')->get(),
//             'permissions' => Permission::where('status', 'active')->get(),
//         ]);
//     }

//     public function store(Request $request)
//     {
//         $request->validate([
//             'role_id' => 'required|exists:roles,id',
//             'permission_id' => 'required|exists:permissions,id',
//         ]);

//         PermissionRole::updateOrCreate(
//             [
//                 'role_id' => $request->role_id,
//                 'permission_id' => $request->permission_id,
//             ],
//             [
//                 'status' => 'active',
//                 'created_by' => auth()->id(),
//             ]
//         );

//         return back()->with('success', 'Permission assigned to role');
//     }

//     public function destroy(PermissionRole $permissionRole)
//     {
//         $permissionRole->update([
//             'status' => 'inactive',
//             'updated_by' => auth()->id(),
//         ]);

//         return back()->with('success', 'Permission removed from role');
//     }
// }

