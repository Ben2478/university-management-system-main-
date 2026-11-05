@extends(auth()->user()->role === 'super_admin' ? 'layouts.super-admin' : (auth()->user()->role === 'admin' ? 'layouts.admin' : (auth()->user()->role === 'lecturer' ? 'layouts.lecturer' : 'layouts.app')))

@section('content')
<link rel="stylesheet" href="{{ asset('css/academic-records.css') }}?v={{ filemtime(public_path('css/academic-records.css')) }}">
<div class="container academic-records records-form-page">
    <h2>Edit Grade</h2>

    @if($errors->any())
        <div class="alert alert-error" style="background: #e74c3c; color: white; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
            <ul style="margin: 0; padding-left: 20px;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('grades.update', $grade) }}" method="POST" class="records-surface">
        @csrf
        @method('PUT')

        <div style="margin-bottom: 20px;">
            <label for="student_id" style="display: block; margin-bottom: 8px; font-weight: bold;">Student:</label>
            <select id="student_id" name="student_id" required disabled style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; background: #f5f5f5;">
                <option value="{{ $grade->student_id }}">{{ $grade->student->user->name }}</option>
            </select>
            <input type="hidden" name="student_id" value="{{ $grade->student_id }}">
        </div>

        <div style="margin-bottom: 20px;">
            <label for="course_id" style="display: block; margin-bottom: 8px; font-weight: bold;">Course:</label>
            <select id="course_id" name="course_id" required disabled style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; background: #f5f5f5;">
                <option value="{{ $grade->course_id }}">{{ $grade->course->course_name }}</option>
            </select>
            <input type="hidden" name="course_id" value="{{ $grade->course_id }}">
        </div>

        <div style="margin-bottom: 20px;">
            <label for="assignment_score" style="display: block; margin-bottom: 8px; font-weight: bold;">Assignment Score (0-100):</label>
            <input type="number" id="assignment_score" name="assignment_score" min="0" max="100" step="0.01" 
                   style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;" 
                   value="{{ old('assignment_score', $grade->assignment_score) }}" onchange="calculateOverall()">
        </div>

        <div style="margin-bottom: 20px;">
            <label for="midterm_score" style="display: block; margin-bottom: 8px; font-weight: bold;">Midterm Score (0-100):</label>
            <input type="number" id="midterm_score" name="midterm_score" min="0" max="100" step="0.01" 
                   style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;" 
                   value="{{ old('midterm_score', $grade->midterm_score) }}" onchange="calculateOverall()">
        </div>

        <div style="margin-bottom: 20px;">
            <label for="final_score" style="display: block; margin-bottom: 8px; font-weight: bold;">Final Score (0-100):</label>
            <input type="number" id="final_score" name="final_score" min="0" max="100" step="0.01" 
                   style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;" 
                   value="{{ old('final_score', $grade->final_score) }}" onchange="calculateOverall()">
        </div>

        <div style="margin-bottom: 20px;">
            <label for="overall_score" style="display: block; margin-bottom: 8px; font-weight: bold; color: #2c3e50;">Overall Score (Auto-calculated):</label>
            <input type="number" id="overall_score" name="overall_score" min="0" max="100" step="0.01" readonly
                   style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; background: #f5f5f5;" 
                   value="{{ old('overall_score', $grade->overall_score) }}">
            <small style="color: #666;">Calculated as: 30% assignment + 30% midterm + 40% final</small>
        </div>

        <div style="margin-bottom: 20px;">
            <label for="remarks" style="display: block; margin-bottom: 8px; font-weight: bold;">Remarks:</label>
            <textarea id="remarks" name="remarks" rows="3" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">{{ $grade->remarks }}</textarea>
        </div>

        <div class="records-actions records-form-actions">
            <button type="submit" class="btn-success" style="flex: 1; padding: 12px; background: #27ae60; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 16px;">Update Grade</button>
            <a href="{{ route('grades.index') }}" class="btn-secondary" style="flex: 1; padding: 12px; background: #95a5a6; color: white; border: none; border-radius: 4px; cursor: pointer; text-decoration: none; text-align: center; font-size: 16px;">Cancel</a>
        </div>
    </form>
</div>

<script>
    function calculateOverall() {
        const assignment = parseFloat(document.getElementById('assignment_score').value) || 0;
        const midterm = parseFloat(document.getElementById('midterm_score').value) || 0;
        const final = parseFloat(document.getElementById('final_score').value) || 0;

        const overall = (assignment * 0.3) + (midterm * 0.3) + (final * 0.4);
        document.getElementById('overall_score').value = overall.toFixed(2);
    }

    calculateOverall();
</script>
@endsection
