<p>Hello {{ $student->first_name }},</p>

<p>Your Phoenix University student account has been created.</p>

<p><strong>Student ID (login):</strong> {{ $student->student_id }}</p>
<p><strong>Email on your account:</strong> {{ $student->email }}</p>
<p><strong>Temporary password:</strong> {{ $temporaryPassword }}</p>

@if($student->isShortCourse())
	<p><strong>Course type:</strong> Short Course</p>
	<p><strong>Theory:</strong> {{ $student->enrollment_date->format('M d, Y') }} to {{ $student->shortCourseTheoryEndsOn()->copy()->subDay()->format('M d, Y') }}</p>
	<p><strong>Practical:</strong> {{ $student->shortCourseTheoryEndsOn()->format('M d, Y') }} to {{ $student->shortCourseCompletesOn()->copy()->subDay()->format('M d, Y') }}</p>
	<p>Your login will be deactivated when the course completes on {{ $student->shortCourseCompletesOn()->format('M d, Y') }}.</p>
@endif

<p>Sign in at <a href="{{ url('/login') }}">{{ url('/login') }}</a>. You will be asked to change your temporary password after signing in.</p>

<p>Please keep these details private.</p>