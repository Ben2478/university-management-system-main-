<?php

namespace App\Http\Controllers;

use App\Models\Lecturer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class LecturerController extends Controller
{
    private const DEFAULT_PASSWORD = 'welcome123';

    public function index()
    {
        $lecturers = Lecturer::withCount('courses')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $stats = [
            'total' => Lecturer::count(),
            'active' => Lecturer::where('status', 'active')->count(),
            'inactive' => Lecturer::where('status', 'inactive')->count(),
        ];

        $schools = config('schools.labels', []);

        return view('lecturers.index', compact('lecturers', 'stats', 'schools'));
    }

    public function create()
    {
        $schools = config('schools.labels', []);
        return view('lecturers.create', compact('schools'));
    }

    public function store(Request $request)
    {
        $allowedSchoolCodes = array_keys(config('schools.labels', []));

        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['nullable', 'string', 'max:30'],
            'school_code' => ['nullable', 'string', 'in:' . implode(',', $allowedSchoolCodes)],
            'status' => ['required', 'in:active,inactive'],
        ]);

        return DB::transaction(function () use ($validated) {
            $password = self::DEFAULT_PASSWORD;

            $user = User::create([
                'name' => $validated['first_name'] . ' ' . $validated['last_name'],
                'email' => $validated['email'],
                'password' => Hash::make($password),
                'role' => 'lecturer',
                'phone' => $validated['phone'] ?? null,
                'must_change_password' => true,
            ]);

            $year = date('Y');
            $sequence = str_pad((string) (Lecturer::count() + 1), 4, '0', STR_PAD_LEFT);
            $lecturerCode = 'LEC' . $year . $sequence;

            // Ensure unique lecturer_code if concurrent
            $tries = 0;
            while (Lecturer::where('lecturer_code', $lecturerCode)->exists() && $tries < 20) {
                $tries++;
                $sequence = str_pad((string) (Lecturer::count() + 1 + $tries), 4, '0', STR_PAD_LEFT);
                $lecturerCode = 'LEC' . $year . $sequence;
            }

            $lecturer = Lecturer::create([
                'user_id' => $user->id,
                'lecturer_code' => $lecturerCode,
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'] ?? null,
                'school_code' => $validated['school_code'] ?? null,
                'status' => $validated['status'],
            ]);

            return redirect()->route('lecturers.show', $lecturer)->with([
                'success' => 'Lecturer created successfully',
                'login_credentials' => [
                    'email' => $validated['email'],
                    'password' => $password,
                    'lecturer_code' => $lecturerCode,
                ],
            ]);
        });
    }

    public function show(Lecturer $lecturer)
    {
        $lecturer->load(['courses', 'user']);
        $schools = config('schools.labels', []);
        return view('lecturers.show', compact('lecturer', 'schools'));
    }

    public function edit(Lecturer $lecturer)
    {
        $schools = config('schools.labels', []);
        return view('lecturers.edit', compact('lecturer', 'schools'));
    }

    public function update(Request $request, Lecturer $lecturer)
    {
        $allowedSchoolCodes = array_keys(config('schools.labels', []));

        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $lecturer->user_id],
            'phone' => ['nullable', 'string', 'max:30'],
            'school_code' => ['nullable', 'string', 'in:' . implode(',', $allowedSchoolCodes)],
            'status' => ['required', 'in:active,inactive'],
        ]);

        return DB::transaction(function () use ($validated, $lecturer) {
            $lecturer->update([
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'] ?? null,
                'school_code' => $validated['school_code'] ?? null,
                'status' => $validated['status'],
            ]);

            $lecturer->user()->update([
                'name' => $validated['first_name'] . ' ' . $validated['last_name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'] ?? null,
            ]);

            return redirect()->route('lecturers.show', $lecturer)->with('success', 'Lecturer updated successfully');
        });
    }

    public function destroy(Lecturer $lecturer)
    {
        return DB::transaction(function () use ($lecturer) {
            $lecturer->courses()->detach();
            $user = $lecturer->user;
            $lecturer->delete();
            if ($user) {
                $user->delete();
            }

            return redirect()->route('lecturers.index')->with('success', 'Lecturer deleted successfully');
        });
    }
}
