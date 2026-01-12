<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CustomerAddress;

class CustomerAddressController extends Controller
{
    public function index()
    {
        $addresses = auth()->user()->addresses()
            ->where('status', 'active')
            ->get();

        return view('in.customer.addresses.index', compact('addresses'));
    }

    public function create()
    {
        return view('in.customer.addresses.form');
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);

        if ($request->is_default) {
            $this->clearDefault();
        }

        CustomerAddress::create(array_merge($data, [
            'user_id' => auth()->id(),
            'created_by' => auth()->id(),
        ]));

        return redirect()->route('customer.addresses.index')
            ->with('success', 'Address added successfully');
    }

    public function edit(CustomerAddress $address)
    {
        $this->authorizeOwner($address);

        return view('in.customer.addresses.form', compact('address'));
    }

    public function update(Request $request, CustomerAddress $address)
    {
        $this->authorizeOwner($address);

        $data = $this->validateData($request);

        if ($request->is_default) {
            $this->clearDefault();
        }

        $address->update(array_merge($data, [
            'updated_by' => auth()->id(),
        ]));

        return redirect()->route('customer.addresses.index')
            ->with('success', 'Address updated successfully');
    }

    public function destroy(CustomerAddress $address)
    {
        $this->authorizeOwner($address);

        $address->update([
            'status' => 'inactive',
            'updated_by' => auth()->id(),
        ]);

        return back()->with('success', 'Address removed');
    }

    /* ---------------- HELPERS ---------------- */

    private function clearDefault()
    {
        CustomerAddress::where('user_id', auth()->id())
            ->update(['is_default' => false]);
    }

    private function authorizeOwner(CustomerAddress $address)
    {
        abort_unless($address->user_id === auth()->id(), 403);
    }

    private function validateData(Request $request): array
    {
        return $request->validate([
            'address_type' => 'required|in:home,work,other',
            'label' => 'nullable|string|max:50',
            'address_line1' => 'required|string|max:255',
            'address_line2' => 'nullable|string|max:255',
            'city' => 'required|string|max:100',
            'state' => 'nullable|string|max:100',
            'postal_code' => 'required|string|max:20',
            'country' => 'required|string|max:100',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'landmark' => 'nullable|string|max:255',
            'contact_phone' => 'nullable|string|max:20',
            'delivery_instructions' => 'nullable|string|max:500',
            'is_default' => 'boolean',
        ]);
    }
}
