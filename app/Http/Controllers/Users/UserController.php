<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\UserType;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::where('status', '!=', 'deleted')
            ->latest()
            ->paginate(10);

        return view('in.admin.users.index', compact('users'));
    }

    public function create()
    {
        $userTypes = UserType::where('status', 'active')->get();
        return view('in.admin.users.create', compact('userTypes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'      => 'required|string|max:255',
            'username'  => 'required|string|max:100|unique:users',
            'email'     => 'required|email|unique:users',
            'phone'     => 'required|string|max:20|unique:users',
            'user_type_id' => 'nullable|exists:user_types,id',
            'password'  => 'required|min:6',
        ]);

        User::create([
            'name'        => $request->name,
            'username'    => $request->username,
            'email'       => $request->email,
            'phone'       => $request->phone,
            'user_type_id'=> $request->user_type_id,
            'user_type'   => optional(UserType::find($request->user_type_id))->name,
            'password'    => Hash::make($request->password),
            'status'      => 'active',
            'created_by'  => Auth::id(),
        ]);

        return redirect()->route('users.index')
            ->with('success', 'User created successfully.');
    }

    public function show(User $user)
    {
        return view('in.admin.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        $userTypes = UserType::where('status', 'active')->get();
        return view('in.admin.users.edit', compact('user', 'userTypes'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'username' => 'required|unique:users,username,' . $user->id,
            'email'    => 'required|email|unique:users,email,' . $user->id,
            'phone'    => 'required|unique:users,phone,' . $user->id,
            'status'   => 'required|in:active,inactive,suspended,locked',
            'user_type_id' => 'nullable|exists:user_types,id',
        ]);

        $data = $request->only([
            'name','username','email','phone','status','user_type_id'
        ]);


        $data['user_type'] = optional(UserType::find($request->user_type_id))->name;
        $data['updated_by'] = Auth::id();

        $user->update($data);

        return redirect()->route('users.index')
            ->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        $user->update([
            'status' => 'deleted',
            'updated_by' => Auth::id(),
        ]);

        $user->delete(); // Soft delete

        return redirect()->route('users.index')
            ->with('success', 'User deleted successfully.');
    }
}