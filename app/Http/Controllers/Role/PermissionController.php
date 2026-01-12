<?php

namespace App\Http\Controllers\Role;

use App\Http\Controllers\Controller;

use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class PermissionController extends Controller
{
    public function index()
    {
        $permissions = Permission::where('status', '!=', 'deleted')
            ->latest()
            ->paginate(10);

        return view('in.permissions.index', compact('permissions'));
    }

    public function create()
    {
        return view('in.permissions.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:permissions,name',
            'descriptions' => 'nullable|string|max:500',
        ]);

        Permission::create([
            'name'        => $request->name,
            'descriptions'=> $request->descriptions,
            'created_by'  => Auth::id(),
            'status'      => 'active',
        ]);

        return redirect()
            ->route('permissions.index')
            ->with('success', 'Permission created successfully.');
    }

    public function show(Permission $permission)
    {
        return view('in.permissions.show', compact('permission'));
    }

    public function edit(Permission $permission)
    {
        return view('in.permissions.edit', compact('permission'));
    }

    public function update(Request $request, Permission $permission)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:permissions,name,' . $permission->id,
            'descriptions' => 'nullable|string|max:500',
            'status' => 'required|in:active,inactive,locked',
        ]);

        $permission->update([
            'name'        => $request->name,
            'descriptions'=> $request->descriptions,
            'status'      => $request->status,
            'updated_by'  => Auth::id(),
        ]);

        return redirect()
            ->route('permissions.index')
            ->with('success', 'Permission updated successfully.');
    }

    public function destroy(Permission $permission)
    {
        $permission->update([
            'status' => 'deleted',
            'updated_by' => Auth::id(),
        ]);

        return redirect()
            ->route('permissions.index')
            ->with('success', 'Permission deleted successfully.');
    }
}
