@extends(auth()->user()->isSuperAdmin() ? 'layouts.super-admin' : 'layouts.admin')

@section('content')
<link rel="stylesheet" href="{{ asset('css/student-management.css') }}">
<div class="student-management">
    <div class="student-page-heading">
        <div>
            <a href="{{ route('students.index') }}" class="student-back-link">← Back to Students</a>
            <h1>{{ $student->full_name }}</h1>
            <p>ID: {{ $student->student_id }}</p>
        </div>
        <div class="student-actions">
            <a href="{{ route('students.edit', $student) }}" class="btn">Edit</a>
            <form action="{{ route('students.destroy', $student) }}" method="POST" style="display: inline;" onsubmit="return confirm('Delete this student?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">Delete</button>
            </form>
        </div>
    </div>

    <!-- Login Credentials Alert (shown only when student is first created) -->
    @if(session('login_credentials'))
    <div class="student-credentials" role="status">
        <h3>✓ Student Account Created Successfully!</h3>
        <p style="margin-bottom: 1rem;"><strong>{{ session('login_email_status') }}</strong></p>
        <p style="margin-bottom: 1rem;">Student login credentials:</p>
        
        <div class="student-credentials-details">
            <p style="margin: 0.5rem 0;"><strong>Student ID (Login):</strong> <code style="background: #f8f9fa; padding: 0.25rem 0.5rem; border-radius: 3px;">{{ session('login_credentials')['student_id'] }}</code></p>
            <p style="margin: 0.5rem 0;"><strong>Default Password:</strong> <code style="background: #fff3cd; padding: 0.25rem 0.5rem; border-radius: 3px; color: #856404;">{{ session('login_credentials')['password'] }}</code></p>
            <p style="margin: 0.5rem 0;"><strong>Email (for records):</strong> <code style="background: #f8f9fa; padding: 0.25rem 0.5rem; border-radius: 3px;">{{ session('login_credentials')['email'] }}</code></p>
        </div>
        
        <p style="margin-top: 1rem; margin-bottom: 0; font-size: 0.9rem;">
            <strong>Important:</strong> Students sign in with their Student ID and temporary password, then must change the password. Login: <strong>{{ url('/login') }}</strong>
        </p>
    </div>
    @endif

    <!-- Balance & Payment Section -->
    <div class="student-section">
        <h2>Financial Summary</h2>
        <div class="student-stats">
            <div class="student-metric">
                <p>Year & Semester</p>
                <p class="student-metric-value">Year {{ $student->year }}, Semester {{ $student->semester }}</p>
            </div>
            <div class="student-metric">
                <p>Tuition Fee</p>
                <p class="student-metric-value">K{{ number_format($student->tuition_fee, 2) }}</p>
            </div>
            <div class="student-metric">
                <p>Amount Paid</p>
                <p class="student-metric-value">K{{ number_format($student->getPaidAmount(), 2) }}</p>
            </div>
            <div class="student-metric">
                <p>Balance Due</p>
                <p class="student-metric-value">
                    @if($student->getBalance() <= 0)
                        Fully Paid ✓
                    @else
                        K{{ number_format($student->getBalance(), 2) }}
                    @endif
                </p>
            </div>
        </div>

        <div class="student-actions">
            <a href="{{ route('receipts.create', ['student_id' => $student->id]) }}" class="btn btn-success">+ Upload Receipt</a>
            <a href="{{ route('receipts.student-receipts', $student) }}" class="btn">View Payment History</a>
        </div>
    </div>

    <!-- Personal Information -->
    <div class="student-section">
        <h2>Personal Information</h2>
        <div class="student-info-grid">
            <div>
                <p><strong>Email:</strong> {{ $student->email }}</p>
                <p><strong>Student Type:</strong> {{ $student->isShortCourse() ? 'Short Course' : 'Regular' }}</p>
                @if($student->isShortCourse())
                    <p><strong>Course Phase:</strong> {{ $student->shortCoursePhase() }}</p>
                    <p><strong>Theory:</strong> {{ $student->enrollment_date->format('M d, Y') }} – {{ $student->shortCourseTheoryEndsOn()->copy()->subDay()->format('M d, Y') }}</p>
                    <p><strong>Practical:</strong> {{ $student->shortCourseTheoryEndsOn()->format('M d, Y') }} – {{ $student->shortCourseCompletesOn()->copy()->subDay()->format('M d, Y') }}</p>
                    <p><strong>Login ends:</strong> {{ $student->shortCourseCompletesOn()->format('M d, Y') }}</p>
                @endif
                <p><strong>NRC:</strong> {{ $student->nrc ?? 'N/A' }}</p>
                <p><strong>Phone:</strong> {{ $student->phone ?? 'N/A' }}</p>
                <p><strong>Date of Birth:</strong> {{ $student->date_of_birth?->format('M d, Y') ?? 'N/A' }}</p>
                <p><strong>Gender:</strong> {{ ucfirst($student->gender) }}</p>
                <p><strong>Age:</strong> {{ $student->age ?? 'N/A' }} years old</p>
            </div>
            <div>
                <p><strong>Address:</strong> {{ $student->address ?? 'N/A' }}</p>
                <p><strong>City:</strong> {{ $student->city ?? 'N/A' }}</p>
                <p><strong>State:</strong> {{ $student->state ?? 'N/A' }}</p>
                <p><strong>Postal Code:</strong> {{ $student->postal_code ?? 'N/A' }}</p>
                <p><strong>Enrollment Date:</strong> {{ $student->enrollment_date?->format('M d, Y') ?? 'N/A' }}</p>
                <p><strong>Status:</strong> <span class="status-badge {{ $student->status }}">{{ ucfirst($student->status) }}</span></p>
            </div>
        </div>
    </div>

    <!-- Enrolled Courses -->
    <div class="student-section">
        <h2>Enrolled Courses</h2>
        @if($student->enrollments->count() > 0)
            <div class="student-table-scroll" role="region" aria-label="Enrolled courses" tabindex="0">
            <table>
                <thead>
                    <tr>
                        <th>Course Code</th>
                        <th>Course Name</th>
                        <th>Credits</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($student->enrollments as $enrollment)
                        <tr>
                            <td>{{ $enrollment->course->course_code }}</td>
                            <td>{{ $enrollment->course->name }}</td>
                            <td>{{ $enrollment->course->credits ?? 'N/A' }}</td>
                            <td><span class="badge badge-info">{{ ucfirst($enrollment->status) }}</span></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            </div>
        @else
            <p style="color: #999; text-align: center; padding: 2rem;">No courses enrolled</p>
        @endif
    </div>

    <!-- Recent Payment Receipts -->
    <div class="student-section">
        <h2>Recent Payment Receipts</h2>
        @if($student->receipts->count() > 0)
            <div class="student-table-scroll" role="region" aria-label="Recent payment receipts" tabindex="0">
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>File</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($student->receipts()->latest()->take(5)->get() as $receipt)
                        <tr>
                            <td>{{ $receipt->uploaded_at->format('M d, Y') }}</td>
                            <td>K{{ number_format($receipt->amount, 2) }}</td>
                            <td>
                                <span class="badge 
                                    @if($receipt->status === 'pending') badge-warning
                                    @elseif($receipt->status === 'approved') badge-success
                                    @else badge-danger
                                    @endif">
                                    {{ ucfirst($receipt->status) }}
                                </span>
                            </td>
                            <td>{{ Str::limit($receipt->original_filename, 25) }}</td>
                            <td><a href="{{ route('receipts.show', $receipt) }}" style="color: #3498db; text-decoration: none;">View</a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            </div>
            <div style="margin-top: 1rem; text-align: center;">
                <a href="{{ route('receipts.student-receipts', $student) }}" style="color: #3498db; text-decoration: none;">View All Receipts →</a>
            </div>
        @else
            <p style="color: #999; text-align: center; padding: 2rem;">No payment receipts uploaded yet</p>
        @endif
    </div>
</div>
@endsection
