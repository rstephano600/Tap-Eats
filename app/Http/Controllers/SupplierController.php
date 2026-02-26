<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use App\Models\BusinessType;
use App\Models\SupplierUser;
use App\Models\User;
use App\Models\Role;
use App\Models\UserType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use App\Helpers\LogActivity;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class SupplierController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function suppliersinformations()
    {

    LogActivity::addToLog('View Supplier Informations');
    $query = Supplier::with('businessType')->where('Status', 'Active');
    if (!auth()->user()->hasRole('super_admin')) {
        $query->where('user_id', auth()->id());
    }
    $suppliers = $query
        ->whereNull('deleted_at')
        ->latest()
        ->paginate(10);
    return view('in.suppliers.bussinessinfo.suppliersinformations', compact('suppliers'));
    }

    public function publicIndex()
    {
        $suppliers = Supplier::query()
            ->where('is_active', true)
            ->where('status', 'active')
            ->where('verification_status', 'verified')
            ->whereNull('deleted_at')
            ->latest()
            ->paginate(12);

        return view('in.supplierss.public.index', compact('suppliers'));
    }

    public function createsuppliersinformations()
    {
        LogActivity::addToLog('Create Supplier Informations');
        $businesTypes = BusinessType::all();
        return view('in.suppliers.bussinessinfo.createsuppliersinformations', compact('businesTypes'));
    }

    public function storesuppliersinformations(Request $request)
    {
    //  try {
            LogActivity::addToLog('Store Supplier Informations');
            $request->validate([
                'business_name' => 'required|string|max:255',
                'business_type_id' => 'required|integer|exists:business_types,id',
                'contact_email' => 'required|email',
                'contact_phone' => 'required|string|max:20',
                'descriptions' => 'nullable|string|max:500',

                'logo_url' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
                'gallery_images.*' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            ]);

            $slug = Str::slug($request['business_name']);

            $logoPath = null;
            if ($request->hasFile('logo_url')) {
                $logoPath = $request->file('logo_url')
                    ->store('suppliers/logos', 'public');
            }
            $coverPath = null;
            if ($request->hasFile('logo_url')) {
                $coverPath = $request->file('logo_url')
                    ->store('suppliers/covers', 'public');
            }
            $galleryPaths = [];
            if ($request->hasFile('gallery_images')) {
                foreach ($request->file('gallery_images') as $image) {
                    $galleryPaths[] = $image->store('suppliers/gallery', 'public');
                }
            }
            
            Supplier::create([
            'business_name' => $request->business_name,
            'business_type_id' => $request->business_type_id,
            'contact_email' => $request->contact_email,
            'contact_phone' => $request->contact_phone,
            'slug' => $slug,
            'descriptions' => $request->descriptions,

            'logo_url' => $logoPath,
            'cover_image' => $coverPath,
            'gallery_images' => $galleryPaths ?: null,

            'user_id' => Auth::id(),
            'created_by' => Auth::id(),
            'Status'     => 'Inactive',
            'deleted_at'  => now(),
            'is_active'  => false,
            'verification_status'  => 'pending',
            ]);

            $exists = SupplierUser::where('supplier_id', $supplier->id)
                ->where('user_id', auth()->id())
                ->exists();

            if (!$exists) {
            SupplierUser::create([
                'supplier_id' => $supplier->id,
                'user_id'     => auth()->id(),
                'is_primary'  => $request->boolean('is_primary'),
                'is_active'   => true,
                'joined_at'   => now(),
                'created_by'  => auth()->id(),
                'Status'      => 'Active',
            ]);
            }
            $user = auth()->user();
             if (!$user->hasRole('supplier')) {
                     $user->assignRole('supplier');
             }
            return redirect()
                  ->route('suppliersinformations')
                  ->with('success' . auth()->user()->name, 'Supplier created successfully.');
        //    } catch (\Throwable $e) {
        //        return back()
        //            ->with('error', 'Technical error, please contact TapEats Administration for support. Tel: +255657856790');
        //    }
    }
