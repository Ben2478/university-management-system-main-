@extends(auth()->user()->role === 'super_admin' ? 'layouts.super-admin' : (auth()->user()->role === 'admin' ? 'layouts.admin' : 'layouts.app'))

@section('content')
<div class="container" style="max-width: 600px; margin: 30px auto;">
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

    <form action="{{ route('grades.update', $grade) }}" method="POST" style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
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
            <label for="score" style="display: block; margin-bottom: 8px; font-weight: bold;">Score (0-100):</label>
            <input type="number" id="score" name="score" min="0" max="100" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;" value="{{ $grade->score }}">
        </div>

        <div style="margin-bottom: 20px;">
            <label for="grade_letter" style="display: block; margin-bottom: 8px; font-weight: bold;">Grade Letter:</label>
            <select id="grade_letter" name="grade_letter" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
                <option value="A" {{ $grade->grade_letter == 'A' ? 'selected' : '' }}>A (90-100)</option>
                <option value="B" {{ $grade->grade_letter == 'B' ? 'selected' : '' }}>B (80-89)</option>
                <option value="C" {{ $grade->grade_letter == 'C' ? 'selected' : '' }}>C (70-79)</option>
                <option value="D" {{ $grade->grade_letter == 'D' ? 'selected' : '' }}>D (60-69)</option>
                <option value="F" {{ $grade->grade_letter == 'F' ? 'selected' : '' }}>F (Below 60)</option>
            </select>
        </div>

        <div style="margin-bottom: 20px;">
            <label for="remarks" style="display: block; margin-bottom: 8px; font-weight: bold;">Remarks:</label>
            <textarea id="remarks" name="remarks" rows="3" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">{{ $grade->remarks }}</textarea>
        </div>

        <div style="display: flex; gap: 10px;">
            <button type="submit" class="btn-success" style="flex: 1; padding: 12px; background: #27ae60; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 16px;">Update Grade</button>
            <a href="{{ route('grades.index') }}" class="btn-secondary" style="flex: 1; padding: 12px; background: #95a5a6; color: white; border: none; border-radius: 4px; cursor: pointer; text-decoration: none; text-align: center; font-size: 16px;">Cancel</a>
        </div>
    </form>
</div>
@endsection
