<?php

namespace App\Http\Controllers\Supplier;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use App\Models\SupplierLocation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SupplierLocationController extends Controller
{
    /**
     * Display supplier locations (HTML)
     */
    public function index(Request $request)
    {
        $supplierId = $this->resolveSupplierId($request);

        $locations = SupplierLocation::where('supplier_id', $supplierId)
            ->orderByDesc('is_primary')
            ->latest()
            ->paginate(10);

        return view('in.suppliers.locations.index', compact('locations'));
    }

    /**
     * Show create form (HTML)
     */
    public function create()
    {
        return view('in.suppliers.locations.create');
    }

    /**
     * Store location (JSON – AJAX)
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'address_line1' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'postal_code' => 'required|string|max:20',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'is_primary' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        DB::transaction(function () use ($request, &$location) {
            $supplierId = $this->resolveSupplierId($request);

            if ($request->is_primary) {
                SupplierLocation::where('supplier_id', $supplierId)
                    ->update(['is_primary' => false]);
            }

            $location = SupplierLocation::create([
                'supplier_id' => $supplierId,
                'address_line1' => $request->address_line1,
                'city' => $request->city,
                'postal_code' => $request->postal_code,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'is_primary' => $request->is_primary ?? false,
                'is_active' => true,
                'status' => 'active',
                'created_by' => Auth::id(),
            ]);
        });

        return response()->json([
            'success' => true,
            'message' => 'Location added successfully',
            'data' => $location
        ], 201);
    }

    /**
     * Show location details (HTML)
     */
    public function show($id)
    {
        $location = $this->findLocation($id);

        return view('in.suppliers.locations.show', compact('location'));
    }

    /**
     * Show edit form (HTML)
     */
    public function edit($id)
    {
        $location = $this->findLocation($id);

        return view('in.suppliers.locations.edit', compact('location'));
    }

    /**
     * Update location (JSON – AJAX)
     */
    public function update(Request $request, $id)
    {
        $location = $this->findLocation($id);

        $location->update(array_merge(
            $request->only([
                'address_line1', 'city', 'postal_code',
                'latitude', 'longitude', 'is_active'
            ]),
            ['updated_by' => Auth::id()]
        ));

        return response()->json([
            'success' => true,
            'message' => 'Location updated successfully',
            'data' => $location->fresh()
        ]);
    }

    /**
     * Set primary (JSON)
     */
    public function setPrimary($id)
    {
        DB::transaction(function () use ($id) {
            $location = $this->findLocation($id);

            SupplierLocation::where('supplier_id', $location->supplier_id)
                ->update(['is_primary' => false]);

            $location->update([
                'is_primary' => true,
                'updated_by' => Auth::id()
            ]);
        });

        return response()->json(['success' => true]);
    }

    /**
     * Toggle active (JSON)
     */
    public function toggleActive($id)
    {
        $location = $this->findLocation($id);

        $location->update([
            'is_active' => !$location->is_active,
            'updated_by' => Auth::id()
        ]);

        return response()->json(['success' => true, 'data' => $location]);
    }

    /**
     * Soft delete (JSON)
     */
    public function destroy($id)
    {
        $location = $this->findLocation($id);

        $location->update([
            'status' => 'deleted',
            'updated_by' => Auth::id()
        ]);

        // $location->delete();

        return response()->json(['success' => true]);
    }

    /**
     * Google address validation (JSON only)
     */
    public function validateAddress(Request $request)
    {
        // unchanged logic – JSON is correct here
    }

    /**
     * Helpers
     */
    private function resolveSupplierId(Request $request): int
    {
        if (auth()->user()->hasRole('super_admin') && $request->supplier_id) {
            return (int) $request->supplier_id;
        }

        return Supplier::where('user_id', auth()->id())->value('id');
    }

    private function findLocation(int $id): SupplierLocation
    {
        return SupplierLocation::where('id', $id)
            ->whereIn('supplier_id', Supplier::where('user_id', auth()->id())->pluck('id'))
            ->firstOrFail();
    }
}
