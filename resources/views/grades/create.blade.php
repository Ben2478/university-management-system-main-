@extends(auth()->user()->role === 'super_admin' ? 'layouts.super-admin' : (auth()->user()->role === 'admin' ? 'layouts.admin' : (auth()->user()->role === 'lecturer' ? 'layouts.lecturer' : 'layouts.app')))

@section('content')
<link rel="stylesheet" href="{{ asset('css/academic-records.css') }}?v={{ filemtime(public_path('css/academic-records.css')) }}">
<div class="container academic-records records-form-page">
    <h2>Add Grade</h2>

    @if($errors->any())
        <div class="alert alert-error" style="background: #e74c3c; color: white; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
            <ul style="margin: 0; padding-left: 20px;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('grades.store') }}" method="POST" class="records-surface">
        @csrf

        <div style="margin-bottom: 20px;">
            <label for="student_id" style="display: block; margin-bottom: 8px; font-weight: bold;">Student:</label>
            <select id="student_id" name="student_id" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
                <option value="">Select Student</option>
                @foreach(($students ?? \App\Models\Student::all()) as $student)
                    <option value="{{ $student->id }}" {{ old('student_id') == $student->id ? 'selected' : '' }}>
                        {{ $student->first_name }} {{ $student->last_name }} ({{ $student->student_id }})
                    </option>
                @endforeach
            </select>
        </div>

        <div style="margin-bottom: 20px;">
            <label for="course_id" style="display: block; margin-bottom: 8px; font-weight: bold;">Course:</label>
            <select id="course_id" name="course_id" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
                <option value="">Select Course</option>
                @php
                    $coursesByDept = ($courses ?? \App\Models\Course::all())->groupBy('department');
                @endphp
                @foreach($coursesByDept as $department => $courses)
                    <optgroup label="{{ $department ?? 'Other Courses' }}">
                        @foreach($courses as $course)
                            <option value="{{ $course->id }}" {{ old('course_id') == $course->id ? 'selected' : '' }}>
                                {{ $course->course_code }} - {{ $course->course_name }}
                            </option>
                        @endforeach
                    </optgroup>
                @endforeach
            </select>
        </div>

        <div style="margin-bottom: 20px;">
            <label for="assignment_score" style="display: block; margin-bottom: 8px; font-weight: bold;">Assignment Score (0-100):</label>
            <input type="number" id="assignment_score" name="assignment_score" min="0" max="100" step="0.01" 
                   style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;" 
                   value="{{ old('assignment_score') }}" onchange="calculateOverall()">
        </div>

        <div style="margin-bottom: 20px;">
            <label for="midterm_score" style="display: block; margin-bottom: 8px; font-weight: bold;">Midterm Score (0-100):</label>
            <input type="number" id="midterm_score" name="midterm_score" min="0" max="100" step="0.01" 
                   style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;" 
                   value="{{ old('midterm_score') }}" onchange="calculateOverall()">
        </div>

        <div style="margin-bottom: 20px;">
            <label for="final_score" style="display: block; margin-bottom: 8px; font-weight: bold;">Final Score (0-100):</label>
            <input type="number" id="final_score" name="final_score" min="0" max="100" step="0.01" 
                   style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;" 
                   value="{{ old('final_score') }}" onchange="calculateOverall()">
        </div>

        <div style="margin-bottom: 20px; background: #f0f8ff; padding: 15px; border-radius: 4px; border: 2px solid #3498db;">
            <label for="overall_score" style="display: block; margin-bottom: 8px; font-weight: bold; color: #2c3e50;">Overall Score (Auto-calculated):</label>
            <input type="number" id="overall_score" name="overall_score" min="0" max="100" step="0.01" readonly
                   style="width: 100%; padding: 10px; border: 1px solid #3498db; border-radius: 4px; background: white; font-size: 18px; font-weight: bold;" 
                   value="{{ old('overall_score') }}">
            <small style="color: #666; display: block; margin-top: 5px;">Formula: (Assignment × 30%) + (Midterm × 30%) + (Final × 40%)</small>
        </div>

        <div style="margin-bottom: 20px;">
            <label for="remarks" style="display: block; margin-bottom: 8px; font-weight: bold;">Remarks:</label>
            <textarea id="remarks" name="remarks" rows="3" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">{{ old('remarks') }}</textarea>
        </div>

        <div class="records-actions records-form-actions">
            <button type="submit" class="btn-success" style="flex: 1; padding: 12px; background: #27ae60; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 16px;">Add Grade</button>
            <a href="{{ route('grades.index') }}" class="btn-secondary" style="flex: 1; padding: 12px; background: #95a5a6; color: white; border: none; border-radius: 4px; cursor: pointer; text-decoration: none; text-align: center; font-size: 16px;">Cancel</a>
        </div>
    </form>

    <script>
        function calculateOverall() {
            const assignment = parseFloat(document.getElementById('assignment_score').value) || 0;
            const midterm = parseFloat(document.getElementById('midterm_score').value) || 0;
            const final = parseFloat(document.getElementById('final_score').value) || 0;
            
            // Weighted calculation: Assignment 30%, Midterm 30%, Final 40%
            const overall = (assignment * 0.3) + (midterm * 0.3) + (final * 0.4);
            
            document.getElementById('overall_score').value = overall.toFixed(2);
        }
    </script>
</div>
@endsection
