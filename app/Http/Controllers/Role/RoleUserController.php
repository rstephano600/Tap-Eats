<?php

namespace App\Http\Controllers\Role;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\RoleUser;
use App\Models\Role;
use App\Models\User;

use Illuminate\Support\Facades\Auth;

class RoleUserController extends Controller
{
    public function index()
    {
        $roleUsers = RoleUser::with(['user', 'role'])->latest()->get();
        return view('in.admin.role_users.index', compact('roleUsers'));
    }

    public function create()
    {
        $users = User::all();
        $roles = Role::all();
        return view('in.admin.role_users.create', compact('users', 'roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'role_id' => 'required|exists:roles,id',
        ]);

        RoleUser::create([
            'user_id' => $request->user_id,
            'role_id' => $request->role_id,
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('role-users.index')->with('success', 'Role assigned successfully');
    }

    public function edit(RoleUser $roleUser)
    {
        $users = User::all();
        $roles = Role::all();
        return view('in.admin.role_users.edit', compact('roleUser', 'users', 'roles'));
    }

    public function update(Request $request, RoleUser $roleUser)
    {
        $request->validate([
            'role_id' => 'required|exists:roles,id',
            'status' => 'required',
        ]);

        $roleUser->update([
            'role_id' => $request->role_id,
            'status' => $request->status,
            'updated_by' => Auth::id(),
        ]);

        return redirect()->route('role-users.index')->with('success', 'Updated successfully');
    }

    public function destroy(RoleUser $roleUser)
    {
        $roleUser->update([
            'status' => 'deleted',
            'updated_by' => Auth::id(),
        ]);
        return back()->with('success', 'Deleted successfully');
    }
}



// class RoleUserController extends Controller
// {
//     public function index()
//     {
//         return view('in.admin.role_users.index', [
//             'users' => User::with('roles')->get(),
//             'roles' => Role::where('status', 'active')->get(),
//         ]);
//     }

//     public function store(Request $request)
//     {
//         $request->validate([
//             'user_id' => 'required|exists:users,id',
//             'role_id' => 'required|exists:roles,id',
//         ]);

//         RoleUser::updateOrCreate(
//             [
//                 'user_id' => $request->user_id,
//                 'role_id' => $request->role_id,
//             ],
//             [
//                 'status' => 'active',
//                 'created_by' => auth()->id(),
//             ]
//         );

//         return back()->with('success', 'Role assigned successfully');
//     }

//     public function destroy(RoleUser $roleUser)
//     {
//         $roleUser->update([
//             'status' => 'inactive',
//             'updated_by' => auth()->id(),
//         ]);

//         return back()->with('success', 'Role removed');
//     }
// }
