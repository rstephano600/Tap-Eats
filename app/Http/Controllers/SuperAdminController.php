<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use App\Models\BusinessType;
use App\Models\SupplierUser;
use App\Models\User;
use App\Models\Role;
use App\Models\UserType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class SuperAdminController extends Controller
{
    //SUPER ADMIN CONTROLLERS
    public function manageSuppliers()
    {

    $query = Supplier::with('businessType');

    if (!auth()->user()->hasRole('super_admin')) {
        $query->where('user_id', auth()->id());
    }
    $suppliers = $query
        ->latest()
        ->paginate(10);

    return view('in.admin.suppliers.manageSuppliers', compact('suppliers'));
    }

}
