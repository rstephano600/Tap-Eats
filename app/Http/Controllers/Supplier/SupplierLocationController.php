<?php

namespace App\Http\Controllers\Supplier;

use App\Http\Controllers\Controller;
use App\Models\SupplierLocation;
use App\Models\Supplier; // Assuming you need this for dropdowns
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SupplierLocationController extends Controller
{
    // 1. List all locations
    public function supplierlocations()
    {
        $locations = SupplierLocation::with('supplier')->paginate(15);
        return view('in.suppliers.locations.index', compact('locations'));
    }

    // 2. Show the form to create a new location
    public function createsupplierlocations()
    {
        $suppliers = Supplier::all(); // Needed to select which supplier owns this location
        return view('in.suppliers.locations.create', compact('suppliers'));
    }

    // 3. Save the new location
    public function storesupplierlocations(Request $request)
    {
        $validated = $request->validate([
            'supplier_id'   => 'required|exists:suppliers,id',
            'location_name' => 'required|string|max:255',
            'address_line1' => 'required|string',
            'city'          => 'required|string',
            'country'       => 'required|string',
            'latitude'      => 'nullable|numeric',
            'longitude'     => 'nullable|numeric',
            'postal_code'     => 'nullable|numeric',
        ]);

        // Add creator ID automatically
        $validated['created_by'] = Auth::id();
        $validated['is_active'] = $request->has('is_active');
        $validated['is_primary'] = $request->has('is_primary');

        SupplierLocation::create($validated);

        return redirect()->route('supplierlocations')->with('success', 'Location created successfully!');
    }

    // 4. Show a single location details
    public function showsupplierlocations($id)
    {
        $location = SupplierLocation::with(['supplier', 'creator', 'updater'])->findOrFail($id);
        return view('in.suppliers.locations.show', compact('location'));
    }

    // 5. Show the edit form
    public function editsupplierlocations($id)
    {
        $location = SupplierLocation::findOrFail($id);
        $suppliers = Supplier::all();
        return view('in.suppliers.locations.edit', compact('location', 'suppliers'));
    }

    // 6. Update the location
    public function updatesupplierlocations(Request $request, $id)
    {
        $location = SupplierLocation::findOrFail($id);

        $validated = $request->validate([
            'location_name' => 'required|string|max:255',
            'address_line1' => 'required|string',
            'city'          => 'required|string',
        ]);

        $validated['updated_by'] = Auth::id();
        $validated['is_active'] = $request->has('is_active');
        $validated['is_primary'] = $request->has('is_primary');

        $location->update($validated);

        return redirect()->route('supplierlocations')->with('success', 'Location updated successfully!');
    }

    // 7. Delete (Soft Delete) the location
    public function destroysupplierlocations($id)
    {
        $location = SupplierLocation::findOrFail($id);
        $location->delete();

        return redirect()->route('supplierlocations')->with('success', 'Location deleted successfully!');
    }
}