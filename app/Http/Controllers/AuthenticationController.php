<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\UserType;
use App\Models\Supplier;
use App\Models\SupplierUser;

class AuthenticationController extends Controller
{
    // =============================================
    // SHOW LOGIN FORM
    // =============================================
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('in.auth.showLoginForm');
    }

    // =============================================
    // LOGIN
    // =============================================
public function login(Request $request)
{
    $request->validate([
        'login'    => 'required|string',
        'password' => 'required|string',
    ]);

    // Detect login type
    if (filter_var($request->login, FILTER_VALIDATE_EMAIL)) {
        $login_type = 'email';
    } elseif (preg_match('/^\+?[0-9]{7,15}$/', $request->login)) {
        $login_type = 'phone';
    } else {
        $login_type = 'username';
    }

    // Find user first — check status BEFORE attempting auth
    $user = User::where($login_type, $request->login)->first();

    // User not found
    if (!$user) {
        return back()
            ->withInput($request->only('login'))
            ->withErrors(['login' => 'Invalid login credentials.']);
    }

    // Check account status BEFORE authenticating
    if ($user->Status === 'Inactive') {
        return back()
            ->withInput($request->only('login'))
            ->with('error', 'Your account has been deactivated. Please contact TapEats Administration for support.');
    }

    if ($user->Status === 'Suspended') {
        return back()
            ->withInput($request->only('login'))
            ->with('error', 'Your account has been suspended. Please contact TapEats Administration for support.');
    }

    if ($user->Status === 'Banned') {
        return back()
            ->withInput($request->only('login'))
            ->with('error', 'Your account has been banned. Please contact TapEats Administration for support.');
    }

    // Now attempt authentication
    $credentials = [
        $login_type => $request->login,
        'password'  => $request->password,
    ];

    if (Auth::attempt($credentials, $request->boolean('remember'))) {
        $request->session()->regenerate();

        $user->update([
            'last_login_at' => now(),
            'last_login_ip' => $request->ip(),
            'login_status'  => 'Online',
            'login_times'   => $user->login_times + 1,
        ]);

        $supplier = $this->getSupplierForUser($user);
        if ($supplier) {
            session(['auth_supplier_id' => $supplier->id]);
        }

        return $this->redirectBasedOnRole($user);
    }

    return back()
        ->withInput($request->only('login'))
        ->withErrors(['login' => 'Invalid login credentials.']);
}

    // =============================================
    // LOGOUT
    // =============================================
    public function logout(Request $request)
    {
        // Update status before logging out
        if (Auth::check()) {
            Auth::user()->update([
                'login_status' => 'Offline',
            ]);
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('showLoginForm')
            ->with('success', 'You have been logged out successfully.');
    }

    // =============================================
    // SHOW REGISTER FORM
    // =============================================
    public function showRegisterForm()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('in.auth.showRegisterForm');
    }

    // =============================================
    // REGISTER
    // =============================================
    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'phone'    => [
                'required',
                'string',
                'unique:users,phone',
                // Validates: +255712345678 or 0712345678 or 255712345678
                'regex:/^(\+?255|0)[6-7][0-9]{8}$/',
            ],
            'password' => 'required|min:6',
        ], [
            // Custom error messages
            'phone.regex' => 'Phone number must be a valid Tanzanian number e.g. +255712345678 or 0712345678.',
            'phone.unique' => 'This phone number is already registered.',
            'email.unique' => 'This email address is already registered.',
        ]);

        try {
            // Generate unique username
            $username = $this->generateUsername($request->name);

            // Normalize phone to +255 format
            $phone = $this->normalizePhone($request->phone);

            // Get default user type
            $userType = UserType::where('name', 'Customer')->first();

            // Create user
            $user = User::create([
                'name'         => $request->name,
                'username'     => $username,
                'email'        => $request->email,
                'phone'        => $phone,
                'password'     => Hash::make($request->password),
                'user_type_id' => $userType?->id,
                'login_status' => 'Offline',
                'login_times'  => 0,
            ]);

            // Assign default role
            $user->assignRole('customer');

            // Auto login after registration
            Auth::login($user);

            // Track login
            $user->update([
                'last_login_at' => now(),
                'last_login_ip' => $request->ip(),
                'login_status'  => 'Online',
                'login_times'   => 1,
            ]);

            return redirect()
                ->route('dashboard')
                ->with('success', 'Welcome ' . $user->name . '! Registration successful.');

        } catch (\Throwable $e) {
            Log::error('Registration failed: ' . $e->getMessage());
            return back()
                ->withInput($request->except('password', 'password_confirmation'))
                ->with('error', 'Registration failed. Please try again.');
        }
    }

    // =============================================
    // PRIVATE HELPERS
    // =============================================

    /**
     * Redirect user based on their role after login
     */

private function redirectBasedOnRole(User $user): \Illuminate\Http\RedirectResponse
{
    // Check status FIRST — before any redirect
    if ($user->Status === 'Inactive') {

        // Log them back out immediately
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        // Reset login status since we're forcing them out
        $user->update([
            'login_status' => 'Offline',
        ]);

        return redirect()
            ->route('showLoginForm')
            ->with('error', 'Your account has been deactivated. Please contact TapEats Administration for support.');
    }

    // Also block Suspended or Banned accounts
    if (in_array($user->Status, ['Suspended', 'Banned'])) {

        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        $user->update(['login_status' => 'Offline']);

        return redirect()
            ->route('showLoginForm')
            ->with('error', 'Your account has been suspended. Please contact TapEats Administration for support.');
    }

    // All clear — redirect based on role
    if ($user->hasRole('super_admin')) {
        return redirect()->route('dashboard');
    }

    switch ($user->role) {
        case 'admin':
            return redirect()->route('dashboard');
        case 'supplier':
            return redirect()->route('dashboard');
        case 'customer':
            return redirect()->route('dashboard');
        case 'deliver':
            return redirect()->route('dashboard');
        default:
            return redirect()->route('dashboard');
    }
}


    /**
     * Get supplier associated with a user
     * Checks direct ownership AND supplier_user pivot
     */
    private function getSupplierForUser(User $user): ?Supplier
    {
        // Check direct owner
        $supplier = Supplier::where('user_id', $user->id)->first();

        if ($supplier) {
            return $supplier;
        }

        // Check supplier_user pivot table
        $supplierUser = SupplierUser::where('user_id', $user->id)->first();

        if ($supplierUser) {
            return Supplier::find($supplierUser->supplier_id);
        }

        return null;
    }

    /**
     * Generate a unique username from full name
     */
    private function generateUsername(string $name): string
    {
        $cleanName = strtolower(str_replace(' ', '', $name));
        $app       = 'TE';
        $year      = date('Y');
        $rand      = rand(100, 999);
        $username  = "{$cleanName}-{$app}-{$year}-{$rand}";

        while (User::where('username', $username)->exists()) {
            $rand     = rand(100, 999);
            $username = "{$cleanName}-{$app}-{$year}-{$rand}";
        }

        return $username;
    }

    /**
     * Normalize phone to +255 format
     * 0712345678 → +255712345678
     * 255712345678 → +255712345678
     * +255712345678 → +255712345678
     */
    private function normalizePhone(string $phone): string
    {
        $phone = preg_replace('/\s+/', '', $phone); // remove spaces

        if (str_starts_with($phone, '0')) {
            return '+255' . substr($phone, 1);
        }

        if (str_starts_with($phone, '255')) {
            return '+' . $phone;
        }

        return $phone; // already +255...
    }
}