<?php

namespace App\Http\Controllers\Supplier;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use App\Models\BusinessType;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SupplierController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * List all suppliers owned by the authenticated user
     */
public function index()
{
    $query = Supplier::with('businessType');

    if (!auth()->user()->hasRole('super_admin')) {
        $query->where('user_id', auth()->id());
    }

    $suppliers = $query
        ->whereNull('deleted_at')
        ->latest()
        ->paginate(10);

    return view('in.suppliers.suppliers.index', compact('suppliers'));
}


    /**
     * Public / frontend listing
     * ONLY active + verified suppliers
     */
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

    /**
     * Create supplier form
     */

    public function create()
{
    $businessTypes = BusinessType::where('status','active')->orderBy('name')->get();

    return view('in.suppliers.suppliers.create', compact('businessTypes'));
}


    /**
     * Store supplier
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'business_name'      => 'required|string|max:255',
            'business_type_id'   => 'nullable|exists:business_types,id',
            'description'        => 'nullable|string',
            'contact_email'      => 'required|email',
            'contact_phone'      => 'required|string|max:20',
            'website'            => 'nullable|url',
        ]);

        $data['user_id'] = auth()->id();
        $data['slug'] = Str::slug($data['business_name']) . '-' . uniqid();
        $data['verification_status'] = 'pending';
        $data['status'] = 'active';
        $data['is_active'] = true;
        $data['accepts_orders'] = false;
        $data['created_by'] = auth()->id();

        Supplier::create($data);

        return redirect()
            ->route('supplier.suppliers.index')
            ->with('success', 'Business created successfully and pending verification.');
    }

    /**
     * Show supplier
     */
    public function show(Supplier $supplier)
    {
        $this->authorizeAccess($supplier);

        return view('in.suppliers.suppliers.show', compact('supplier'));
    }
    public function edit(Supplier $supplier)
{
    $this->authorizeAccess($supplier);

    $businessTypes = BusinessType::where('status','active')->orderBy('name')->get();

    return view('in.suppliers.suppliers.edit', compact('supplier', 'businessTypes'));
}


    /**
     * Edit supplier
     */


    /**
     * Update supplier
     */
    public function update(Request $request, Supplier $supplier)
    {
        $this->authorizeAccess($supplier);

        $data = $request->validate([
            'business_name'      => 'required|string|max:255',
            'business_type_id'   => 'nullable|exists:business_types,id',
            'description'        => 'nullable|string',
            'contact_email'      => 'required|email',
            'contact_phone'      => 'required|string|max:20',
            'website'            => 'nullable|url',
            'is_active'          => 'boolean',
            'accepts_orders'     => 'boolean',
        ]);

        $data['updated_by'] = auth()->id();

        $supplier->update($data);

        return redirect()
            ->route('supplier.suppliers.index')
            ->with('success', 'Business updated successfully.');
    }

    /**
     * Soft delete supplier
     */
    public function destroy(Supplier $supplier)
    {
        $this->authorizeAccess($supplier);

        $supplier->update([
            'status' => 'deleted',
            'updated_by' => auth()->id(),
        ]);

        $supplier->delete();

        return redirect()
            ->route('supplier.suppliers.index')
            ->with('success', 'Business removed successfully.');
    }

    /**
     * Centralized authorization
     */
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
}