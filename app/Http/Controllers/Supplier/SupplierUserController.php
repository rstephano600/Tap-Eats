<?php

namespace App\Http\Controllers\Supplier;
use App\Http\Controllers\Controller;
use App\Models\SupplierUser;
use App\Models\Supplier;
use App\Models\User;
use App\Models\Role;
use App\Models\UserType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class SupplierUserController extends Controller
{
    // SUPPLIER USERS INFORMATIONS
    public function supplieruserinfo()
    {
        $supplierUsers = SupplierUser::with(['supplier', 'user', 'role'])
            ->latest()
            ->paginate(15);
        return view('in.suppliers.supplierusers.supplieruserinfo', compact('supplierUsers'));
    }
    public function createsuppuserinfo()
    {
        $query = Supplier::with('businessType');
        $users     = User::all();
        $usertypes = UserType::where('Status', 'Active');
        
        if (!auth()->user()->hasRole('super_admin')) {
            $query->where('user_id', auth()->id());
        }
        $suppliers = $query
            ->whereNull('deleted_at')
            ->latest()
            ->get();

        // Exclude super_admin role
        $roles = Role::where('name', '!=', 'super_admin')->get();

        return view('in.suppliers.supplierusers.createsuppuserinfo', compact('suppliers', 'users', 'roles', 'usertypes'));
    }
    public function storesuppuserinfo(Request $request)
    {
        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'role_id'     => 'required|exists:roles,id',
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users',
            'phone'    => 'required|string|max:20|unique:users,phone',
        ]);
        $cleanName = strtolower(str_replace(' ', '', $request->name));
        $app = "TE";
        $date = date('Y');
        $rand = rand(100, 999);
        $username = "{$cleanName}-{$app}-{$date}-{$rand}";
        while (User::where('username', $username)->exists()) {
            $rand = rand(100, 999);
            $username = "{$cleanName}-{$app}-{$date}-{$rand}";
        }
        $userType = UserType::where('name', 'Customer')->first();
        $user = User::create([
            'name'         => $request->name,
            'username'     => $username,
            'email'        => $request->email,
            'phone'        => $request->phone,
            'password'     => Hash::make('tapeats'),
            'user_type_id' => $userType?->id,
        ]);

        $userId = $user->id;

        $exists = SupplierUser::where('supplier_id', $request->supplier_id)
            ->where('user_id', $request->user_id)
            ->where('role_id', $request->role_id)
            ->exists();

        if ($exists) {
            return back()->withErrors([
                'role_id' => 'This user already has this role for the selected supplier.'
            ])->withInput();
        }

        SupplierUser::create([
            'supplier_id' => $request->supplier_id,
            'user_id'     => $userId,
            'role_id'     => $request->role_id,
            'is_primary'  => $request->boolean('is_primary'),
            'is_active'   => true,
            'joined_at'   => now(),
            'created_by'  => auth()->id(),
            'Status'      => 'Active',
        ]);

        // Prevent assigning super_admin role
        $role = Role::findOrFail($request->role_id);
        if ($role->name === 'super_admin') {
            return back()->withErrors([
                'role_id' => 'Sorry This role cannot be assigned to a supplier Please contact Support.'
            ])->withInput();
        }
        $roleName = $role->name;
        $user->assignRole($roleName);

        return redirect()->route('supplieruserinfo')
            ->with('success', 'Supplier user assigned successfully.');
    }
    public function editsuppuserinfo($id)
    {
        $supplierUser = SupplierUser::findOrFail($id);
        $suppliers = Supplier::all();
        $users     = User::all();
        // Exclude super_admin role
        $roles = Role::where('name', '!=', 'super_admin')->get();
        return view('in.suppliers.supplierusers.editsuppuserinfo', compact(
            'supplierUser',
            'suppliers',
            'users',
            'roles'
        ));
    }
    public function updatesuppuserinfo(Request $request, $id)
    {
        $supplierUser = SupplierUser::findOrFail($id);

        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'user_id'     => 'required|exists:users,id',
            'role_id'     => 'required|exists:roles,id',
            'Status'      => 'required|in:Active,Inactive,Locked,Deleted'
        ]);

        $role = Role::findOrFail($request->role_id);
        if ($role->name === 'super_admin') {
            return back()->withErrors([
                'role_id' => 'Super Admin role cannot be assigned to a supplier.'
            ])->withInput();
        }
        $supplierUser->update([
            'supplier_id' => $request->supplier_id,
            'user_id'     => $request->user_id,
            'role_id'     => $request->role_id,
            'is_primary'  => $request->boolean('is_primary'),
            'is_active'   => $request->boolean('is_active'),
            'updated_by'  => auth()->id(),
            'Status'      => $request->Status,
        ]);

        return redirect()->route('supplieruserinfo')
            ->with('success', 'Supplier user updated successfully.');
    }

    public function destroysuppuserinfo($id)
    {
        $supplierUser = SupplierUser::findOrFail($id);

        $supplierUser->update([
            'Status'     => 'Deleted',
            'is_active'  => false,
            'updated_by' => auth()->id(),
        ]);

        return redirect()->route('supplieruserinfo')
            ->with('success', 'Supplier user removed successfully.');
    }
}
