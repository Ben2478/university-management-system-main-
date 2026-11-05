@extends('layouts.student')

@section('content')
<link rel="stylesheet" href="{{ asset('css/finance-management.css') }}?v={{ filemtime(public_path('css/finance-management.css')) }}">
<div class="container finance-management" style="padding: 2rem 0;">
    <div class="finance-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <div>
            <h1 style="margin: 0 0 0.5rem 0;">My Payment History</h1>
            <p style="margin: 0; color: #666;">Track your tuition payments and receipt status</p>
        </div>
        @if($balance > 0)
            <a href="{{ route('receipts.create', $student) }}" class="btn" style="background: #27ae60; color: white; padding: 0.75rem 1.5rem; border-radius: 0.25rem; text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem; font-weight: 600;">
                + Upload New Receipt
            </a>
        @endif
    </div>

    @if(session('success'))
    <div style="padding: 1rem; margin-bottom: 2rem; background: #d4edda; border-left: 4px solid #27ae60; border-radius: 0.25rem;">
        <span style="color: #155724; font-weight: 600;">✓ {{ session('success') }}</span>
    </div>
    @endif

    <!-- Payment Summary Cards -->
    <div class="finance-summary-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1rem; margin-bottom: 2rem;">
        <div class="card" style="border-left: 4px solid #3498db; padding: 1.25rem;">
            <p style="margin: 0; color: #666; font-size: 0.75rem; text-transform: uppercase; font-weight: 600;">Total Tuition Fee</p>
            <h2 style="margin: 0.5rem 0 0 0; color: #3498db; font-size: 2rem;">K{{ number_format($student->tuition_fee, 2) }}</h2>
        </div>
        <div class="card" style="border-left: 4px solid #27ae60; padding: 1.25rem;">
            <p style="margin: 0; color: #666; font-size: 0.75rem; text-transform: uppercase; font-weight: 600;">Amount Paid (Approved)</p>
            <h2 style="margin: 0.5rem 0 0 0; color: #27ae60; font-size: 2rem;">K{{ number_format($paid, 2) }}</h2>
        </div>
        <div class="card" style="border-left: 4px solid #e74c3c; padding: 1.25rem;">
            <p style="margin: 0; color: #666; font-size: 0.75rem; text-transform: uppercase; font-weight: 600;">Balance Due</p>
            <h2 style="margin: 0.5rem 0 0 0; color: #e74c3c; font-size: 2rem;">K{{ number_format($balance, 2) }}</h2>
        </div>
        <div class="card" style="border-left: 4px solid #f39c12; padding: 1.25rem;">
            <p style="margin: 0; color: #666; font-size: 0.75rem; text-transform: uppercase; font-weight: 600;">Payment Progress</p>
            <h2 style="margin: 0.5rem 0 0.5rem 0; color: #f39c12; font-size: 2rem;">{{ $student->tuition_fee > 0 ? round(($paid / $student->tuition_fee) * 100) : 0 }}%</h2>
            <div style="width: 100%; height: 8px; background: #ecf0f1; border-radius: 4px; overflow: hidden;">
                <div style="width: {{ $student->tuition_fee > 0 ? ($paid / $student->tuition_fee) * 100 : 0 }}%; height: 100%; background: #f39c12;"></div>
            </div>
        </div>
    </div>

    <!-- Payment Status Alert -->
    @if($balance <= 0)
    <div style="padding: 1.5rem; margin-bottom: 2rem; background: #d4edda; border-left: 4px solid #27ae60; border-radius: 0.5rem;">
        <div style="display: flex; align-items: center; gap: 1rem;">
            <div style="font-size: 3rem;">🎉</div>
            <div>
                <h3 style="margin: 0 0 0.5rem 0; color: #155724;">Fully Paid!</h3>
                <p style="margin: 0; color: #155724;">Congratulations! You have completed your tuition payment for this semester.</p>
            </div>
        </div>
    </div>
    @elseif($balance > 0 && $balance < $student->tuition_fee * 0.5)
    <div style="padding: 1.5rem; margin-bottom: 2rem; background: #fff3cd; border-left: 4px solid #ffc107; border-radius: 0.5rem;">
        <div style="display: flex; align-items: center; gap: 1rem;">
            <div style="font-size: 3rem;">💰</div>
            <div>
                <h3 style="margin: 0 0 0.5rem 0; color: #856404;">Almost There!</h3>
                <p style="margin: 0; color: #856404;">You're more than halfway through your tuition payment. Keep going!</p>
            </div>
        </div>
    </div>
    @endif

    <!-- Receipts Table -->
    <div class="card">
        <div class="finance-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
            <h3 style="margin: 0; color: #2c3e50;">Uploaded Receipts ({{ $receipts->total() }})</h3>
            <div class="finance-status-summary" style="display: flex; gap: 0.5rem; align-items: center;">
                <span style="padding: 0.375rem 0.75rem; background: #fff3cd; color: #856404; border-radius: 0.25rem; font-size: 0.75rem; font-weight: 600;">
                    Pending: {{ $receipts->where('status', 'pending')->count() }}
                </span>
                <span style="padding: 0.375rem 0.75rem; background: #d4edda; color: #155724; border-radius: 0.25rem; font-size: 0.75rem; font-weight: 600;">
                    Approved: {{ $receipts->where('status', 'approved')->count() }}
                </span>
            </div>
        </div>

        @if($receipts->count() > 0)
        <div class="finance-table-scroll" tabindex="0" role="region" aria-label="My uploaded receipts">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: #34495e; color: white;">
                        <th style="padding: 1rem; text-align: left; font-weight: 600;">Date Uploaded</th>
                        <th style="padding: 1rem; text-align: center; font-weight: 600;">Amount</th>
                        <th style="padding: 1rem; text-align: center; font-weight: 600;">Status</th>
                        <th style="padding: 1rem; text-align: left; font-weight: 600;">File Name</th>
                        <th style="padding: 1rem; text-align: center; font-weight: 600;">Reviewed</th>
                        <th style="padding: 1rem; text-align: center; font-weight: 600;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($receipts as $receipt)
                    <tr style="border-bottom: 1px solid #ecf0f1;">
                        <td style="padding: 1rem;">{{ $receipt->uploaded_at->format('M d, Y h:i A') }}</td>
                        <td style="padding: 1rem; text-align: center; font-weight: 600; color: #2c3e50;">
                            K{{ number_format($receipt->amount, 2) }}
                        </td>
                        <td style="padding: 1rem; text-align: center;">
                            @if($receipt->status === 'pending')
                                <span style="padding: 0.375rem 0.75rem; background: #fff3cd; color: #856404; border-radius: 0.25rem; font-size: 0.875rem; font-weight: 600;">
                                    ⏳ Pending
                                </span>
                            @elseif($receipt->status === 'approved')
                                <span style="padding: 0.375rem 0.75rem; background: #d4edda; color: #155724; border-radius: 0.25rem; font-size: 0.875rem; font-weight: 600;">
                                    ✓ Approved
                                </span>
                            @else
                                <span style="padding: 0.375rem 0.75rem; background: #f8d7da; color: #721c24; border-radius: 0.25rem; font-size: 0.875rem; font-weight: 600;">
                                    ✗ Rejected
                                </span>
                            @endif
                        </td>
                        <td style="padding: 1rem; color: #666;">
                            📎 {{ Str::limit($receipt->original_filename, 30) }}
                        </td>
                        <td style="padding: 1rem; text-align: center; font-size: 0.875rem; color: #666;">
                            @if($receipt->reviewed_at)
                                {{ $receipt->reviewed_at->format('M d, Y') }}
                            @else
                                <span style="color: #999;">Not yet</span>
                            @endif
                        </td>
                        <td style="padding: 1rem; text-align: center;">
                            <a href="{{ route('receipts.show', $receipt) }}" style="padding: 0.375rem 0.75rem; background: #3498db; color: white; border-radius: 0.25rem; text-decoration: none; font-size: 0.875rem; font-weight: 600;">
                                👁️ View
                            </a>
                        </td>
                    </tr>
                    @if($receipt->status === 'rejected' && $receipt->rejection_reason)
                    <tr style="background: #fff3e0; border-bottom: 1px solid #ecf0f1;">
                        <td colspan="6" style="padding: 1rem;">
                            <strong style="color: #e65100;">Rejection Reason:</strong> 
                            <span style="color: #e65100;">{{ $receipt->rejection_reason }}</span>
                        </td>
                    </tr>
                    @endif
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
        @else
        <div style="text-align: center; padding: 3rem; background: #f8f9fa; border-radius: 0.5rem;">
            <p style="color: #999; font-size: 1.125rem; margin-bottom: 1rem;">📋 No receipts uploaded yet</p>
            <p style="color: #666; font-size: 0.875rem; margin-bottom: 1.5rem;">Upload your first payment receipt to get started</p>
            <a href="{{ route('receipts.create', $student) }}" style="padding: 0.75rem 1.5rem; background: #27ae60; color: white; border-radius: 0.25rem; text-decoration: none; font-weight: 600;">
                + Upload First Receipt
            </a>
        </div>
        @endif
    </div>
</div>
@endsection
