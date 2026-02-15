@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <a href="{{ route('students.show', $receipt->student) }}" style="color: #3498db; text-decoration: none;">← Back to Student</a>
        <h2 style="margin-top: 1rem;">Receipt Details</h2>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-bottom: 2rem;">
            <div>
                <h4>Receipt Information</h4>
                <p><strong>Student:</strong> {{ $receipt->student->first_name }} {{ $receipt->student->last_name }}</p>
                <p><strong>Student ID:</strong> {{ $receipt->student->student_id }}</p>
                <p><strong>Amount:</strong> K{{ number_format($receipt->amount, 2) }}</p>
                <p><strong>File:</strong> {{ $receipt->original_filename }}</p>
                <p><strong>Uploaded:</strong> {{ $receipt->uploaded_at->format('M d, Y H:i') }}</p>
            </div>

            <div>
                <h4>Status</h4>
                <p>
                    <span class="badge 
                        @if($receipt->status === 'pending') badge-warning
                        @elseif($receipt->status === 'approved') badge-success
                        @else badge-danger
                        @endif">
                        {{ ucfirst($receipt->status) }}
                    </span>
                </p>

                @if($receipt->reviewed_at)
                    <p><strong>Reviewed:</strong> {{ $receipt->reviewed_at->format('M d, Y H:i') }}</p>
                    <p><strong>Reviewed By:</strong> {{ $receipt->reviewer->name ?? 'Unknown' }}</p>
                @endif

                @if($receipt->accountant_notes)
                    <p><strong>Notes:</strong> {{ $receipt->accountant_notes }}</p>
                @endif
            </div>
        </div>

        @if($receipt->isPending())
            <div style="background: #fff3cd; border: 1px solid #ffc107; border-radius: 4px; padding: 1rem; margin-bottom: 2rem;">
                <strong>⏳ Pending Approval</strong>
                <p>Your receipt is waiting for accountant review. This usually takes 1-2 business days.</p>
            </div>

            @if(auth()->user()->role === 'super_admin' || auth()->user()->role === 'admin')
                <div style="background: #e7f3ff; border: 1px solid #3498db; border-radius: 4px; padding: 1.5rem; margin-bottom: 2rem;">
                    <h4 style="margin-top: 0; color: #2980b9;">Review Actions</h4>
                    
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-top: 1rem;">
                        <!-- Approve Form -->
                        <div style="background: white; padding: 1rem; border-radius: 4px; border: 1px solid #ddd;">
                            <h5 style="color: #28a745; margin-top: 0;">✓ Approve Receipt</h5>
                            <form action="{{ route('receipts.approve', $receipt) }}" method="POST">
                                @csrf
                                <div style="margin-bottom: 1rem;">
                                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Notes (Optional)</label>
                                    <textarea name="notes" rows="3" style="width: 100%; padding: 0.5rem; border: 1px solid #ddd; border-radius: 4px;" placeholder="Add any notes about this approval..."></textarea>
                                </div>
                                <button type="submit" class="btn" style="background: #28a745; color: white; width: 100%;">Approve & Create Payment</button>
                            </form>
                        </div>

                        <!-- Reject Form -->
                        <div style="background: white; padding: 1rem; border-radius: 4px; border: 1px solid #ddd;">
                            <h5 style="color: #dc3545; margin-top: 0;">✗ Reject Receipt</h5>
                            <form action="{{ route('receipts.reject', $receipt) }}" method="POST">
                                @csrf
                                <div style="margin-bottom: 1rem;">
                                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Reason (Required)</label>
                                    <textarea name="reason" rows="3" required style="width: 100%; padding: 0.5rem; border: 1px solid #ddd; border-radius: 4px;" placeholder="Explain why this receipt is being rejected..."></textarea>
                                </div>
                                <button type="submit" class="btn" style="background: #dc3545; color: white; width: 100%;">Reject Receipt</button>
                            </form>
                        </div>
                    </div>
                </div>
            @endif
        @elseif($receipt->isApproved())
            <div style="background: #d4edda; border: 1px solid #28a745; border-radius: 4px; padding: 1rem; margin-bottom: 2rem;">
                <strong>✓ Approved</strong>
                <p>Your receipt has been approved. Your payment is now recorded in the system.</p>
                @if($receipt->payment_id)
                    <p><a href="{{ route('payments.show', $receipt->payment_id) }}" class="btn" style="font-size: 0.85rem; margin-top: 0.5rem;">View Payment Record</a></p>
                @endif
            </div>
        @else
            <div style="background: #f8d7da; border: 1px solid #f5c6cb; border-radius: 4px; padding: 1rem; margin-bottom: 2rem;">
                <strong>✗ Rejected</strong>
                <p>{{ $receipt->accountant_notes }}</p>
                <p style="margin-top: 0.5rem;"><a href="{{ route('receipts.create', $receipt->student) }}" class="btn btn-secondary" style="font-size: 0.85rem;">Upload Again</a></p>
            </div>
        @endif

        <div style="border-top: 1px solid #ddd; padding-top: 1.5rem;">
            <h4>Receipt File</h4>
            <p><a href="{{ route('receipts.download', $receipt) }}" target="_blank" class="btn">View/Download Receipt File</a></p>
            <p style="font-size: 0.85rem; color: #666; margin-top: 0.5rem;">File: {{ $receipt->original_filename }}</p>
        </div>
    </div>
</div>
@endsection
