<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminRegisterController extends Controller
{
    private const DEFAULT_PASSWORD = 'welcome123';

    public function create()
    {
        // Toggle to disable later (defaults to enabled)
        if (!filter_var(env('PUBLIC_ADMIN_REGISTER_ENABLED', true), FILTER_VALIDATE_BOOLEAN)) {
            abort(404);
        }

        $hasSuperAdmin = User::where('role', 'super_admin')->exists();

        // Bootstrap mode: allow creating the FIRST super admin only.
        if ($hasSuperAdmin && filter_var(env('PUBLIC_ADMIN_REGISTER_BOOTSTRAP_ONLY', true), FILTER_VALIDATE_BOOLEAN)) {
            return view('auth.register', [
                'bootstrap_closed' => true,
                'can_create_super_admin' => false,
            ]);
        }

        return view('auth.register', [
            'bootstrap_closed' => false,
            'can_create_super_admin' => !$hasSuperAdmin,
        ]);
    }

    public function store(Request $request)
    {
        // Toggle to disable later (defaults to enabled)
        if (!filter_var(env('PUBLIC_ADMIN_REGISTER_ENABLED', true), FILTER_VALIDATE_BOOLEAN)) {
            abort(404);
        }

        $hasSuperAdmin = User::where('role', 'super_admin')->exists();

        // Bootstrap mode: once a super admin exists, public registration closes.
        if ($hasSuperAdmin && filter_var(env('PUBLIC_ADMIN_REGISTER_BOOTSTRAP_ONLY', true), FILTER_VALIDATE_BOOLEAN)) {
            abort(404);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'role' => ['required', 'in:super_admin,admin'],
            'phone' => ['nullable', 'string', 'max:30'],
            'address' => ['nullable', 'string', 'max:255'],
        ]);

        // Only allow creating a super_admin if none exists yet.
        if ($validated['role'] === 'super_admin' && $hasSuperAdmin) {
            return back()->withErrors([
                'role' => 'A Super Admin already exists. Please contact the school administrator.',
            ])->onlyInput(['name', 'email', 'phone', 'address']);
        }

        $email = strtolower(trim($validated['email']));
        $name = trim($validated['name']);

        $user = User::create([
            'name' => $name,
            'email' => $email,
            'role' => $validated['role'],
            'phone' => $validated['phone'] ?? null,
            'address' => $validated['address'] ?? null,
            'password' => self::DEFAULT_PASSWORD,
            'must_change_password' => true,
        ]);

        return redirect()->route('register')->with([
            'success' => 'User created successfully.',
            'created_user' => [
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'password' => self::DEFAULT_PASSWORD,
            ],
        ]);
    }
}
