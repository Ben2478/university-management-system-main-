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
    private const DEFAULT_PASSWORD = 'welcome123';

    // Show login form
    public function showLogin()
    {
        return view('auth.login');
    }

    // Handle login
    public function login(Request $request)
    {
        $validated = $request->validate([
            'identifier' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $identifier = trim($validated['identifier']);
        $remember = $request->boolean('remember');

        $credentials = null;

        // Staff/management/lecturers login via email
        if (filter_var($identifier, FILTER_VALIDATE_EMAIL)) {
            $credentials = [
                'email' => $identifier,
                'password' => $validated['password'],
            ];
        } else {
            // Students login via Student ID
            $student = Student::where('student_id', $identifier)->with('user')->first();
            if (!$student || !$student->user || !$student->user->isStudent()) {
                return back()->withErrors([
                    'identifier' => 'Invalid Student ID or password.',
                ])->onlyInput('identifier');
            }

            $credentials = [
                'email' => $student->user->email,
                'password' => $validated['password'],
            ];
        }

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            if (auth()->user()?->must_change_password) {
                return redirect()->route('password.change')->with('error', 'Please change your password to continue.');
            }

            return redirect()->intended('/');
        }

        return back()->withErrors([
            'identifier' => 'The provided credentials do not match our records.',
        ])->onlyInput('identifier');
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
            'role' => ['required', 'in:super_admin,admin,student,parent,lecturer'],
            'phone' => ['nullable', 'string'],
        ]);

        // Extra fields for student registration
        if ($validated['role'] === 'student') {
            $allowedSchoolCodes = array_keys(config('schools.labels', []));

            $studentExtra = $request->validate([
                'school_code' => ['required', 'string', 'in:' . implode(',', $allowedSchoolCodes)],
                'nrc' => ['required', 'string', 'max:50', function ($attribute, $value, $fail) {
                    $beforeSlash = explode('/', (string) $value, 2)[0] ?? '';
                    $digits = preg_replace('/\D+/', '', (string) $beforeSlash);
                    if (strlen($digits) < 3) {
                        $fail('The NRC must contain at least 3 digits before the first "/".');
                    }
                }],
                'enrollment_date' => ['required', 'date'],
            ]);

            $validated = array_merge($validated, $studentExtra);
        }

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'phone' => $validated['phone'] ?? null,
            'must_change_password' => true,
        ]);

        // Auto-create Student record if registering as student (for testing)
        if ($validated['role'] === 'student') {
            $nameParts = explode(' ', $validated['name'], 2);
            $firstName = $nameParts[0] ?? 'Student';
            $lastName = $nameParts[1] ?? 'User';

            // Generate student ID: YYYY + school_code + last3digits(NRC before first '/')
            $year = (string) date('Y', strtotime($validated['enrollment_date']));
            $beforeSlash = explode('/', (string) $validated['nrc'], 2)[0] ?? '';
            $digits = preg_replace('/\D+/', '', (string) $beforeSlash);
            $last3 = substr($digits, -3);
            $baseStudentId = $year . $validated['school_code'] . $last3;

            $studentId = $baseStudentId;
            if (Student::where('student_id', $studentId)->exists()) {
                for ($i = 1; $i <= 99; $i++) {
                    $candidate = $baseStudentId . str_pad((string) $i, 2, '0', STR_PAD_LEFT);
                    if (!Student::where('student_id', $candidate)->exists()) {
                        $studentId = $candidate;
                        break;
                    }
                }
            }
            
            Student::create([
                'user_id' => $user->id,
                'student_id' => $studentId,
                'first_name' => $firstName,
                'last_name' => $lastName,
                'email' => $validated['email'],
                'nrc' => $validated['nrc'],
                'phone' => $validated['phone'] ?? null,
                'date_of_birth' => now()->subYears(20), // Default age
                'gender' => 'other',
                'address' => 'N/A',
                'city' => 'N/A',
                'state' => 'N/A',
                'postal_code' => '00000',
                'enrollment_date' => $validated['enrollment_date'],
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
