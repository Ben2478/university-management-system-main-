@extends('layouts.app')

@section('content')
<div class="container">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <div>
            <a href="{{ route('students.index') }}" style="color: #3498db; text-decoration: none;">← Back to Students</a>
            <h1 style="margin-top: 0.5rem;">{{ $student->full_name }}</h1>
            <p style="color: #666;">ID: {{ $student->student_id }}</p>
        </div>
        <div style="display: flex; gap: 1rem;">
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
    <div style="background: #d4edda; border: 1px solid #c3e6cb; color: #155724; padding: 1.5rem; border-radius: 8px; margin-bottom: 2rem;">
        <h3 style="margin-top: 0; color: #155724;">✓ Student Account Created Successfully!</h3>
        <p style="margin-bottom: 1rem;"><strong>Login credentials have been generated. Please save these details and share them with the student:</strong></p>
        
        <div style="background: white; padding: 1rem; border-radius: 5px; border: 1px solid #c3e6cb;">
            <p style="margin: 0.5rem 0;"><strong>Email/Username:</strong> <code style="background: #f8f9fa; padding: 0.25rem 0.5rem; border-radius: 3px;">{{ session('login_credentials')['email'] }}</code></p>
            <p style="margin: 0.5rem 0;"><strong>Student ID:</strong> <code style="background: #f8f9fa; padding: 0.25rem 0.5rem; border-radius: 3px;">{{ session('login_credentials')['student_id'] }}</code></p>
            <p style="margin: 0.5rem 0;"><strong>Temporary Password:</strong> <code style="background: #fff3cd; padding: 0.25rem 0.5rem; border-radius: 3px; color: #856404;">{{ session('login_credentials')['password'] }}</code></p>
        </div>
        
        <p style="margin-top: 1rem; margin-bottom: 0; font-size: 0.9rem;">
            <strong>⚠️ Important:</strong> These credentials will only be shown once. Make sure to copy them or print this page. 
            The student can use these credentials to log in at <strong>{{ url('/login') }}</strong>
        </p>
    </div>
    @endif

    <!-- Balance & Payment Section -->
    <div class="card" style="margin-bottom: 2rem;">
        <h2>Financial Summary</h2>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
            <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 1.5rem; border-radius: 8px;">
                <p style="opacity: 0.9;">Year & Semester</p>
                <p style="font-size: 1.5rem; font-weight: bold;">Year {{ $student->year }}, Semester {{ $student->semester }}</p>
            </div>
            <div style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white; padding: 1.5rem; border-radius: 8px;">
                <p style="opacity: 0.9;">Tuition Fee</p>
                <p style="font-size: 1.5rem; font-weight: bold;">K{{ number_format($student->tuition_fee, 2) }}</p>
            </div>
            <div style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: white; padding: 1.5rem; border-radius: 8px;">
                <p style="opacity: 0.9;">Amount Paid</p>
                <p style="font-size: 1.5rem; font-weight: bold;">K{{ number_format($student->getPaidAmount(), 2) }}</p>
            </div>
            <div style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); color: white; padding: 1.5rem; border-radius: 8px;">
                <p style="opacity: 0.9;">Balance Due</p>
                <p style="font-size: 1.5rem; font-weight: bold;">
                    @if($student->getBalance() <= 0)
                        Fully Paid ✓
                    @else
                        K{{ number_format($student->getBalance(), 2) }}
                    @endif
                </p>
            </div>
        </div>

        <div style="display: flex; gap: 1rem;">
            <a href="{{ route('receipts.create', ['student_id' => $student->id]) }}" class="btn btn-success">+ Upload Receipt</a>
            <a href="{{ route('receipts.student-receipts', $student) }}" class="btn">View Payment History</a>
        </div>
    </div>

    <!-- Personal Information -->
    <div class="card" style="margin-bottom: 2rem;">
        <h2>Personal Information</h2>
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
            <div>
                <p><strong>Email:</strong> {{ $student->email }}</p>
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
                <p><strong>Status:</strong> <span class="badge badge-success">{{ ucfirst($student->status) }}</span></p>
            </div>
        </div>
    </div>

    <!-- Enrolled Courses -->
    <div class="card" style="margin-bottom: 2rem;">
        <h2>Enrolled Courses</h2>
        @if($student->enrollments->count() > 0)
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
        @else
            <p style="color: #999; text-align: center; padding: 2rem;">No courses enrolled</p>
        @endif
    </div>

    <!-- Recent Payment Receipts -->
    <div class="card">
        <h2>Recent Payment Receipts</h2>
        @if($student->receipts->count() > 0)
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
            <div style="margin-top: 1rem; text-align: center;">
                <a href="{{ route('receipts.student-receipts', $student) }}" style="color: #3498db; text-decoration: none;">View All Receipts →</a>
            </div>
        @else
            <p style="color: #999; text-align: center; padding: 2rem;">No payment receipts uploaded yet</p>
        @endif
    </div>
</div>
@endsection
