@extends('layouts.admin')

@section('content')
<div class="container" style="padding: 2rem 0;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <div>
            <h1 style="margin: 0 0 0.5rem 0; color: #2c3e50;">⏳ Pending Receipt Reviews</h1>
            <p style="margin: 0; color: #666;">Review and approve student payment receipts awaiting verification</p>
        </div>
        <a href="{{ route('receipts.index') }}" style="padding: 0.75rem 1.5rem; background: #95a5a6; color: white; border-radius: 0.375rem; text-decoration: none; font-weight: 600;">
            ← All Receipts
        </a>
    </div>

    @if(session('success'))
    <div style="padding: 1rem; margin-bottom: 2rem; background: #d4edda; border-left: 4px solid #27ae60; border-radius: 0.25rem;">
        <span style="color: #155724; font-weight: 600;">✓ {{ session('success') }}</span>
    </div>
    @endif

    @if(session('error'))
    <div style="padding: 1rem; margin-bottom: 2rem; background: #f8d7da; border-left: 4px solid #e74c3c; border-radius: 0.25rem;">
        <span style="color: #721c24; font-weight: 600;">✗ {{ session('error') }}</span>
    </div>
    @endif

    <!-- Stats Cards -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1rem; margin-bottom: 2rem;">
        <div class="card" style="border-left: 4px solid #f39c12; padding: 1.25rem;">
            <p style="margin: 0; color: #666; font-size: 0.75rem; text-transform: uppercase; font-weight: 600;">Pending Reviews</p>
            <h2 style="margin: 0.5rem 0 0 0; color: #f39c12; font-size: 2rem;">{{ $stats['pending_count'] }}</h2>
            <p style="margin: 0.25rem 0 0 0; color: #999; font-size: 0.75rem;">{{ $stats['today_count'] }} uploaded today</p>
        </div>

        <div class="card" style="border-left: 4px solid #3498db; padding: 1.25rem;">
            <p style="margin: 0; color: #666; font-size: 0.75rem; text-transform: uppercase; font-weight: 600;">Total Amount Pending</p>
            <h2 style="margin: 0.5rem 0 0 0; color: #3498db; font-size: 2rem;">K{{ number_format($stats['pending_amount'], 2) }}</h2>
            <p style="margin: 0.25rem 0 0 0; color: #999; font-size: 0.75rem;">Claimed by students</p>
        </div>

        @if($stats['oldest_pending'])
        <div class="card" style="border-left: 4px solid #e74c3c; padding: 1.25rem;">
            <p style="margin: 0; color: #666; font-size: 0.75rem; text-transform: uppercase; font-weight: 600;">Oldest Pending</p>
            <h2 style="margin: 0.5rem 0 0 0; color: #e74c3c; font-size: 1.5rem;">{{ $stats['oldest_pending']->uploaded_at->diffForHumans() }}</h2>
            <p style="margin: 0.25rem 0 0 0; color: #999; font-size: 0.75rem;">{{ $stats['oldest_pending']->student->first_name }} {{ $stats['oldest_pending']->student->last_name }}</p>
        </div>
        @endif
    </div>

    <!-- Priority Alert -->
    @if($stats['oldest_pending'] && $stats['oldest_pending']->uploaded_at->diffInDays(now()) > 3)
    <div style="padding: 1.25rem; margin-bottom: 2rem; background: #fff3cd; border-left: 4px solid #ffc107; border-radius: 0.5rem; display: flex; align-items: center; gap: 1rem;">
        <span style="font-size: 2rem;">⚠️</span>
        <div>
            <p style="margin: 0; color: #856404; font-weight: 600;">Receipts waiting over 3 days!</p>
            <p style="margin: 0.25rem 0 0 0; color: #856404; font-size: 0.875rem;">Please review pending receipts to avoid delays in student accounts.</p>
        </div>
    </div>
    @endif

    <!-- Receipts Table -->
    <div class="card">
        @if($receipts->isEmpty())
            <div style="text-align: center; padding: 3rem;">
                <p style="color: #999; font-size: 1.25rem; margin-bottom: 0.5rem;">🎉 All caught up!</p>
                <p style="color: #666; font-size: 0.875rem;">No pending receipts to review at the moment.</p>
            </div>
        @else
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background: #34495e; color: white;">
                            <th style="padding: 1rem; text-align: left; font-weight: 600;">Upload Time</th>
                            <th style="padding: 1rem; text-align: left; font-weight: 600;">Student</th>
                            <th style="padding: 1rem; text-align: center; font-weight: 600;">Program</th>
                            <th style="padding: 1rem; text-align: center; font-weight: 600;">Amount</th>
                            <th style="padding: 1rem; text-align: center; font-weight: 600;">File</th>
                            <th style="padding: 1rem; text-align: center; font-weight: 600;">Waiting Time</th>
                            <th style="padding: 1rem; text-align: center; font-weight: 600;">Priority</th>
                            <th style="padding: 1rem; text-align: center; font-weight: 600;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($receipts as $receipt)
                        @php
                            $waitingDays = $receipt->uploaded_at->diffInDays(now());
                            $priority = $waitingDays > 5 ? 'urgent' : ($waitingDays > 3 ? 'high' : 'normal');
                            $fileExt = strtoupper(pathinfo($receipt->file_path, PATHINFO_EXTENSION));
                        @endphp
                        <tr style="border-bottom: 1px solid #ecf0f1; background: {{ $priority === 'urgent' ? '#fff5f5' : ($priority === 'high' ? '#fffbf0' : 'white') }};">
                            <td style="padding: 1rem;">
                                <div>
                                    <span style="font-weight: 600; color: #2c3e50;">{{ $receipt->uploaded_at->format('M d, Y') }}</span>
                                    <br>
                                    <span style="font-size: 0.75rem; color: #999;">{{ $receipt->uploaded_at->format('h:i A') }}</span>
                                </div>
                            </td>
                            <td style="padding: 1rem;">
                                <div>
                                    <span style="font-weight: 600; color: #2c3e50;">{{ $receipt->student->first_name }} {{ $receipt->student->last_name }}</span>
                                    <br>
                                    <span style="font-size: 0.75rem; color: #666;">ID: {{ $receipt->student->student_id }}</span>
                                </div>
                            </td>
                            <td style="padding: 1rem; text-align: center; color: #666; font-size: 0.875rem;">
                                {{ $receipt->student->program ?? 'N/A' }}
                            </td>
                            <td style="padding: 1rem; text-align: center;">
                                <span style="font-weight: 700; color: #2c3e50; font-size: 1.125rem;">K{{ number_format($receipt->amount, 2) }}</span>
                            </td>
                            <td style="padding: 1rem; text-align: center;">
                                <span style="padding: 0.375rem 0.75rem; background: #ecf0f1; border-radius: 0.25rem; font-size: 0.75rem; font-weight: 600; font-family: monospace;">
                                    {{ $fileExt }}
                                </span>
                            </td>
                            <td style="padding: 1rem; text-align: center;">
                                <div>
                                    <span style="font-weight: 600; color: {{ $priority === 'urgent' ? '#e74c3c' : ($priority === 'high' ? '#f39c12' : '#27ae60') }};">
                                        {{ $receipt->uploaded_at->diffForHumans() }}
                                    </span>
                                    <br>
                                    <span style="font-size: 0.75rem; color: #999;">{{ $waitingDays }} day(s)</span>
                                </div>
                            </td>
                            <td style="padding: 1rem; text-align: center;">
                                @if($priority === 'urgent')
                                    <span style="padding: 0.375rem 0.75rem; background: #f8d7da; color: #721c24; border-radius: 0.25rem; font-size: 0.75rem; font-weight: 700;">
                                        🔴 URGENT
                                    </span>
                                @elseif($priority === 'high')
                                    <span style="padding: 0.375rem 0.75rem; background: #fff3cd; color: #856404; border-radius: 0.25rem; font-size: 0.75rem; font-weight: 700;">
                                        🟡 HIGH
                                    </span>
                                @else
                                    <span style="padding: 0.375rem 0.75rem; background: #d4edda; color: #155724; border-radius: 0.25rem; font-size: 0.75rem; font-weight: 700;">
                                        🟢 Normal
                                    </span>
                                @endif
                            </td>
                            <td style="padding: 1rem; text-align: center;">
                                <a href="{{ route('receipts.show', $receipt) }}" style="padding: 0.5rem 1rem; background: #3498db; color: white; border-radius: 0.375rem; text-decoration: none; font-size: 0.875rem; font-weight: 600; display: inline-block;">
                                    👁️ Review
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($receipts->hasPages())
            <div style="padding: 1.5rem; border-top: 1px solid #ecf0f1;">
                {{ $receipts->links() }}
            </div>
            @endif
        @endif
    </div>

    <!-- Quick Tips -->
    <div class="card" style="margin-top: 2rem; background: #e8f4f8; border-left: 4px solid #3498db;">
        <h3 style="margin: 0 0 1rem 0; color: #2c3e50;">💡 Review Tips</h3>
        <ul style="margin: 0; padding-left: 1.5rem; color: #555; line-height: 1.8;">
            <li><strong>Verify Amount:</strong> Check if the receipt amount matches the student's claim</li>
            <li><strong>Check Clarity:</strong> Ensure the receipt is clear and legible</li>
            <li><strong>Validate Date:</strong> Confirm the payment date is recent and reasonable</li>
            <li><strong>Review Balance:</strong> Check if the amount aligns with the student's outstanding balance</li>
            <li><strong>Priority:</strong> Focus on urgent and high-priority receipts first (waiting > 3 days)</li>
            <li><strong>Quick Action:</strong> Process receipts promptly to avoid delays in student accounts</li>
        </ul>
    </div>
</div>
@endsection
