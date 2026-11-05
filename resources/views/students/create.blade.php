@extends(auth()->user()->isSuperAdmin() ? 'layouts.super-admin' : 'layouts.admin')

@section('content')
<link rel="stylesheet" href="{{ asset('css/student-management.css') }}">
    <div class="student-management student-management-form">
        <header class="student-page-heading">
            <div>
                <a href="{{ route('students.index') }}" class="student-back-link">Back to Students</a>
                <h1>Add New Student</h1>
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

            <form method="POST" action="{{ route('students.store') }}">
                @csrf

                <div class="form-row">
                    <div class="form-group">
                        <label for="school_code">School <span style="color:#e74c3c;">*</span></label>
                        <select id="school_code" name="school_code" required>
                            <option value="">Select School</option>
                            @foreach(($schools ?? []) as $code => $label)
                                <option value="{{ $code }}" {{ old('school_code') == $code ? 'selected' : '' }}>{{ $label }} ({{ $code }})</option>
                            @endforeach
                        </select>
                        @error('school_code') <span class="error">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label for="nrc">National Registration Card (NRC) <span style="color:#e74c3c;">*</span></label>
                        <input type="text" id="nrc" name="nrc" required placeholder="######/##/#" value="{{ old('nrc') }}">
                        @error('nrc') <span class="error">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="generated_student_id">Generated Student ID</label>
                        <input type="text" id="generated_student_id" readonly value="" placeholder="Will be generated automatically" style="background:#f7f7f7;">
                        <small style="color:#666; font-size:12px; display:block; margin-top:6px;">Format: Enrollment Year + School Code + Last 3 NRC digits before the first “/”</small>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="email">Email *</label>
                        <input type="email" id="email" name="email" required value="{{ old('email') }}">
                        @error('email') <span class="error">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label for="phone">Phone</label>
                        <input type="tel" id="phone" name="phone" value="{{ old('phone') }}">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="first_name">First Name *</label>
                        <input type="text" id="first_name" name="first_name" required value="{{ old('first_name') }}">
                        @error('first_name') <span class="error">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label for="last_name">Last Name *</label>
                        <input type="text" id="last_name" name="last_name" required value="{{ old('last_name') }}">
                        @error('last_name') <span class="error">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="date_of_birth">Date of Birth *</label>
                        <input type="date" id="date_of_birth" name="date_of_birth" required value="{{ old('date_of_birth') }}">
                        @error('date_of_birth') <span class="error">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label for="gender">Gender *</label>
                        <select id="gender" name="gender" required>
                            <option value="">Select Gender</option>
                            <option value="male" {{ old('gender') === 'male' ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ old('gender') === 'female' ? 'selected' : '' }}>Female</option>
                            <option value="other" {{ old('gender') === 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                        @error('gender') <span class="error">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="enrollment_date">Enrollment Date *</label>
                        <input type="date" id="enrollment_date" name="enrollment_date" required value="{{ old('enrollment_date') }}">
                        @error('enrollment_date') <span class="error">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label for="program">Program of Study *</label>
                    <select id="program" name="program" required>
                        <option value="">Select Program</option>
                        @foreach($programs as $program)
                            <option value="{{ $program }}" {{ old('program') === $program ? 'selected' : '' }}>{{ $program }}</option>
                        @endforeach
                    </select>
                    @if($programs->isEmpty())
                        <small class="error">No active programmes are available. Add a programme before registering students.</small>
                    @endif
                    @error('program') <span class="error">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label for="student_type">Student Type *</label>
                    <select id="student_type" name="student_type" required>
                        <option value="regular" {{ old('student_type', 'regular') === 'regular' ? 'selected' : '' }}>Regular</option>
                        <option value="short_course" {{ old('student_type') === 'short_course' ? 'selected' : '' }}>Short Course (3 months)</option>
                    </select>
                    @error('student_type') <span class="error">{{ $message }}</span> @enderror
                    <small style="color: #666;">Short courses include one month of theory followed by two months of practical training.</small>
                </div>

                <div class="form-row" id="regular-period-fields">
                    <div class="form-group">
                        <label for="year">Year *</label>
                        <select id="year" name="year" required>
                            <option value="">Select Year</option>
                            <option value="1" {{ old('year') == '1' ? 'selected' : '' }}>Year 1</option>
                            <option value="2" {{ old('year') == '2' ? 'selected' : '' }}>Year 2</option>
                            <option value="3" {{ old('year') == '3' ? 'selected' : '' }}>Year 3</option>
                            <option value="4" {{ old('year') == '4' ? 'selected' : '' }}>Year 4</option>
                            <option value="5" {{ old('year') == '5' ? 'selected' : '' }}>Year 5</option>
                        </select>
                        @error('year') <span class="error">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label for="semester">Semester *</label>
                        <select id="semester" name="semester" required>
                            <option value="">Select Semester</option>
                            <option value="1" {{ old('semester') == '1' ? 'selected' : '' }}>Semester 1</option>
                            <option value="2" {{ old('semester') == '2' ? 'selected' : '' }}>Semester 2</option>
                        </select>
                        @error('semester') <span class="error">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="tuition_fee">Tuition Fee (K) *</label>
                        <input type="number" id="tuition_fee" name="tuition_fee" step="0.01" min="0" required value="{{ old('tuition_fee', 5000.00) }}">
                        @error('tuition_fee') <span class="error">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label for="status">Status *</label>
                        <select id="status" name="status" required>
                            <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            <option value="graduated" {{ old('status') == 'graduated' ? 'selected' : '' }}>Graduated</option>
                            <option value="suspended" {{ old('status') == 'suspended' ? 'selected' : '' }}>Suspended</option>
                        </select>
                        @error('status') <span class="error">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label for="address">Address</label>
                    <input type="text" id="address" name="address" value="{{ old('address') }}">
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="city">City</label>
                        <input type="text" id="city" name="city" value="{{ old('city') }}">
                    </div>

                    <div class="form-group">
                        <label for="state">State/Province</label>
                        <input type="text" id="state" name="state" value="{{ old('state') }}">
                    </div>
                </div>

                <div class="form-group">
                    <label for="postal_code">Postal Code</label>
                    <input type="text" id="postal_code" name="postal_code" value="{{ old('postal_code') }}">
                </div>

                <div class="button-group">
                    <button type="submit" class="btn btn-primary">Create Student</button>
                    <a href="{{ route('students.index') }}" class="btn btn-secondary" style="text-align: center; text-decoration: none; display: flex; align-items: center; justify-content: center;">Cancel</a>
                </div>
            </form>
        </div>
    </div>

    <script>
        const studentTypeSelect = document.getElementById('student_type');
        const regularPeriodFields = document.getElementById('regular-period-fields');

        function updateStudentTypeFields() {
            const isRegular = studentTypeSelect.value === 'regular';
            regularPeriodFields.style.display = isRegular ? 'grid' : 'none';
            regularPeriodFields.querySelectorAll('select').forEach((field) => {
                field.required = isRegular;
                field.disabled = !isRegular;
            });
        }

        studentTypeSelect.addEventListener('change', updateStudentTypeFields);
        updateStudentTypeFields();

        function computeStudentIdPreview() {
            const schoolCode = document.getElementById('school_code')?.value || '';
            const nrc = document.getElementById('nrc')?.value || '';
            const enrollmentDate = document.getElementById('enrollment_date')?.value || '';
            const out = document.getElementById('generated_student_id');

            if (!out) return;

            const beforeSlash = (nrc.split('/')[0] || '');
            const digits = (beforeSlash.match(/\d/g) || []).join('');
            const last3 = digits.length >= 3 ? digits.slice(-3) : '';
            const year = enrollmentDate ? String(new Date(enrollmentDate).getFullYear()) : '';

            if (year && schoolCode && last3) {
                out.value = `${year}${schoolCode}${last3}`;
            } else {
                out.value = '';
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            ['school_code', 'nrc', 'enrollment_date'].forEach((id) => {
                const el = document.getElementById(id);
                if (!el) return;
                el.addEventListener('input', computeStudentIdPreview);
                el.addEventListener('change', computeStudentIdPreview);
            });
            computeStudentIdPreview();
        });
    </script>
@endsection
