<?php

namespace App\Http\Controllers\Supplier;

use App\Http\Controllers\Controller;
use App\Models\SupplierLocation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class SupplierLocationController extends Controller
{
    /**
     * Display a listing of supplier locations
     */
    public function index(Request $request)
    {
        $supplierId = Auth::user()->supplier_id ?? $request->supplier_id;
        
        $locations = SupplierLocation::where('supplier_id', $supplierId)
            ->when($request->status, function($query, $status) {
                return $query->where('status', $status);
            })
            ->when($request->is_active !== null, function($query) use ($request) {
                return $query->where('is_active', $request->is_active);
            })
            ->orderBy('is_primary', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $locations
        ]);
    }

    /**
     * Display the form for creating a new location
     */
    public function create()
    {
        return view('suppliers.locations.create');
    }

    /**
     * Store a newly created location
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'location_name' => 'nullable|string|max:100',
            'address_line1' => 'required|string|max:255',
            'address_line2' => 'nullable|string|max:255',
            'city' => 'required|string|max:100',
            'state' => 'nullable|string|max:100',
            'postal_code' => 'required|string|max:20',
            'country' => 'nullable|string|max:100',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'landmark' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'is_primary' => 'boolean',
            'google_place_id' => 'nullable|string',
            'formatted_address' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            $supplierId = Auth::user()->supplier_id ?? $request->supplier_id;

            // If this is set as primary, unset other primary locations
            if ($request->is_primary) {
                SupplierLocation::where('supplier_id', $supplierId)
                    ->update(['is_primary' => false]);
            }

            // If no locations exist, make this one primary
            $locationCount = SupplierLocation::where('supplier_id', $supplierId)->count();
            $isPrimary = $locationCount === 0 ? true : ($request->is_primary ?? false);

            $location = SupplierLocation::create([
                'supplier_id' => $supplierId,
                'location_name' => $request->location_name,
                'address_line1' => $request->address_line1,
                'address_line2' => $request->address_line2,
                'city' => $request->city,
                'state' => $request->state,
                'postal_code' => $request->postal_code,
                'country' => $request->country ?? 'Tanzania',
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'landmark' => $request->landmark,
                'phone' => $request->phone,
                'is_primary' => $isPrimary,
                'is_active' => true,
                'status' => 'active',
                'created_by' => Auth::id(),
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Location added successfully',
                'data' => $location
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to create location',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified location
     */
    public function show($id)
    {
        $supplierId = Auth::user()->supplier_id;
        
        $location = SupplierLocation::where('id', $id)
            ->where('supplier_id', $supplierId)
            ->firstOrFail();

        return response()->json([
            'success' => true,
            'data' => $location
        ]);
    }

    /**
     * Display the form for editing the specified location
     */
    public function edit($id)
    {
        $supplierId = Auth::user()->supplier_id;
        
        $location = SupplierLocation::where('id', $id)
            ->where('supplier_id', $supplierId)
            ->firstOrFail();

        return view('suppliers.locations.edit', compact('location'));
    }

    /**
     * Update the specified location
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'location_name' => 'nullable|string|max:100',
            'address_line1' => 'required|string|max:255',
            'address_line2' => 'nullable|string|max:255',
            'city' => 'required|string|max:100',
            'state' => 'nullable|string|max:100',
            'postal_code' => 'required|string|max:20',
            'country' => 'nullable|string|max:100',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'landmark' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'is_primary' => 'boolean',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            $supplierId = Auth::user()->supplier_id ?? $request->supplier_id;

            $location = SupplierLocation::where('id', $id)
                ->where('supplier_id', $supplierId)
                ->firstOrFail();

            // If setting as primary, unset other primary locations
            if ($request->is_primary && !$location->is_primary) {
                SupplierLocation::where('supplier_id', $supplierId)
                    ->where('id', '!=', $id)
                    ->update(['is_primary' => false]);
            }

            $location->update([
                'location_name' => $request->location_name,
                'address_line1' => $request->address_line1,
                'address_line2' => $request->address_line2,
                'city' => $request->city,
                'state' => $request->state,
                'postal_code' => $request->postal_code,
                'country' => $request->country ?? 'Tanzania',
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'landmark' => $request->landmark,
                'phone' => $request->phone,
                'is_primary' => $request->is_primary ?? $location->is_primary,
                'is_active' => $request->is_active ?? $location->is_active,
                'updated_by' => Auth::id(),
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Location updated successfully',
                'data' => $location->fresh()
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to update location',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Set a location as primary
     */
    public function setPrimary($id)
    {
        try {
            DB::beginTransaction();

            $supplierId = Auth::user()->supplier_id;

            // Unset all primary locations for this supplier
            SupplierLocation::where('supplier_id', $supplierId)
                ->update(['is_primary' => false]);

            // Set the selected location as primary
            $location = SupplierLocation::where('id', $id)
                ->where('supplier_id', $supplierId)
                ->firstOrFail();

            $location->update([
                'is_primary' => true,
                'updated_by' => Auth::id()
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Primary location updated successfully',
                'data' => $location
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to set primary location',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Toggle location active status
     */
    public function toggleActive($id)
    {
        try {
            $supplierId = Auth::user()->supplier_id;

            $location = SupplierLocation::where('id', $id)
                ->where('supplier_id', $supplierId)
                ->firstOrFail();

            $location->update([
                'is_active' => !$location->is_active,
                'updated_by' => Auth::id()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Location status updated successfully',
                'data' => $location
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to toggle location status',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified location (soft delete)
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $supplierId = Auth::user()->supplier_id;

            $location = SupplierLocation::where('id', $id)
                ->where('supplier_id', $supplierId)
                ->firstOrFail();

            // Prevent deleting primary location if there are other locations
            if ($location->is_primary) {
                $otherLocations = SupplierLocation::where('supplier_id', $supplierId)
                    ->where('id', '!=', $id)
                    ->count();

                if ($otherLocations > 0) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Cannot delete primary location. Please set another location as primary first.'
                    ], 422);
                }
            }

            $location->update([
                'status' => 'deleted',
                'updated_by' => Auth::id()
            ]);
            
            $location->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Location deleted successfully'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete location',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Validate address using Google Places API
     */
    public function validateAddress(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'place_id' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // You'll need to set GOOGLE_MAPS_API_KEY in your .env file
            $apiKey = config('services.google.maps_api_key');
            
            $url = "https://maps.googleapis.com/maps/api/place/details/json?place_id={$request->place_id}&key={$apiKey}";
            
            $response = file_get_contents($url);
            $data = json_decode($response, true);

            if ($data['status'] === 'OK') {
                $result = $data['result'];
                $components = $result['address_components'];

                // Parse address components
                $addressData = [
                    'formatted_address' => $result['formatted_address'],
                    'latitude' => $result['geometry']['location']['lat'],
                    'longitude' => $result['geometry']['location']['lng'],
                    'place_id' => $request->place_id,
                ];

                foreach ($components as $component) {
                    if (in_array('street_number', $component['types'])) {
                        $addressData['street_number'] = $component['long_name'];
                    }
                    if (in_array('route', $component['types'])) {
                        $addressData['route'] = $component['long_name'];
                    }
                    if (in_array('locality', $component['types'])) {
                        $addressData['city'] = $component['long_name'];
                    }
                    if (in_array('administrative_area_level_1', $component['types'])) {
                        $addressData['state'] = $component['long_name'];
                    }
                    if (in_array('postal_code', $component['types'])) {
                        $addressData['postal_code'] = $component['long_name'];
                    }
                    if (in_array('country', $component['types'])) {
                        $addressData['country'] = $component['long_name'];
                    }
                }

                return response()->json([
                    'success' => true,
                    'data' => $addressData
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Could not validate address'
            ], 422);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to validate address',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get primary location for supplier
     */
    public function getPrimary(Request $request)
    {
        $supplierId = Auth::user()->supplier_id ?? $request->supplier_id;
        
        $location = SupplierLocation::where('supplier_id', $supplierId)
            ->where('is_primary', true)
            ->where('is_active', true)
            ->first();

        if (!$location) {
            return response()->json([
                'success' => false,
                'message' => 'No primary location found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $location
        ]);
    }
}