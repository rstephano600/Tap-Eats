<?php

namespace App\Http\Controllers\users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\UserType;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class AuthenticationController extends Controller
{
    public function showLoginForm()
    {
        return view('in.auth.showLoginForm');
    }

    public function login(Request $request)
{
    $request->validate([
        'login'    => 'required',   // can be username or email
        'password' => 'required'
    ]);

    $login_type = filter_var($request->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

    $credentials = [
        $login_type => $request->login,
        'password'  => $request->password,
    ];

    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();

        // Redirect according to ROLE
        switch (Auth::user()->role) {
            case 'admin':
                return redirect()->route('admin.dashboard');
            case 'supplier':
                return redirect()->route('supplier.dashboard');
            case 'customer':
                return redirect()->route('customer.dashboard');
            case 'deliver':
                return redirect()->route('deliver.dashboard');
            default:
                return redirect()->route('home');
        }
    }

    return back()->withErrors([
        'login' => 'Invalid login credentials.',
    ]);
}

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        return redirect()->route('showLoginForm');
    }

    public function showRegisterForm()
    {
        return view('in.auth.showRegisterForm');
    }

    public function register(Request $request)
    {
    $request->validate([
        'name'     => 'required|string|max:255',
        'email'    => 'required|email|unique:users',
        'phone'    => 'required|string|max:20|unique:users,phone',
        'password' => 'required|confirmed|min:6',
    ]);

    // 1️⃣ Clean name
    $cleanName = strtolower(str_replace(' ', '', $request->name));

    // 2️⃣ Your app short name
    $app = "TE";

    // 3️⃣ Year + random number
    $date = date('Y');
    $rand = rand(100, 999);

    // 4️⃣ Generate username
    $username = "{$cleanName}-{$app}-{$date}-{$rand}";

    // 5️⃣ Ensure it's unique
    while (User::where('username', $username)->exists()) {
        $rand = rand(100, 999);
        $username = "{$cleanName}-{$app}-{$date}-{$rand}";
    }

        // Get default user type (Customer)
        $userType = UserType::where('name', 'Customer')->first();

        // Create user
        $user = User::create([
            'name'         => $request->name,
            'username'     => $username,
            'email'        => $request->email,
            'phone'        => $request->phone,
            'password'     => Hash::make($request->password),
            'user_type_id' => $userType?->id,
        ]);

        // Assign default role (Customer)
        $customerRole = Role::where('slug', 'customer')->first();
        if ($customerRole) {
            $user->roles()->attach($customerRole->id);
        }

        // Auto login (optional – you can disable)
        Auth::login($user);

        return redirect()->route('home')->with('success', 'Registration successful!');
    }
}
