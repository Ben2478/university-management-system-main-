@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <a href="{{ route('users.index') }}" class="text-blue-600 hover:text-blue-900">&larr; Back to Users</a>
        <h1 class="text-3xl font-bold text-gray-900 mt-4">User Details</h1>
    </div>

    @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 text-green-700 rounded">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- User Info Card -->
        <div class="md:col-span-2 bg-white rounded-lg shadow p-8">
            <div class="flex justify-between items-start mb-6">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">{{ $user->name }}</h2>
                    <p class="text-gray-600">{{ $user->email }}</p>
                </div>
                <span class="px-4 py-2 rounded-full text-sm font-semibold
                    @if($user->role === 'super_admin') bg-purple-100 text-purple-800
                    @elseif($user->role === 'admin') bg-green-100 text-green-800
                    @elseif($user->role === 'student') bg-yellow-100 text-yellow-800
                    @else bg-pink-100 text-pink-800
                    @endif">
                    {{ ucfirst(str_replace('_', ' ', $user->role)) }}
                </span>
            </div>

            <hr class="my-6">

            <div class="grid grid-cols-2 gap-6">
                <div>
                    <p class="text-sm text-gray-600">User ID</p>
                    <p class="text-lg font-medium text-gray-900">#{{ $user->id }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Phone</p>
                    <p class="text-lg font-medium text-gray-900">{{ $user->phone ?? 'Not provided' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Status</p>
                    <p class="text-lg font-medium">
                        @if($user->deleted_at)
                            <span class="text-red-600">Inactive</span>
                        @else
                            <span class="text-green-600">Active</span>
                        @endif
                    </p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Account Age</p>
                    <p class="text-lg font-medium text-gray-900">{{ $activityStats['account_age_days'] }} days</p>
                </div>
            </div>

            @if($user->address)
                <hr class="my-6">
                <div>
                    <p class="text-sm text-gray-600">Address</p>
                    <p class="text-gray-900 mt-1">{{ $user->address }}</p>
                </div>
            @endif

            <!-- Related Student Record -->
            @if($user->role === 'student' && $user->student)
                <hr class="my-6">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">📚 Student Record</h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-600">Student ID</p>
                            <p class="font-medium">{{ $user->student->student_id }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Program</p>
                            <p class="font-medium">{{ $user->student->program ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Year/Semester</p>
                            <p class="font-medium">{{ $user->student->year ?? 'N/A' }} / {{ $user->student->semester ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Status</p>
                            <p class="font-medium">{{ ucfirst($user->student->status) }}</p>
                        </div>
                    </div>
                    <a href="{{ route('students.show', $user->student) }}" class="inline-block mt-4 text-blue-600 hover:text-blue-900">
                        View Full Student Profile →
                    </a>
                </div>
            @endif

            <!-- Children (if parent) -->
            @if($user->role === 'parent' && $user->childrenAsParent->count() > 0)
                <hr class="my-6">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">👨‍👩‍👧‍👦 Children</h3>
                    <div class="space-y-3">
                        @foreach($user->childrenAsParent as $child)
                            <div class="flex justify-between items-center p-3 bg-gray-50 rounded">
                                <div>
                                    <p class="font-medium">{{ $child->first_name }} {{ $child->last_name }}</p>
                                    <p class="text-sm text-gray-600">{{ $child->student_id }} - {{ $child->program ?? 'N/A' }}</p>
                                </div>
                                <a href="{{ route('students.show', $child) }}" class="text-blue-600 hover:text-blue-900 text-sm">
                                    View Profile →
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <!-- Activity & Actions Sidebar -->
        <div class="space-y-6">
            <!-- Activity Stats -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">📊 Activity</h3>
                <div class="space-y-4">
                    <div>
                        <p class="text-sm text-gray-600">Registered</p>
                        <p class="font-medium">{{ $user->created_at->format('M d, Y') }}</p>
                        <p class="text-xs text-gray-500">{{ $user->created_at->diffForHumans() }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Last Updated</p>
                        <p class="font-medium">{{ $user->updated_at->format('M d, Y') }}</p>
                        <p class="text-xs text-gray-500">{{ $user->updated_at->diffForHumans() }}</p>
                    </div>
                    @if($user->deleted_at)
                        <div>
                            <p class="text-sm text-gray-600">Deactivated</p>
                            <p class="font-medium text-red-600">{{ $user->deleted_at->format('M d, Y') }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Actions -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">⚙️ Actions</h3>
                <div class="space-y-3">
                    @if(!$user->deleted_at)
                        <a href="{{ route('users.edit', $user) }}" class="block w-full text-center bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md">
                            Edit User
                        </a>
                        
                        <!-- Password Reset Button -->
                        <button onclick="document.getElementById('reset-password-modal').classList.remove('hidden')" 
                                class="block w-full text-center bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md">
                            Reset Password
                        </button>

                        @if($user->id !== auth()->id())
                            <form action="{{ route('users.destroy', $user) }}" method="POST" 
                                  onsubmit="return confirm('Are you sure you want to deactivate this user?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="block w-full text-center bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md">
                                    Deactivate User
                                </button>
                            </form>
                        @endif
                    @else
                        <form action="{{ route('users.restore', $user->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="block w-full text-center bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md">
                                Restore User
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Password Reset Modal -->
<div id="reset-password-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg p-8 max-w-md w-full mx-4">
        <h3 class="text-xl font-bold text-gray-900 mb-4">Reset Password for {{ $user->name }}</h3>
        <form action="{{ route('users.reset-password', $user) }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">New Password</label>
                <input type="password" name="new_password" required minlength="8"
                       class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Confirm Password</label>
                <input type="password" name="new_password_confirmation" required minlength="8"
                       class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div class="flex gap-3">
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-md">
                    Reset Password
                </button>
                <button type="button" onclick="document.getElementById('reset-password-modal').classList.add('hidden')" 
                        class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-6 py-2 rounded-md">
                    Cancel
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
