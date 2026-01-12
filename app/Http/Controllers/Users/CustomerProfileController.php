<?php

namespace App\Http\Controllers\Users;
use App\Http\Controllers\Controller;
use App\Models\CustomerProfile;
use App\Models\User;
use Illuminate\Http\Request;

class CustomerProfileController extends Controller
{
    public function index()
    {
        $profiles = CustomerProfile::with('user')->latest()->paginate(10);
        return view('in.admin.customer_profiles.index', compact('profiles'));
    }

    public function create()
    {
        $users = User::orderBy('name')->get();
        return view('in.admin.customer_profiles.create', compact('users'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:male,female,prefer_not_to_say',
            'dietary_preferences' => 'nullable|array',
            'allergies' => 'nullable|array',
            'default_payment_method' => 'nullable|string|max:50',
            'status' => 'required|in:active,inactive,locked,deleted',
        ]);

        $data['created_by'] = auth()->id();

        CustomerProfile::create($data);

        return redirect()
            ->route('customer-profiles.index')
            ->with('success', 'Customer profile created successfully.');
    }

    public function show(CustomerProfile $customerProfile)
    {
        return view('in.admin.customer_profiles.show', compact('customerProfile'));
    }

    public function edit(CustomerProfile $customerProfile)
    {
        $users = User::orderBy('name')->get();
        return view('in.admin.customer_profiles.edit', compact('customerProfile', 'users'));
    }

    public function update(Request $request, CustomerProfile $customerProfile)
    {
        $data = $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:male,female,prefer_not_to_say',
            'dietary_preferences' => 'nullable|array',
            'allergies' => 'nullable|array',
            'default_payment_method' => 'nullable|string|max:50',
            'status' => 'required|in:active,inactive,locked,deleted',
        ]);

        $data['updated_by'] = auth()->id();

        $customerProfile->update($data);

        return redirect()
            ->route('customer-profiles.index')
            ->with('success', 'Customer profile updated successfully.');
    }

    public function destroy(CustomerProfile $customerProfile)
    {
        $customerProfile->delete();

        return redirect()
            ->route('customer-profiles.index')
            ->with('success', 'Customer profile deleted.');
    }
}