public function toggleStatus($id)
{
    try {
        $decryptedId = decrypt($id);
        $supplier = Supplier::findOrFail($decryptedId);
        $newState = !$supplier->is_active;
        if ($newState === true) {
            $supplier->update([
                'is_active' => true,
                'Status' => 'Active',
                'is_open_now' => true,
                'accepts_orders' => true,
                'verification_status' => 'verified',
                'verified_at' => now(),
            ]);
            $message = 'Supplier has been activated successfully.';
        } else {
            $supplier->update([
                'is_active' => false,
                'is_featured' => false,
                'is_open_now' => false,
                'accepts_orders' => false,
                'Status' => 'Inactive',
                'verification_status' => 'pending',
                'verified_at' => null,
            ]);
            $message = 'Supplier has been deactivated and hidden from search.';
        }
        return back()->with('success', $message);
    } catch (\Exception $e) {
        return back()->with('error', 'Action failed: Invalid supplier identification.');
    }
}
    public function showsuppliersinformations($id)
    {
        try{
            $supplier = Supplier::findOrFail(decrypt($id));
           LogActivity::addToLog('Showingd Supplier Informations');
           return view('in.suppliers.bussinessinfo.showsuppliersinformations', compact('supplier'));
           } catch (\Throwable $e) {
               return back()
                   ->with('error', 'Technical error, please contact TapEats Administration for support. Tel: +255657856790');
           }
    }
    public function editsuppliersinformations($id)
    {
        try{
            $supplier = Supplier::findOrFail(decrypt($id));
            LogActivity::addToLog('Editing Supplier Informations');
            return view('in.suppliers.bussinessinfo.editsuppliersinformations', compact('supplier'));
            } catch (\Throwable $e) {
               return back()
                   ->with('error', 'Technical error, please contact TapEats Administration for support. Tel: +255657856790');
           }
    }

    public function updatesuppliersinformations(Request $request, $id)
    {
        try{
            $supplier = Supplier::findOrFail(decrypt($id));
            LogActivity::addToLog('Updating Supplier Informations');
            $request->validate([
               'business_name' => 'required|string|max:255',
               'business_type_id' => 'required|integer|exists:business_types,id',
               'contact_email' => 'required|email',
               'contact_phone' => 'required|string|max:20',
               'descriptions' => 'nullable|string|max:500',

               'logo_url' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
               'gallery_images.*' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
             ]);
            $slug = Str::slug($request->business_name);

            /** Logo */
            if ($request->hasFile('logo_url')) {
             $supplier->logo_url = $request->file('logo_url')
                    ->store('suppliers/logos', 'public');
            }        

            /** Cover image */
            if ($request->hasFile('logo_url')) {
                $supplier->cover_image = $request->file('logo_url')
                    ->store('suppliers/covers', 'public');
            }

            /** Gallery images (append or replace) */
            if ($request->hasFile('gallery_images')) {
                $galleryPaths = $supplier->gallery_images ?? [];

                foreach ($request->file('gallery_images') as $image) {
                    $galleryPaths[] = $image->store('suppliers/gallery', 'public');
                }

             $supplier->gallery_images = $galleryPaths;
            }

            $supplier->update([
                'business_name' => $request->business_name,
                'business_type_id' => $request->business_type_id,
                'contact_email' => $request->contact_email,
                'contact_phone' => $request->contact_phone,
                'slug' => $slug,
                'descriptions' => $request->descriptions,
            ]);

            return redirect()
                ->route('suppliersinformations')
                ->with('success', 'Supplier updated successfully.');

            } catch (\Throwable $e) {
                 Alert::error('Sorry! ' . auth()->user()->name,'Technical error please contact TapEats Administration For Support.'. br .'tel. +255657856790');
                 return back();
        }
    }

    public function destroysuppliersinformations($id)
    {
        try{
            $supplier = Supplier::findOrFail(decrypt($id));
        $this->authorizeAccess($supplier);

        $supplier->update([
            'Status'     => 'Deleted',
            'updated_by' => auth()->id(),
            'deleted_at'  => now(),
            'is_active'  => false,
            'verification_status'  => 'pending',
        ]);
        return redirect()
            ->route('suppliersinformations')
            ->with('success', auth()->user()->name . ' — Business removed successfully.');
       } catch (\Throwable $e) {
           \Log::error('Supplier soft-delete failed: ' . $e->getMessage()); 
           return back()
               ->with('error', 'Technical error, please contact TapEats Administration. Tel: +255657856790');
       }
    }

    private function authorizeAccess(Supplier $supplier): void
    {
        if (
            auth()->user()->hasRole('super_admin') ||
            $supplier->user_id === auth()->id()
        ) {
            return;
        }
        abort(403, 'Unauthorized access.');
    }




    // SUPPLIER USERS
    // SUPPLIER USERS INFORMATIONS
    public function supplieruserinfo()
    {
        try{
        $query = SupplierUser::with(['supplier', 'user', 'role'])
            ->latest()
            ->paginate(15);
        if (!auth()->user()->hasRole('super_admin')) {
            $query->where('user_id', auth()->id());
        }
        $supplierUsers = $query
            ->whereNull('deleted_at');

        return view('in.suppliers.supplierusers.supplieruserinfo', compact('supplierUsers'));
           } catch (\Throwable $e) {
               return back()
                   ->with('error', 'Technical error, please contact TapEats Administration for support. Tel: +255657856790');
           }
    }
    public function createsuppuserinfo()
    {
        try{
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
           } catch (\Throwable $e) {
               return back()
                   ->with('error', 'Technical error, please contact TapEats Administration for support. Tel: +255657856790');
           }
    }
    public function storesuppuserinfo(Request $request)
    {
        try{
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
           } catch (\Throwable $e) {
               return back()
                   ->with('error', 'Technical error, please contact TapEats Administration for support. Tel: +255657856790');
           }
    }

    public function editsuppuserinfo($id)
    {
        try{
           $supplierUser = SupplierUser::findOrFail(decrypt($id));
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
           } catch (\Throwable $e) {
               return back()
                   ->with('error', 'Technical error, please contact TapEats Administration for support. Tel: +255657856790');
           }
    }
    public function updatesuppuserinfo(Request $request, $id)
    {
        try{
           $supplierUser = SupplierUser::findOrFail(decrypt($id));

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
           } catch (\Throwable $e) {
               return back()
                   ->with('error', 'Technical error, please contact TapEats Administration for support. Tel: +255657856790');
           }
    }

    public function destroysuppuserinfo($id)
    {
        try{
            $supplierUser = SupplierUser::findOrFail(decrypt($id));

            $supplierUser->update([
            'Status'     => 'Deleted',
            'is_active'  => false,
                'updated_by' => auth()->id(),
            ]);

            return redirect()->route('supplieruserinfo')
                ->with('success', 'Supplier user removed successfully.');
           } catch (\Throwable $e) {
               return back()
                   ->with('error', 'Technical error, please contact TapEats Administration for support. Tel: +255657856790');
           }
    }

}