@extends(auth()->user()->isStudent() ? 'layouts.student' : (auth()->user()->isAdmin() ? 'layouts.admin' : (auth()->user()->isSuperAdmin() ? 'layouts.super-admin' : 'layouts.parent')))

@section('content')
<div class="container" style="padding: 2rem 0;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <div>
            <h1 style="margin: 0 0 0.5rem 0;">Profile Settings</h1>
            <p style="margin: 0; color: #666;">Manage your account information and preferences</p>
        </div>
    </div>

    @if(session('success'))
        <div style="padding: 1rem; margin-bottom: 2rem; background: #d4edda; border-left: 4px solid #27ae60; border-radius: 0.25rem;">
            <span style="color: #155724; font-weight: 600;">✓ {{ session('success') }}</span>
        </div>
    @endif

    @if($errors->any())
        <div style="padding: 1rem; margin-bottom: 2rem; background: #f8d7da; border-left: 4px solid #e74c3c; border-radius: 0.25rem;">
            <ul style="margin: 0; padding-left: 1.5rem; color: #721c24;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 2rem;">
        <!-- User Info Card -->
        <div class="card">
            <div style="text-align: center; padding-bottom: 1.5rem; border-bottom: 1px solid #ecf0f1;">
                <div style="width: 100px; height: 100px; margin: 0 auto 1rem; border-radius: 50%; background: linear-gradient(135deg, 
                    {{ auth()->user()->isSuperAdmin() ? '#2c3e50, #34495e' : (auth()->user()->isAdmin() ? '#c0392b, #e74c3c' : (auth()->user()->isStudent() ? '#2980b9, #3498db' : '#27ae60, #2ecc71')) }});
                    display: flex; align-items: center; justify-content: center; color: white; font-size: 2.5rem; font-weight: bold;">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <h3 style="margin: 0 0 0.5rem 0;">{{ auth()->user()->name }}</h3>
                <span style="padding: 0.375rem 0.75rem; border-radius: 0.25rem; font-size: 0.875rem; font-weight: 600; background-color: 
                    {{ auth()->user()->isSuperAdmin() ? '#2c3e50' : (auth()->user()->isAdmin() ? '#c0392b' : (auth()->user()->isStudent() ? '#2980b9' : '#27ae60')) }};
                    color: white;">
                    @if(auth()->user()->isSuperAdmin())
                        Super Administrator
                    @elseif(auth()->user()->isAdmin())
                        Accountant
                    @elseif(auth()->user()->isStudent())
                        Student
                    @else
                        Parent/Guardian
                    @endif
                </span>
            </div>

            @if(auth()->user()->isStudent() && auth()->user()->student)
            <div style="padding-top: 1.5rem;">
                <h3 style="margin: 0 0 1rem 0; color: #2c3e50;">Student Information</h3>
                <div style="margin-bottom: 0.75rem;">
                    <p style="margin: 0; color: #666; font-size: 0.75rem; text-transform: uppercase;">Student ID</p>
                    <p style="margin: 0.25rem 0 0 0; font-weight: 600; color: #2c3e50;">{{ auth()->user()->student->student_id }}</p>
                </div>
                <div style="margin-bottom: 0.75rem;">
                    <p style="margin: 0; color: #666; font-size: 0.75rem; text-transform: uppercase;">Program</p>
                    <p style="margin: 0.25rem 0 0 0; font-weight: 600; color: #2c3e50;">{{ auth()->user()->student->program ?? 'Not assigned' }}</p>
                </div>
                <div style="margin-bottom: 0.75rem;">
                    <p style="margin: 0; color: #666; font-size: 0.75rem; text-transform: uppercase;">Year/Semester</p>
                    <p style="margin: 0.25rem 0 0 0; font-weight: 600; color: #2c3e50;">Year {{ auth()->user()->student->year ?? 'N/A' }} - Semester {{ auth()->user()->student->semester ?? 'N/A' }}</p>
                </div>
                <div style="margin-bottom: 0.75rem;">
                    <p style="margin: 0; color: #666; font-size: 0.75rem; text-transform: uppercase;">Status</p>
                    <span style="padding: 0.25rem 0.5rem; background: {{ auth()->user()->student->status === 'active' ? '#d4edda' : '#f8d7da' }}; color: {{ auth()->user()->student->status === 'active' ? '#155724' : '#721c24' }}; border-radius: 0.25rem; font-size: 0.75rem; font-weight: 600; text-transform: uppercase;">
                        {{ auth()->user()->student->status ?? 'Unknown' }}
                    </span>
                </div>
            </div>
            <hr style="margin: 1.5rem 0; border: none; border-top: 1px solid #ecf0f1;">
            @endif

            <div>
                <h3 style="margin: 0 0 1rem 0; color: #2c3e50;">Account Details</h3>
                <div style="margin-bottom: 0.75rem;">
                    <p style="margin: 0; color: #666; font-size: 0.75rem; text-transform: uppercase;">Email</p>
                    <p style="margin: 0.25rem 0 0 0; font-weight: 500; color: #2c3e50; word-break: break-word;">{{ auth()->user()->email }}</p>
                </div>
                <div style="margin-bottom: 0.75rem;">
                    <p style="margin: 0; color: #666; font-size: 0.75rem; text-transform: uppercase;">Phone</p>
                    <p style="margin: 0.25rem 0 0 0; font-weight: 500; color: #2c3e50;">{{ auth()->user()->phone ?? 'Not provided' }}</p>
                </div>
                <div style="margin-bottom: 0.75rem;">
                    <p style="margin: 0; color: #666; font-size: 0.75rem; text-transform: uppercase;">Member Since</p>
                    <p style="margin: 0.25rem 0 0 0; font-weight: 500; color: #2c3e50;">{{ auth()->user()->created_at->format('M d, Y') }}</p>
                </div>
                <div style="margin-bottom: 0.75rem;">
                    <p style="margin: 0; color: #666; font-size: 0.75rem; text-transform: uppercase;">Last Login</p>
                    <p style="margin: 0.25rem 0 0 0; font-weight: 500; color: #2c3e50;">{{ auth()->user()->updated_at->diffForHumans() }}</p>
                </div>
            </div>
        </div>

        <!-- Edit Form -->
        <div class="card">
            <h3 style="margin: 0 0 1.5rem 0; color: #2c3e50; border-bottom: 2px solid #3498db; padding-bottom: 0.75rem;">✏️ Edit Profile Information</h3>

            <form action="{{ route('profile.update') }}" method="POST">
                @csrf
                @method('PUT')

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
                    <div>
                        <label for="name" style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #2c3e50;">Full Name *</label>
                        <input 
                            type="text" 
                            id="name" 
                            name="name" 
                            value="{{ old('name', auth()->user()->name) }}"
                            style="width: 100%; padding: 0.75rem; border: 2px solid #ecf0f1; border-radius: 0.375rem; font-size: 1rem; transition: border-color 0.2s;"
                            onfocus="this.style.borderColor='#3498db'" 
                            onblur="this.style.borderColor='#ecf0f1'"
                            required
                        >
                        @error('name')
                            <span style="color: #e74c3c; font-size: 0.875rem; display: block; margin-top: 0.25rem;">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label for="email" style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #2c3e50;">Email Address *</label>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            value="{{ old('email', auth()->user()->email) }}"
                            style="width: 100%; padding: 0.75rem; border: 2px solid #ecf0f1; border-radius: 0.375rem; font-size: 1rem; transition: border-color 0.2s;"
                            onfocus="this.style.borderColor='#3498db'" 
                            onblur="this.style.borderColor='#ecf0f1'"
                            required
                        >
                        @error('email')
                            <span style="color: #e74c3c; font-size: 0.875rem; display: block; margin-top: 0.25rem;">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
                    <div>
                        <label for="phone" style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #2c3e50;">Phone Number</label>
                        <input 
                            type="tel" 
                            id="phone" 
                            name="phone" 
                            value="{{ old('phone', auth()->user()->phone) }}"
                            style="width: 100%; padding: 0.75rem; border: 2px solid #ecf0f1; border-radius: 0.375rem; font-size: 1rem; transition: border-color 0.2s;"
                            onfocus="this.style.borderColor='#3498db'" 
                            onblur="this.style.borderColor='#ecf0f1'"
                            placeholder="+260 XXX XXX XXX"
                        >
                        @error('phone')
                            <span style="color: #e74c3c; font-size: 0.875rem; display: block; margin-top: 0.25rem;">{{ $message }}</span>
                        @enderror
                    </div>

                    @if(auth()->user()->isStudent() && auth()->user()->student)
                    <div>
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #2c3e50;">Student ID</label>
                        <input 
                            type="text" 
                            value="{{ auth()->user()->student->student_id }}"
                            style="width: 100%; padding: 0.75rem; border: 2px solid #ecf0f1; border-radius: 0.375rem; font-size: 1rem; background: #f8f9fa; color: #666;"
                            readonly
                        >
                        <span style="color: #666; font-size: 0.75rem; display: block; margin-top: 0.25rem;">🔒 Read-only field</span>
                    </div>
                    @endif
                </div>

                <div style="margin-bottom: 1.5rem;">
                    <label for="address" style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #2c3e50;">Home Address</label>
                    <textarea 
                        id="address" 
                        name="address" 
                        style="width: 100%; padding: 0.75rem; border: 2px solid #ecf0f1; border-radius: 0.375rem; font-size: 1rem; resize: vertical; min-height: 100px; transition: border-color 0.2s;"
                        onfocus="this.style.borderColor='#3498db'" 
                        onblur="this.style.borderColor='#ecf0f1'"
                        placeholder="Enter your full address"
                    >{{ old('address', auth()->user()->address) }}</textarea>
                    @error('address')
                        <span style="color: #e74c3c; font-size: 0.875rem; display: block; margin-top: 0.25rem;">{{ $message }}</span>
                    @enderror
                </div>

                @if(auth()->user()->isStudent() && auth()->user()->student)
                <div style="padding: 1rem; background: #e8f4f8; border-left: 4px solid #3498db; border-radius: 0.375rem; margin-bottom: 1.5rem;">
                    <p style="margin: 0; color: #2c3e50; font-size: 0.875rem;">
                        <strong>ℹ️ Note:</strong> To update your academic information (Program, Year, Semester), please contact the registrar's office.
                    </p>
                </div>
                @endif

                <hr style="margin: 2rem 0; border: none; border-top: 2px solid #ecf0f1;">

                <h4 style="margin: 0 0 1.5rem 0; color: #2c3e50; border-bottom: 2px solid #e74c3c; padding-bottom: 0.75rem;">🔒 Change Password (Optional)</h4>

                <div style="padding: 1rem; background: #fff3cd; border-left: 4px solid #ffc107; border-radius: 0.375rem; margin-bottom: 1.5rem;">
                    <p style="margin: 0; color: #856404; font-size: 0.875rem;">
                        <strong>⚠️ Password Requirements:</strong> Must be at least 8 characters long. Leave blank to keep your current password.
                    </p>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
                    <div>
                        <label for="password" style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #2c3e50;">New Password</label>
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            style="width: 100%; padding: 0.75rem; border: 2px solid #ecf0f1; border-radius: 0.375rem; font-size: 1rem; transition: border-color 0.2s;"
                            onfocus="this.style.borderColor='#3498db'" 
                            onblur="this.style.borderColor='#ecf0f1'"
                            placeholder="Enter new password"
                        >
                        @error('password')
                            <span style="color: #e74c3c; font-size: 0.875rem; display: block; margin-top: 0.25rem;">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #2c3e50;">Confirm Password</label>
                        <input 
                            type="password" 
                            id="password_confirmation" 
                            name="password_confirmation" 
                            style="width: 100%; padding: 0.75rem; border: 2px solid #ecf0f1; border-radius: 0.375rem; font-size: 1rem; transition: border-color 0.2s;"
                            onfocus="this.style.borderColor='#3498db'" 
                            onblur="this.style.borderColor='#ecf0f1'"
                            placeholder="Confirm new password"
                        >
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-top: 2rem;">
                    <button type="submit" class="btn" style="background: #27ae60; color: white; padding: 1rem; border: none; border-radius: 0.375rem; font-size: 1rem; font-weight: 600; cursor: pointer; transition: background 0.2s;" onmouseover="this.style.background='#229954'" onmouseout="this.style.background='#27ae60'">
                        💾 Save Changes
                    </button>
                    <a href="{{ route('dashboard') }}" class="btn" style="background: #95a5a6; color: white; padding: 1rem; text-align: center; text-decoration: none; border-radius: 0.375rem; font-size: 1rem; font-weight: 600; display: flex; align-items: center; justify-content: center; transition: background 0.2s;" onmouseover="this.style.background='#7f8c8d'" onmouseout="this.style.background='#95a5a6'">
                        ✖️ Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Security Tips Card -->
    <div class="card" style="margin-top: 2rem; background: #f8f9fa; border-left: 4px solid #9b59b6;">
        <h3 style="margin: 0 0 1rem 0; color: #2c3e50;">🛡️ Account Security Tips</h3>
        <ul style="margin: 0; padding-left: 1.5rem; color: #555; line-height: 1.8;">
            <li><strong>Strong Password:</strong> Use a mix of uppercase, lowercase, numbers, and special characters</li>
            <li><strong>Don't Share:</strong> Never share your password with anyone, including administrators</li>
            <li><strong>Regular Updates:</strong> Change your password every 3-6 months for better security</li>
            <li><strong>Logout:</strong> Always logout when using shared or public computers</li>
            <li><strong>Suspicious Activity:</strong> Report any unusual account activity immediately to IT support</li>
        </ul>
    </div>
</div>
@endsection
