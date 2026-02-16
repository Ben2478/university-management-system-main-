<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Show login form
    public function showLogin()
    {
        return view('auth.login');
    }

    // Handle login
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended('/');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    // Show register form
    public function showRegister()
    {
        return view('auth.register');
    }

    // Handle registration
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', 'in:super_admin,admin,student,parent'],
            'phone' => ['nullable', 'string'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'phone' => $validated['phone'] ?? null,
        ]);

        // Auto-create Student record if registering as student (for testing)
        if ($validated['role'] === 'student') {
            $nameParts = explode(' ', $validated['name'], 2);
            $firstName = $nameParts[0] ?? 'Student';
            $lastName = $nameParts[1] ?? 'User';
            
            // Generate unique student ID
            $studentId = 'STU' . date('Y') . str_pad(Student::count() + 1, 4, '0', STR_PAD_LEFT);
            
            Student::create([
                'user_id' => $user->id,
                'student_id' => $studentId,
                'first_name' => $firstName,
                'last_name' => $lastName,
                'email' => $validated['email'],
                'phone' => $validated['phone'] ?? null,
                'date_of_birth' => now()->subYears(20), // Default age
                'gender' => 'other',
                'address' => 'N/A',
                'city' => 'N/A',
                'state' => 'N/A',
                'postal_code' => '00000',
                'enrollment_date' => now(),
                'program' => 'General',
                'status' => 'active',
                'year' => 1,
                'semester' => 1,
                'tuition_fee' => 5000.00,
            ]);
        }

        Auth::login($user);

        return redirect('/');
    }

    // Handle logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}
