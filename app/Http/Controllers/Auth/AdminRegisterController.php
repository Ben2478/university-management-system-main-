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
        return view('auth.register');
    }

    public function store(Request $request)
    {
        // Toggle to disable later (defaults to enabled)
        if (!filter_var(env('PUBLIC_ADMIN_REGISTER_ENABLED', true), FILTER_VALIDATE_BOOLEAN)) {
            abort(404);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['nullable', 'string', 'max:30'],
            'address' => ['nullable', 'string', 'max:255'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            // Keep this limited. Promote to super_admin via DB or admin tooling after first login.
            'role' => 'admin',
            'phone' => $validated['phone'] ?? null,
            'address' => $validated['address'] ?? null,
            'password' => Hash::make(self::DEFAULT_PASSWORD),
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
