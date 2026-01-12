<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CustomerProfile;
class UserCustomerProfileController extends Controller
{
    public function show()
    {
        $profile = auth()->user()->customerProfile;

        return view('in.customer.profile.show', compact('profile'));
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);

        $data['user_id'] = auth()->id();
        $data['created_by'] = auth()->id();

        CustomerProfile::create($data);

        return redirect()->back()->with('success', 'Profile created successfully');
    }

    public function update(Request $request)
    {
        $profile = auth()->user()->customerProfile;

        $data = $this->validateData($request);
        $data['updated_by'] = auth()->id();

        $profile->update($data);

        return redirect()->back()->with('success', 'Profile updated successfully');
    }

    private function validateData(Request $request): array
    {
        return $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:male,female,prefer_not_to_say',
            'dietary_preferences' => 'nullable|array',
            'allergies' => 'nullable|array',
            'default_payment_method' => 'nullable|string|max:50',
            'email_notifications' => 'boolean',
            'sms_notifications' => 'boolean',
            'push_notifications' => 'boolean',
        ]);
    }
}