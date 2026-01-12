<?php

namespace App\Http\Controllers\Role;

use App\Http\Controllers\Controller;

use App\Models\PermissionUser;
use App\Models\Permission;
use App\Models\UserPermission;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
class UserPermissionController extends Controller
{
    public function index()
    {
        $userPermissions = UserPermission::with(['user', 'permission'])
            ->where('status', '!=', 'deleted')
            ->latest()
            ->paginate(10);

        return view('in.admin.user_permissions.index', compact('userPermissions'));
    }

    public function create()
    {
        $users = User::where('status', 'active')->get();
        $permissions = Permission::where('status', 'active')->get();

        return view('in.admin.user_permissions.create', compact('users', 'permissions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'permission_id' => 'required|exists:permissions,id',
            'type' => 'required|in:grant,revoke',
        ]);

        UserPermission::create([
            'user_id' => $request->user_id,
            'permission_id' => $request->permission_id,
            'type' => $request->type,
            'status' => 'active',
            'created_by' => Auth::id(),
        ]);

        return redirect()
            ->route('user-permissions.index')
            ->with('success', 'User permission saved successfully.');
    }

    public function edit(UserPermission $userPermission)
    {
        $permissions = Permission::where('status', 'active')->get();

        return view('in.admin.user_permissions.edit', compact('userPermission', 'permissions'));
    }

    public function update(Request $request, UserPermission $userPermission)
    {
        $request->validate([
            'permission_id' => 'required|exists:permissions,id',
            'type' => 'required|in:grant,revoke',
            'status' => 'required|in:active,inactive,locked',
        ]);

        $userPermission->update([
            'permission_id' => $request->permission_id,
            'type' => $request->type,
            'status' => $request->status,
            'updated_by' => Auth::id(),
        ]);

        return redirect()
            ->route('user-permissions.index')
            ->with('success', 'User permission updated successfully.');
    }

    public function destroy(UserPermission $userPermission)
    {
        $userPermission->update([
            'status' => 'deleted',
            'updated_by' => Auth::id(),
        ]);

        return redirect()
            ->route('user-permissions.index')
            ->with('success', 'User permission deleted.');
    }
}