<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    private const DEFAULT_PASSWORD = 'welcome123';

    /**
     * Display a listing of users
     */
    public function index(Request $request)
    {
        $query = User::query();

        // Search
        if ($search = $request->get('search')) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Filter by role
        if ($role = $request->get('role')) {
            $query->where('role', $role);
        }

        // Filter by status (deleted or not)
        if ($status = $request->get('status')) {
            if ($status === 'inactive') {
                $query->onlyTrashed();
            } elseif ($status === 'active') {
                $query->whereNull('deleted_at');
            }
        }

        // Filter by registration date
        if ($dateFrom = $request->get('date_from')) {
            $query->whereDate('created_at', '>=', $dateFrom);
        }
        if ($dateTo = $request->get('date_to')) {
            $query->whereDate('created_at', '<=', $dateTo);
        }

        // Calculate stats
        $stats = [
            'total_users' => User::count(),
            'super_admins' => User::where('role', 'super_admin')->count(),
            'admins' => User::where('role', 'admin')->count(),
            'students' => User::where('role', 'student')->count(),
            'parents' => User::where('role', 'parent')->count(),
            'lecturers' => User::where('role', 'lecturer')->count(),
            'inactive' => User::onlyTrashed()->count(),
        ];

        $users = $query->withTrashed()->orderByDesc('created_at')->paginate(15);

        return view('users.index', compact('users', 'stats'));
    }

    /**
     * Show the form for creating a new user
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Store a newly created user
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'role' => 'required|in:super_admin,admin,student,parent,lecturer',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
        ]);

        $validated['password'] = Hash::make(self::DEFAULT_PASSWORD);
        $validated['must_change_password'] = true;

        User::create($validated);

        return redirect()->route('users.index')->with('success', 'User created successfully. Default password is welcome123 (user will be forced to change it at first login).');
    }

    /**
     * Display the specified user
     */
    public function show(User $user)
    {
        $user->load('student', 'childrenAsParent');
        
        // Get activity info
        $activityStats = [
            'last_login' => $user->updated_at, // Placeholder - you can add last_login column
            'total_logins' => 0, // Placeholder - add login tracking if needed
            'account_age_days' => $user->created_at->diffInDays(now()),
        ];

        return view('users.show', compact('user', 'activityStats'));
    }

    /**
     * Show the form for editing the user
     */
    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    /**
     * Update the specified user
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|in:super_admin,admin,student,parent,lecturer',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
        ]);

        // Update password only if provided
        if ($request->filled('password')) {
            $request->validate([
                'password' => 'required|string|min:8|confirmed',
            ]);
            $validated['password'] = Hash::make($request->password);
        }

        $user->update($validated);

        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

    /**
     * Soft delete the user
     */
    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->withErrors(['error' => 'You cannot delete yourself.']);
        }

        $user->delete();

        return redirect()->route('users.index')->with('success', 'User deactivated successfully.');
    }

    /**
     * Restore a soft-deleted user
     */
    public function restore($id)
    {
        $user = User::withTrashed()->findOrFail($id);
        $user->restore();

        return redirect()->route('users.index')->with('success', 'User restored successfully.');
    }

    /**
     * Reset user password
     */
    public function resetPassword(Request $request, User $user)
    {
        $validated = $request->validate([
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        $user->update([
            'password' => Hash::make($validated['new_password']),
            'must_change_password' => true,
        ]);

        return back()->with('success', 'Password reset successfully.');
    }

    /**
     * Export users to CSV
     */
    public function export(Request $request)
    {
        $query = User::withTrashed();

        // Apply same filters as index
        if ($search = $request->get('search')) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($role = $request->get('role')) {
            $query->where('role', $role);
        }

        if ($status = $request->get('status')) {
            if ($status === 'inactive') {
                $query->onlyTrashed();
            } elseif ($status === 'active') {
                $query->whereNull('deleted_at');
            }
        }

        if ($dateFrom = $request->get('date_from')) {
            $query->whereDate('created_at', '>=', $dateFrom);
        }
        if ($dateTo = $request->get('date_to')) {
            $query->whereDate('created_at', '<=', $dateTo);
        }

        $users = $query->orderByDesc('created_at')->get();

        $filename = 'users_' . date('Y-m-d_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename={$filename}",
        ];

        $callback = function() use ($users) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'Name', 'Email', 'Phone', 'Role', 'Status', 'Address', 'Registered', 'Last Updated']);

            foreach ($users as $user) {
                fputcsv($file, [
                    $user->id,
                    $user->name,
                    $user->email,
                    $user->phone ?? 'N/A',
                    ucfirst(str_replace('_', ' ', $user->role)),
                    $user->deleted_at ? 'Inactive' : 'Active',
                    $user->address ?? 'N/A',
                    $user->created_at->format('Y-m-d H:i:s'),
                    $user->updated_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
