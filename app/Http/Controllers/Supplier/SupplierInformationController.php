<?php

namespace App\Http\Controllers\Supplier;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Supplier;
use App\Models\BusinesType;
use Illuminate\Support\Str;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Auth;

class SupplierInformationController extends Controller
{
    public function index()
    {
        $suppliers = Supplier::with('BusinessType')->where('status', '!=', 'deleted')
            ->latest()
            ->paginate(10);

        return view('in.suppliers.supplier_info.index', compact('suppliers'));
    }

    public function create()
    {
        $businesTypes = BusinesType::all();
        return view('in.suppliers.supplier_info.create', compact('businesTypes'));
    }

    public function store(Request $request)
    {
    //  try {
        $request->validate([
            'business_name' => 'required|string|max:255',
            'business_type_id' => 'required|integer|exists:business_types,id',
            'contact_email' => 'required|email',
            'contact_phone' => 'required|string|max:20',
            'descriptions' => 'nullable|string|max:500',
        ]);
        $slug = Str::slug($request['business_name']);

        Supplier::create([
            'business_name'=> $request->business_name,
            'business_type_id'=> $request->business_type_id,
            'contact_email'=> $request->contact_email,
            'contact_phone'=> $request->contact_phone,
            'slug'=> $slug,
            'descriptions'=> $request->descriptions,
            'user_id'  => Auth::id(),
            'created_by'  => Auth::id(),
            'status'      => 'active',
        ]);

        return redirect()
            ->route('suppliers.index')
            ->with('success', 'supplier created successfully.');
    // } catch (\Throwable $e) {
    //         Alert::error('Sorry! ' . auth()->user()->FirstName,'Technical error—please contact IT support.');
    //         return back();
    // }
    }

    public function show(Supplier $supplier)
    {
        return view('in.suppliers.supplier_info.show', compact('supplier'));
    }

    public function edit(Supplier $supplier)
    {
        return view('in.suppliers.supplier_info.edit', compact('supplier'));
    }

    public function update(Request $request, Supplier $supplier)
    {
        $request->validate([
            'business_name' => 'required|string|max:255',
            'business_type_id' => 'required|integer|exist:business_types',
            'contact_email' => 'required|email',
            'contact_phone' => 'required|string|max:20',
            'descriptions' => 'nullable|string|max:500',
            'status' => 'required|in:active,inactive,locked',
        ]);

        $supplier->update([
            'business_name'=> $request->business_name,
            'business_type_id'=> $request->business_type_id,
            'contact_email'=> $request->contact_email,
            'contact_phone'=> $request->contact_phone,
            'slug'=> $slug,
            'descriptions'=> $request->descriptions,
            'status'      => 'active',
            'updated_by'  => Auth::id(),
        ]);

        return redirect()
            ->route('suppliers.index')
            ->with('success', 'supplier updated successfully.');
    }

    public function destroy(Supplier $supplier)
    {
        $supplier->update([
            'status' => 'deleted',
            'updated_by' => Auth::id(),
        ]);

        return redirect()
            ->route('suppliers.index')
            ->with('success', 'supplier deleted successfully.');
    }
}


