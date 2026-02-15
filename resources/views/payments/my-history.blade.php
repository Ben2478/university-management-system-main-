@extends('layouts.student')

@section('content')
<div class="container" style="padding: 2rem 0;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <div>
            <h1 style="margin: 0 0 0.5rem 0;">My Payment History</h1>
            <p style="margin: 0; color: #666;">Track your completed and pending payments</p>
        </div>
    </div>

    @if(session('success'))
    <div style="padding: 1rem; margin-bottom: 2rem; background: #d4edda; border-left: 4px solid #27ae60; border-radius: 0.25rem;">
        <span style="color: #155724; font-weight: 600;">✓ {{ session('success') }}</span>
    </div>
    @endif

    <!-- Payment Summary Cards -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1rem; margin-bottom: 2rem;">
        <div class="card" style="border-left: 4px solid #3498db; padding: 1.25rem;">
            <p style="margin: 0; color: #666; font-size: 0.75rem; text-transform: uppercase; font-weight: 600;">Total Tuition Fee</p>
            <h2 style="margin: 0.5rem 0 0 0; color: #3498db; font-size: 2rem;">K{{ number_format($stats['tuition_fee'], 2) }}</h2>
        </div>
        <div class="card" style="border-left: 4px solid #27ae60; padding: 1.25rem;">
            <p style="margin: 0; color: #666; font-size: 0.75rem; text-transform: uppercase; font-weight: 600;">Total Paid</p>
            <h2 style="margin: 0.5rem 0 0 0; color: #27ae60; font-size: 2rem;">K{{ number_format($stats['total_paid'], 2) }}</h2>
            <p style="margin: 0.5rem 0 0 0; color: #666; font-size: 0.75rem;">{{ $stats['total_payments'] }} payment(s)</p>
        </div>
        <div class="card" style="border-left: 4px solid #e74c3c; padding: 1.25rem;">
            <p style="margin: 0; color: #666; font-size: 0.75rem; text-transform: uppercase; font-weight: 600;">Balance Due</p>
            <h2 style="margin: 0.5rem 0 0 0; color: #e74c3c; font-size: 2rem;">K{{ number_format($stats['balance'], 2) }}</h2>
        </div>
        <div class="card" style="border-left: 4px solid #f39c12; padding: 1.25rem;">
            <p style="margin: 0; color: #666; font-size: 0.75rem; text-transform: uppercase; font-weight: 600;">Pending Payments</p>
            <h2 style="margin: 0.5rem 0 0 0; color: #f39c12; font-size: 2rem;">{{ $stats['pending_payments'] }}</h2>
            @if($stats['last_payment'])
                <p style="margin: 0.5rem 0 0 0; color: #666; font-size: 0.75rem;">Last: {{ $stats['last_payment']->paid_at->format('M d, Y') }}</p>
            @endif
        </div>
    </div>

    <!-- Payment Status Info -->
    @if($stats['balance'] <= 0)
    <div style="padding: 1.5rem; margin-bottom: 2rem; background: #d4edda; border-left: 4px solid #27ae60; border-radius: 0.5rem;">
        <div style="display: flex; align-items: center; gap: 1rem;">
            <div style="font-size: 3rem;">🎉</div>
            <div>
                <h3 style="margin: 0 0 0.5rem 0; color: #155724;">Fully Paid!</h3>
                <p style="margin: 0; color: #155724;">You have completed all your tuition payments for this semester.</p>
            </div>
        </div>
    </div>
    @elseif($stats['pending_payments'] > 0)
    <div style="padding: 1.5rem; margin-bottom: 2rem; background: #fff3cd; border-left: 4px solid #ffc107; border-radius: 0.5rem;">
        <div style="display: flex; align-items: center; gap: 1rem;">
            <div style="font-size: 3rem;">⏳</div>
            <div>
                <h3 style="margin: 0 0 0.5rem 0; color: #856404;">Pending Verification</h3>
                <p style="margin: 0; color: #856404;">You have {{ $stats['pending_payments'] }} payment(s) awaiting verification by the accounts office.</p>
            </div>
        </div>
    </div>
    @endif

    <!-- Payments Table -->
    <div class="card">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
            <h3 style="margin: 0; color: #2c3e50;">Payment Transactions ({{ $payments->total() }})</h3>
        </div>

        @if($payments->count() > 0)
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: #34495e; color: white;">
                        <th style="padding: 1rem; text-align: left; font-weight: 600;">Reference Number</th>
                        <th style="padding: 1rem; text-align: center; font-weight: 600;">Amount</th>
                        <th style="padding: 1rem; text-align: center; font-weight: 600;">Method</th>
                        <th style="padding: 1rem; text-align: center; font-weight: 600;">Status</th>
                        <th style="padding: 1rem; text-align: center; font-weight: 600;">Payment Date</th>
                        <th style="padding: 1rem; text-align: left; font-weight: 600;">Description</th>
                        <th style="padding: 1rem; text-align: center; font-weight: 600;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($payments as $payment)
                    <tr style="border-bottom: 1px solid #ecf0f1;">
                        <td style="padding: 1rem; font-family: monospace; font-size: 0.875rem;">
                            {{ $payment->reference_number }}
                        </td>
                        <td style="padding: 1rem; text-align: center; font-weight: 600; color: #2c3e50;">
                            K{{ number_format($payment->amount, 2) }}
                        </td>
                        <td style="padding: 1rem; text-align: center;">
                            @php
                                $methods = [
                                    'manual_receipt' => ['icon' => '📄', 'label' => 'Receipt Upload'],
                                    'bank_transfer' => ['icon' => '🏦', 'label' => 'Bank Transfer'],
                                    'mobile_money' => ['icon' => '📱', 'label' => 'Mobile Money'],
                                    'online' => ['icon' => '💳', 'label' => 'Online'],
                                    'cash' => ['icon' => '💵', 'label' => 'Cash'],
                                ];
                                $method = $methods[$payment->payment_method] ?? ['icon' => '💰', 'label' => ucfirst($payment->payment_method)];
                            @endphp
                            <span style="font-size: 0.875rem;">{{ $method['icon'] }} {{ $method['label'] }}</span>
                        </td>
                        <td style="padding: 1rem; text-align: center;">
                            @if($payment->status === 'completed')
                                <span style="padding: 0.375rem 0.75rem; background: #d4edda; color: #155724; border-radius: 0.25rem; font-size: 0.875rem; font-weight: 600;">
                                    ✓ Completed
                                </span>
                            @elseif($payment->status === 'pending')
                                <span style="padding: 0.375rem 0.75rem; background: #fff3cd; color: #856404; border-radius: 0.25rem; font-size: 0.875rem; font-weight: 600;">
                                    ⏳ Pending
                                </span>
                            @elseif($payment->status === 'processing')
                                <span style="padding: 0.375rem 0.75rem; background: #cfe2ff; color: #084298; border-radius: 0.25rem; font-size: 0.875rem; font-weight: 600;">
                                    🔄 Processing
                                </span>
                            @else
                                <span style="padding: 0.375rem 0.75rem; background: #f8d7da; color: #721c24; border-radius: 0.25rem; font-size: 0.875rem; font-weight: 600;">
                                    ✗ {{ ucfirst($payment->status) }}
                                </span>
                            @endif
                        </td>
                        <td style="padding: 1rem; text-align: center; color: #666; font-size: 0.875rem;">
                            @if($payment->paid_at)
                                <div>{{ $payment->paid_at->format('M d, Y') }}</div>
                                <div style="font-size: 0.75rem; color: #999;">{{ $payment->paid_at->format('h:i A') }}</div>
                            @else
                                <span style="color: #999;">-</span>
                            @endif
                        </td>
                        <td style="padding: 1rem; color: #666; font-size: 0.875rem;">
                            {{ Str::limit($payment->description, 40) }}
                        </td>
                        <td style="padding: 1rem; text-align: center;">
                            <a href="{{ route('payments.show', $payment) }}" style="padding: 0.375rem 0.75rem; background: #3498db; color: white; border-radius: 0.25rem; text-decoration: none; font-size: 0.875rem; font-weight: 600;">
                                👁️ View
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($payments->hasPages())
        <div style="padding: 1.5rem; border-top: 1px solid #ecf0f1;">
            {{ $payments->links() }}
        </div>
        @endif
        @else
        <div style="text-align: center; padding: 3rem; background: #f8f9fa; border-radius: 0.5rem;">
            <p style="color: #999; font-size: 1.125rem; margin-bottom: 1rem;">💳 No payments recorded yet</p>
            <p style="color: #666; font-size: 0.875rem; margin-bottom: 1.5rem;">Your completed and verified payments will appear here</p>
            <a href="{{ route('receipts.create', $student) }}" style="padding: 0.75rem 1.5rem; background: #27ae60; color: white; border-radius: 0.25rem; text-decoration: none; font-weight: 600;">
                + Upload Payment Receipt
            </a>
        </div>
        @endif
    </div>

    <!-- Payment Info Card -->
    <div class="card" style="margin-top: 2rem; background: #e8f4f8; border-left: 4px solid #3498db;">
        <h3 style="margin: 0 0 1rem 0; color: #2c3e50;">💡 Payment Information</h3>
        <ul style="margin: 0; padding-left: 1.5rem; color: #555; line-height: 1.8;">
            <li><strong>Receipt Upload:</strong> Upload your payment receipts via the "Upload Receipt" tab for verification</li>
            <li><strong>Processing Time:</strong> Payment verification typically takes 1-2 business days</li>
            <li><strong>Payment Methods:</strong> Bank transfer, mobile money, and online payments accepted</li>
            <li><strong>Status Updates:</strong> Check this page regularly for payment status updates</li>
            <li><strong>Issues?</strong> Contact the accounts office if you have questions about your payments</li>
        </ul>
    </div>
</div>
@endsection
