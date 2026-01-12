<?php

namespace App\Http\Controllers\Role;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Role;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::latest()->paginate(10);
        return view('in.admin.roles.index', compact('roles'));
    }

    public function create()
    {
        return view('in.admin.roles.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:roles,name',
            'slug' => 'nullable|string|max:100|unique:roles,slug',
            'descriptions' => 'nullable|string|max:200',
            'status' => 'required|in:active,inactive,locked,deleted',
        ]);

        Role::create([
            'name' => $request->name,
            'slug' => $request->slug ?? Str::slug($request->name),
            'descriptions' => $request->descriptions,
            'status' => $request->status,
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('roles.index')
            ->with('success', 'Role created successfully');
    }

    public function show(Role $role)
    {
        return view('in.admin.roles.show', compact('role'));
    }

    public function edit(Role $role)
    {
        return view('in.admin.roles.edit', compact('role'));
    }

    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:roles,name,' . $role->id,
            'slug' => 'nullable|string|max:100|unique:roles,slug,' . $role->id,
            'descriptions' => 'nullable|string|max:200',
            'status' => 'required|in:active,inactive,locked,deleted',
        ]);

        $role->update([
            'name' => $request->name,
            'slug' => $request->slug ?? Str::slug($request->name),
            'descriptions' => $request->descriptions,
            'status' => $request->status,
            'updated_by' => Auth::id(),
        ]);

        return redirect()->route('roles.index')
            ->with('success', 'Role updated successfully');
    }

    public function destroy(Role $role)
    {
        $role->update([
            'status' => 'deleted',
            'updated_by' => Auth::id(),
        ]);

        return redirect()->route('roles.index')
            ->with('success', 'Role deleted successfully');
    }
}
