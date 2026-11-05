@extends(auth()->user()->isSuperAdmin() ? 'layouts.super-admin' : 'layouts.admin')

@section('content')
<link rel="stylesheet" href="{{ asset('css/student-management.css') }}">
    <div class="student-management student-management-form">
        <header class="student-page-heading">
            <div>
                <a href="{{ route('students.index') }}" class="student-back-link">Back to Students</a>
                <h1>Edit Student</h1>
                <p>{{ $student->full_name }} &middot; {{ $student->student_id }}</p>
            </div>
        </header>
        <div class="form-card">
            @if ($errors->any())
                <div class="alert alert-error">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('students.update', $student) }}">
                @csrf
                @method('PUT')

                <div class="form-row">
                    <div class="form-group">
                        <label for="first_name">First Name *</label>
                        <input type="text" id="first_name" name="first_name" required value="{{ old('first_name', $student->first_name) }}">
                        @error('first_name') <span class="error">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label for="last_name">Last Name *</label>
                        <input type="text" id="last_name" name="last_name" required value="{{ old('last_name', $student->last_name) }}">
                        @error('last_name') <span class="error">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="email">Email *</label>
                        <input type="email" id="email" name="email" required value="{{ old('email', $student->email) }}">
                        @error('email') <span class="error">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label for="nrc">National Registration Card (NRC)</label>
                        <input type="text" id="nrc" name="nrc" placeholder="######/##/#" value="{{ old('nrc', $student->nrc) }}">
                        @error('nrc') <span class="error">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="phone">Phone</label>
                        <input type="tel" id="phone" name="phone" value="{{ old('phone', $student->phone) }}">
                    </div>

                    <div class="form-group">
                        <label for="date_of_birth">Date of Birth *</label>
                        <input type="date" id="date_of_birth" name="date_of_birth" required value="{{ old('date_of_birth', $student->date_of_birth->format('Y-m-d')) }}">
                        @error('date_of_birth') <span class="error">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="gender">Gender *</label>
                        <select id="gender" name="gender" required>
                            <option value="male" {{ old('gender', $student->gender) === 'male' ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ old('gender', $student->gender) === 'female' ? 'selected' : '' }}>Female</option>
                            <option value="other" {{ old('gender', $student->gender) === 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="program">Program of Study *</label>
                    <select id="program" name="program" required>
                        <option value="">Select Program</option>
                        @foreach($programs as $program)
                            <option value="{{ $program }}" {{ old('program', $student->program) === $program ? 'selected' : '' }}>{{ $program }}</option>
                        @endforeach
                    </select>
                    @error('program') <span class="error">{{ $message }}</span> @enderror
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="year">Year *</label>
                        <select id="year" name="year" required>
                            <option value="">Select Year</option>
                            <option value="1" {{ old('year', $student->year) == '1' ? 'selected' : '' }}>Year 1</option>
                            <option value="2" {{ old('year', $student->year) == '2' ? 'selected' : '' }}>Year 2</option>
                            <option value="3" {{ old('year', $student->year) == '3' ? 'selected' : '' }}>Year 3</option>
                            <option value="4" {{ old('year', $student->year) == '4' ? 'selected' : '' }}>Year 4</option>
                            <option value="5" {{ old('year', $student->year) == '5' ? 'selected' : '' }}>Year 5</option>
                        </select>
                        @error('year') <span class="error">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label for="semester">Semester *</label>
                        <select id="semester" name="semester" required>
                            <option value="">Select Semester</option>
                            <option value="1" {{ old('semester', $student->semester) == '1' ? 'selected' : '' }}>Semester 1</option>
                            <option value="2" {{ old('semester', $student->semester) == '2' ? 'selected' : '' }}>Semester 2</option>
                        </select>
                        @error('semester') <span class="error">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label for="address">Address</label>
                    <input type="text" id="address" name="address" value="{{ old('address', $student->address) }}">
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="city">City</label>
                        <input type="text" id="city" name="city" value="{{ old('city', $student->city) }}">
                    </div>

                    <div class="form-group">
                        <label for="state">State/Province</label>
                        <input type="text" id="state" name="state" value="{{ old('state', $student->state) }}">
                    </div>
                </div>

                <div class="form-group">
                    <label for="postal_code">Postal Code</label>
                    <input type="text" id="postal_code" name="postal_code" value="{{ old('postal_code', $student->postal_code) }}">
                </div>

                <div class="form-group">
                    <label for="status">Status *</label>
                    <select id="status" name="status" required>
                        <option value="active" {{ old('status', $student->status) === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status', $student->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                        <option value="graduated" {{ old('status', $student->status) === 'graduated' ? 'selected' : '' }}>Graduated</option>
                        <option value="suspended" {{ old('status', $student->status) === 'suspended' ? 'selected' : '' }}>Suspended</option>
                    </select>
                </div>

                <div class="button-group">
                    <button type="submit" class="btn btn-primary">Update Student</button>
                    <a href="{{ route('students.show', $student) }}" class="btn btn-secondary" style="text-align: center; text-decoration: none; display: flex; align-items: center; justify-content: center;">Cancel</a>
                </div>
            </form>
        </div>
    </div>
@endsection
