@extends('layouts.student')

@section('content')
<style>
    .student-dashboard-main-grid { display:grid; grid-template-columns:minmax(0,2fr) minmax(280px,1fr); gap:1.25rem; align-items:start; }
    .student-dashboard-main-grid > * { min-width:0; }
    .student-dashboard-main-grid .card { border:1px solid #e8e5ee; border-radius:.65rem; box-shadow:0 2px 8px rgba(37,27,57,.045); }
    @media (max-width:900px) { .student-dashboard-main-grid { grid-template-columns:minmax(0,1fr); gap:1rem; } }
    @media (max-width:600px) {
        .student-dashboard-main-grid { gap:.8rem; }
        .student-dashboard-main-grid .card { padding:1rem; }
        .student-dashboard-main-grid table { min-width:520px; }
    }
</style>
<div class="container dashboard-workspace" data-dashboard="student">
    <!-- Welcome Section -->
    @include('components.dashboard-heading', ['area' => 'Student workspace', 'title' => 'My learning overview', 'description' => $student->first_name . ' ' . $student->last_name . ' | ' . $student->student_id . ' | ' . $student->program])

    <!-- Alerts Section -->
    @if(!empty($alerts))
    <div style="display: grid; gap: 1rem; margin-bottom: 2rem;">
        @foreach($alerts as $alert)
        <div style="padding: 1.25rem; background: {{ $alert['type'] === 'warning' ? '#fff3cd' : '#cfe2ff' }}; border-left: 4px solid {{ $alert['type'] === 'warning' ? '#ffc107' : '#0d6efd' }}; border-radius: 0.5rem; display: flex; align-items: center; justify-content: space-between; gap: 1rem;">
            <div style="display: flex; align-items: center; gap: 1rem; flex: 1;">
                <span style="font-size: 2rem;">{{ $alert['icon'] }}</span>
                <p style="margin: 0; color: {{ $alert['type'] === 'warning' ? '#856404' : '#084298' }}; font-weight: 500;">
                    {{ $alert['message'] }}
                </p>
            </div>
            <a href="{{ $alert['action'] }}" style="padding: 0.5rem 1rem; background: {{ $alert['type'] === 'warning' ? '#ffc107' : '#0d6efd' }}; color: white; border-radius: 0.375rem; text-decoration: none; font-weight: 600; white-space: nowrap;">
                {{ $alert['action_text'] }}
            </a>
        </div>
        @endforeach
    </div>
    @endif

    <!-- Stats Grid -->
    @include('components.dashboard-metrics', ['metrics' => [
        ['label' => 'Enrolled Courses', 'value' => $stats['enrolled_courses'], 'detail' => $stats['total_credits'] . ' total credits', 'icon' => 'courses', 'tone' => 'blue'],
        ['label' => 'Current GPA', 'value' => number_format($stats['gpa'], 2), 'detail' => $stats['total_grades'] . ' courses graded', 'icon' => 'grades'],
        ['label' => 'Amount Paid', 'value' => 'K' . number_format($stats['amount_paid'], 2), 'detail' => 'of K' . number_format($student->tuition_fee, 2), 'icon' => 'payments', 'tone' => 'green'],
        ['label' => 'Balance Due', 'value' => 'K' . number_format($stats['balance_due'], 2), 'detail' => ($student->tuition_fee > 0 ? round($stats['amount_paid'] / $student->tuition_fee * 100) : 0) . '% paid', 'icon' => 'receipts', 'tone' => 'rose'],
    ]])

    <!-- Main Content Grid -->
    <div class="student-dashboard-main-grid">
        <!-- Left Column -->
        <div style="display: grid; gap: 2rem;">
            <!-- Active Courses -->
            <div class="card">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                    <h3 style="margin: 0; color: #2c3e50;">📚 My Active Courses</h3>
                    <a href="{{ route('enrollments.available') }}" style="padding: 0.5rem 1rem; background: #3498db; color: white; border-radius: 0.375rem; text-decoration: none; font-size: 0.875rem; font-weight: 600;">
                        + Enroll More
                    </a>
                </div>

                @if($enrollments->isEmpty())
                    <div style="text-align: center; padding: 2rem; background: #f8f9fa; border-radius: 0.5rem;">
                        <p style="color: #999; font-size: 1rem; margin-bottom: 1rem;">📖 No active courses</p>
                        <a href="{{ route('enrollments.available') }}" style="padding: 0.75rem 1.5rem; background: #3498db; color: white; border-radius: 0.375rem; text-decoration: none; font-weight: 600;">
                            Browse Available Courses
                        </a>
                    </div>
                @else
                    <div style="display: grid; gap: 1rem;">
                        @foreach($enrollments as $enrollment)
                        <div style="padding: 1.25rem; border: 2px solid #ecf0f1; border-radius: 0.5rem; transition: all 0.2s;" onmouseover="this.style.borderColor='#3498db'; this.style.background='#f8f9fa'" onmouseout="this.style.borderColor='#ecf0f1'; this.style.background='white'">
                            <div style="display: flex; justify-content: space-between; align-items: start; gap: 1rem;">
                                <div style="flex: 1;">
                                    <h4 style="margin: 0 0 0.5rem 0; color: #2c3e50;">{{ $enrollment->course->course_name }}</h4>
                                    <div style="display: flex; gap: 1rem; flex-wrap: wrap; margin-bottom: 0.5rem;">
                                        <span style="color: #666; font-size: 0.875rem;">📋 {{ $enrollment->course->course_code }}</span>
                                        <span style="color: #666; font-size: 0.875rem;">🎓 {{ $enrollment->course->credits }} Credits</span>
                                        <span style="color: #666; font-size: 0.875rem;">Term {{ $enrollment->course->term }}</span>
                                    </div>
                                    @if($enrollment->course->instructor)
                                    <p style="margin: 0; color: #999; font-size: 0.75rem;">👨‍🏫 {{ $enrollment->course->instructor }}</p>
                                    @endif
                                </div>
                                <div style="text-align: right;">
                                    <span style="padding: 0.375rem 0.75rem; border-radius: 0.25rem; font-size: 0.875rem; font-weight: 600; background: #d4edda; color: #155724; display: inline-block; margin-bottom: 0.5rem;">
                                        {{ ucfirst($enrollment->status) }}
                                    </span>
                                    @if($enrollment->enrolled_at)
                                    <p style="margin: 0; color: #999; font-size: 0.75rem;">Enrolled {{ $enrollment->enrolled_at->diffForHumans() }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @if($stats['enrolled_courses'] > 6)
                    <a href="{{ route('enrollments.index') }}" style="display: block; text-align: center; margin-top: 1rem; color: #3498db; text-decoration: none; font-weight: 600;">
                        View All {{ $stats['enrolled_courses'] }} Courses →
                    </a>
                    @endif
                @endif
            </div>

            <!-- Recent Grades -->
            <div class="card">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                    <h3 style="margin: 0; color: #2c3e50;">📊 Recent Grades</h3>
                    <a href="{{ route('grades.index') }}" style="color: #3498db; text-decoration: none; font-size: 0.875rem; font-weight: 600;">
                        View All →
                    </a>
                </div>

                @if($recentGrades->isEmpty())
                    <div style="text-align: center; padding: 2rem; background: #f8f9fa; border-radius: 0.5rem;">
                        <p style="color: #999; margin: 0;">📝 No grades available yet</p>
                    </div>
                @else
                    <div style="overflow-x: auto;">
                        <table style="width: 100%; border-collapse: collapse;">
                            <thead>
                                <tr style="background: #f8f9fa; border-bottom: 2px solid #dee2e6;">
                                    <th style="text-align: left; padding: 0.75rem; font-size: 0.875rem; color: #666;">Course</th>
                                    <th style="text-align: center; padding: 0.75rem; font-size: 0.875rem; color: #666;">Score</th>
                                    <th style="text-align: center; padding: 0.75rem; font-size: 0.875rem; color: #666;">Grade</th>
                                    <th style="text-align: center; padding: 0.75rem; font-size: 0.875rem; color: #666;">Credits</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentGrades as $grade)
                                <tr style="border-bottom: 1px solid #ecf0f1;">
                                    <td style="padding: 0.75rem;">
                                        <div>
                                            <span style="font-weight: 600; color: #2c3e50;">{{ $grade->course->course_name ?? 'N/A' }}</span>
                                            <br>
                                            <span style="font-size: 0.75rem; color: #999;">{{ $grade->course->course_code ?? '' }}</span>
                                        </div>
                                    </td>
                                    <td style="padding: 0.75rem; text-align: center; font-weight: 600; color: #2c3e50;">
                                        {{ $grade->overall_score ?? 'N/A' }}%
                                    </td>
                                    <td style="padding: 0.75rem; text-align: center;">
                                        <span style="padding: 0.375rem 0.75rem; border-radius: 0.25rem; font-weight: 700; font-size: 0.875rem;
                                            background: {{ $grade->grade === 'A' ? '#d4edda' : ($grade->grade === 'B' ? '#cfe2ff' : ($grade->grade === 'C' ? '#fff3cd' : ($grade->grade === 'D' ? '#ffe5d0' : '#f8d7da'))) }};
                                            color: {{ $grade->grade === 'A' ? '#155724' : ($grade->grade === 'B' ? '#084298' : ($grade->grade === 'C' ? '#856404' : ($grade->grade === 'D' ? '#e65100' : '#721c24'))) }};">
                                            {{ $grade->grade }}
                                        </span>
                                    </td>
                                    <td style="padding: 0.75rem; text-align: center; color: #666;">
                                        {{ $grade->course->credits ?? 3 }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @if($stats['gpa'] > 0)
                    <div style="margin-top: 1rem; padding: 0.75rem; background: #f8f9fa; border-radius: 0.375rem; text-align: center;">
                        <span style="color: #666; font-size: 0.875rem;">Overall GPA: </span>
                        <strong style="color: #2c3e50; font-size: 1.125rem;">{{ number_format($stats['gpa'], 2) }}/4.0</strong>
                        <span style="margin-left: 1rem; color: #666; font-size: 0.875rem;">Avg Score: </span>
                        <strong style="color: #2c3e50; font-size: 1.125rem;">{{ round($stats['average_grade']) }}%</strong>
                    </div>
                    @endif
                @endif
            </div>
        </div>

        <!-- Right Column (Sidebar) -->
        <div style="display: grid; gap: 2rem;">
            <!-- Financial Summary -->
            <div class="card" style="border-left: 4px solid #27ae60;">
                <h3 style="margin: 0 0 1rem 0; color: #2c3e50;">💰 Financial Summary</h3>
                <div style="background: #f8f9fa; padding: 1rem; border-radius: 0.5rem; margin-bottom: 1rem;">
                    <div style="margin-bottom: 0.75rem;">
                        <span style="color: #666; font-size: 0.75rem; text-transform: uppercase;">Tuition Fee</span>
                        <p style="margin: 0.25rem 0 0 0; font-weight: 700; color: #2c3e50; font-size: 1.25rem;">K{{ number_format($student->tuition_fee, 2) }}</p>
                    </div>
                    <div style="margin-bottom: 0.75rem;">
                        <span style="color: #666; font-size: 0.75rem; text-transform: uppercase;">Amount Paid</span>
                        <p style="margin: 0.25rem 0 0 0; font-weight: 700; color: #27ae60; font-size: 1.25rem;">K{{ number_format($stats['amount_paid'], 2) }}</p>
                    </div>
                    <div style="padding-top: 0.75rem; border-top: 2px solid #dee2e6;">
                        <span style="color: #666; font-size: 0.75rem; text-transform: uppercase;">Balance Due</span>
                        <p style="margin: 0.25rem 0 0 0; font-weight: 700; color: #e74c3c; font-size: 1.25rem;">K{{ number_format($stats['balance_due'], 2) }}</p>
                    </div>
                    
                    <!-- Payment Progress Bar -->
                    <div style="margin-top: 1rem;">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                            <span style="color: #666; font-size: 0.75rem;">Payment Progress</span>
                            <span style="color: #2c3e50; font-size: 0.75rem; font-weight: 600;">
                                {{ $student->tuition_fee > 0 ? round(($stats['amount_paid'] / $student->tuition_fee) * 100) : 0 }}%
                            </span>
                        </div>
                        <div style="width: 100%; height: 10px; background: #ecf0f1; border-radius: 5px; overflow: hidden;">
                            <div style="width: {{ $student->tuition_fee > 0 ? ($stats['amount_paid'] / $student->tuition_fee) * 100 : 0 }}%; height: 100%; background: linear-gradient(90deg, #27ae60, #2ecc71);"></div>
                        </div>
                    </div>
                </div>
                @if($stats['balance_due'] > 0)
                <a href="{{ route('receipts.create', $student) }}" style="display: block; width: 100%; padding: 0.75rem; background: #27ae60; color: white; text-align: center; text-decoration: none; border-radius: 0.375rem; font-weight: 600;">
                    📤 Upload Payment Receipt
                </a>
                @endif
            </div>

            <!-- Recent Receipts -->
            <div class="card" style="border-left: 4px solid #f39c12;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                    <h3 style="margin: 0; color: #2c3e50;">🧾 Recent Receipts</h3>
                    @if($stats['pending_receipts'] > 0)
                    <span style="padding: 0.25rem 0.5rem; background: #fff3cd; color: #856404; border-radius: 0.25rem; font-size: 0.75rem; font-weight: 600;">
                        {{ $stats['pending_receipts'] }} Pending
                    </span>
                    @endif
                </div>
                @if($receipts->isEmpty())
                    <p style="color: #999; font-size: 0.875rem; margin: 0;">No receipts uploaded yet.</p>
                @else
                    <div style="display: grid; gap: 0.75rem;">
                        @foreach($receipts as $receipt)
                        <div style="padding: 1rem; border: 1px solid #ecf0f1; border-radius: 0.375rem; background: #f8f9fa;">
                            <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 0.5rem;">
                                <span style="font-weight: 700; color: #2c3e50; font-size: 1rem;">K{{ number_format($receipt->amount, 2) }}</span>
                                <span style="padding: 0.25rem 0.5rem; border-radius: 0.25rem; font-size: 0.75rem; font-weight: 600;
                                    background: {{ $receipt->status === 'approved' ? '#d4edda' : ($receipt->status === 'rejected' ? '#f8d7da' : '#fff3cd') }};
                                    color: {{ $receipt->status === 'approved' ? '#155724' : ($receipt->status === 'rejected' ? '#721c24' : '#856404') }};">
                                    {{ $receipt->status === 'approved' ? '✓' : ($receipt->status === 'rejected' ? '✗' : '⏳') }} {{ ucfirst($receipt->status) }}
                                </span>
                            </div>
                            <p style="margin: 0; color: #666; font-size: 0.75rem;">{{ $receipt->uploaded_at->format('M d, Y') }}</p>
                        </div>
                        @endforeach
                    </div>
                    <a href="{{ route('receipts.student-history') }}" style="display: block; margin-top: 1rem; color: #3498db; text-decoration: none; font-size: 0.875rem; font-weight: 600; text-align: center;">
                        View All Receipts →
                    </a>
                @endif
            </div>

            <!-- Recent Payments -->
            @if($payments->isNotEmpty())
            <div class="card" style="border-left: 4px solid #3498db;">
                <h3 style="margin: 0 0 1rem 0; color: #2c3e50;">💳 Recent Payments</h3>
                <div style="display: grid; gap: 0.75rem;">
                    @foreach($payments as $payment)
                    <div style="padding: 0.75rem; background: #f8f9fa; border-radius: 0.375rem;">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 0.25rem;">
                            <span style="font-weight: 600; color: #2c3e50;">K{{ number_format($payment->amount, 2) }}</span>
                            <span style="color: #27ae60; font-size: 0.75rem; font-weight: 600;">✓ Completed</span>
                        </div>
                        <p style="margin: 0; color: #666; font-size: 0.75rem;">{{ $payment->paid_at->format('M d, Y') }}</p>
                    </div>
                    @endforeach
                </div>
                <a href="{{ route('payments.my-history') }}" style="display: block; margin-top: 1rem; color: #3498db; text-decoration: none; font-size: 0.875rem; font-weight: 600; text-align: center;">
                    View Payment History →
                </a>
            </div>
            @endif

            <!-- Quick Actions -->
            <div class="card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                <h3 style="margin: 0 0 1rem 0; color: white;">⚡ Quick Actions</h3>
                <div style="display: grid; gap: 0.75rem;">
                    <a href="{{ route('enrollments.available') }}" style="padding: 0.75rem; background: rgba(255,255,255,0.2); color: white; text-decoration: none; border-radius: 0.375rem; font-weight: 600; text-align: center; transition: background 0.2s;" onmouseover="this.style.background='rgba(255,255,255,0.3)'" onmouseout="this.style.background='rgba(255,255,255,0.2)'">
                        📚 Browse Courses
                    </a>
                    <a href="{{ route('grades.index') }}" style="padding: 0.75rem; background: rgba(255,255,255,0.2); color: white; text-decoration: none; border-radius: 0.375rem; font-weight: 600; text-align: center; transition: background 0.2s;" onmouseover="this.style.background='rgba(255,255,255,0.3)'" onmouseout="this.style.background='rgba(255,255,255,0.2)'">
                        📊 View All Grades
                    </a>
                    <a href="{{ route('receipts.create', $student) }}" style="padding: 0.75rem; background: rgba(255,255,255,0.2); color: white; text-decoration: none; border-radius: 0.375rem; font-weight: 600; text-align: center; transition: background 0.2s;" onmouseover="this.style.background='rgba(255,255,255,0.3)'" onmouseout="this.style.background='rgba(255,255,255,0.2)'">
                        📤 Upload Receipt
                    </a>
                    <a href="{{ route('profile.edit') }}" style="padding: 0.75rem; background: rgba(255,255,255,0.2); color: white; text-decoration: none; border-radius: 0.375rem; font-weight: 600; text-align: center; transition: background 0.2s;" onmouseover="this.style.background='rgba(255,255,255,0.3)'" onmouseout="this.style.background='rgba(255,255,255,0.2)'">
                        ⚙️ Profile Settings
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
