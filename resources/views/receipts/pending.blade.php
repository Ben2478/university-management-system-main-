@extends('layouts.admin')

@section('content')
<link rel="stylesheet" href="{{ asset('css/finance-management.css') }}?v={{ filemtime(public_path('css/finance-management.css')) }}">
<div class="container finance-management" style="padding: 2rem 0;">
    <div style="margin-bottom: 2rem;">
        <a href="{{ route('receipts.pending') }}" style="color: #3498db; text-decoration: none; font-weight: 600;">
            ← Back to Pending Reviews
        </a>
        <h1 style="margin: 1rem 0 0.5rem 0; color: #2c3e50;">Receipt Review</h1>
        <p style="margin: 0; color: #666;">Review and approve or reject this payment receipt</p>
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

    <!-- Receipt Preview and Details Grid -->
    <div class="finance-detail-grid" style="display: grid; grid-template-columns: 1.5fr 1fr; gap: 2rem; margin-bottom: 2rem;">
        <!-- Left: Receipt Preview -->
        <div class="card" style="border-left: 4px solid #3498db;">
            <h3 style="margin: 0 0 1rem 0; color: #2c3e50;">📄 Receipt Document</h3>
            
            @php
                $fileExt = strtolower(pathinfo($receipt->file_path, PATHINFO_EXTENSION));
            @endphp
            
            @if(in_array($fileExt, ['jpg', 'jpeg', 'png']))
                <div style="background: #f8f9fa; border-radius: 0.5rem; padding: 1rem; margin-bottom: 1rem;">
                    <img src="{{ asset('storage/' . $receipt->file_path) }}" alt="Receipt" style="width: 100%; height: auto; border-radius: 0.375rem;">
                </div>
            @else
                <div style="background: #f8f9fa; border-radius: 0.5rem; padding: 3rem; text-align: center; margin-bottom: 1rem;">
                    <span style="font-size: 4rem;">📄</span>
                    <p style="margin: 1rem 0 0 0; color: #666; font-weight: 600;">{{ strtoupper($fileExt) }} Document</p>
                    <p style="margin: 0.5rem 0 0 0; color: #999; font-size: 0.875rem;">{{ $receipt->original_filename }}</p>
                </div>
            @endif

            <a href="{{ asset('storage/' . $receipt->file_path) }}" target="_blank" style="display: block; width: 100%; padding: 0.75rem; background: #3498db; color: white; text-align: center; text-decoration: none; border-radius: 0.375rem; font-weight: 600;">
                🔍 View Full Document
            </a>
        </div>

        <!-- Right: Receipt Details -->
        <div style="display: grid; gap: 1.5rem;">
            <!-- Receipt Info Card -->
            <div class="card" style="border-left: 4px solid #9b59b6;">
                <h3 style="margin: 0 0 1rem 0; color: #2c3e50;">ℹ️ Receipt Information</h3>
                
                <div style="margin-bottom: 1rem;">
                    <p style="margin: 0; color: #666; font-size: 0.75rem; text-transform: uppercase;">Student Name</p>
                    <p style="margin: 0.25rem 0 0 0; font-weight: 700; color: #2c3e50; font-size: 1.125rem;">
                        {{ $receipt->student->first_name }} {{ $receipt->student->last_name }}
                    </p>
                </div>

                <div style="margin-bottom: 1rem;">
                    <p style="margin: 0; color: #666; font-size: 0.75rem; text-transform: uppercase;">Student ID</p>
                    <p style="margin: 0.25rem 0 0 0; font-weight: 600; color: #2c3e50;">{{ $receipt->student->student_id }}</p>
                </div>

                <div style="margin-bottom: 1rem;">
                    <p style="margin: 0; color: #666; font-size: 0.75rem; text-transform: uppercase;">Program</p>
                    <p style="margin: 0.25rem 0 0 0; font-weight: 600; color: #2c3e50;">{{ $receipt->student->program ?? 'N/A' }}</p>
                </div>

                <div style="margin-bottom: 1rem; padding-top: 1rem; border-top: 2px solid #ecf0f1;">
                    <p style="margin: 0; color: #666; font-size: 0.75rem; text-transform: uppercase;">Amount Claimed</p>
                    <p style="margin: 0.25rem 0 0 0; font-weight: 700; color: #3498db; font-size: 1.5rem;">K{{ number_format($receipt->amount, 2) }}</p>
                </div>

                <div style="margin-bottom: 1rem;">
                    <p style="margin: 0; color: #666; font-size: 0.75rem; text-transform: uppercase;">Payment Method</p>
                    <p style="margin: 0.25rem 0 0 0; font-weight: 600; color: #2c3e50;">
                        @if($receipt->payment_method === 'bank_transfer') 🏦 Bank Transfer
                        @elseif($receipt->payment_method === 'mobile_money') 📱 Mobile Money
                        @elseif($receipt->payment_method === 'cash') 💵 Cash
                        @elseif($receipt->payment_method === 'card') 💳 Card
                        @elseif($receipt->payment_method === 'zananco_billmaster') 🌐 Zananco Billmaster
                        @else {{ ucfirst(str_replace('_', ' ', $receipt->payment_method)) }}
                        @endif
                    </p>
                </div>

                <div style="margin-bottom: 1rem;">
                    <p style="margin: 0; color: #666; font-size: 0.75rem; text-transform: uppercase;">Uploaded</p>
                    <p style="margin: 0.25rem 0 0 0; font-weight: 600; color: #2c3e50;">{{ $receipt->uploaded_at->format('M d, Y') }}</p>
                    <p style="margin: 0.25rem 0 0 0; color: #999; font-size: 0.75rem;">{{ $receipt->uploaded_at->format('h:i A') }} ({{ $receipt->uploaded_at->diffForHumans() }})</p>
                </div>

                <div>
                    <p style="margin: 0; color: #666; font-size: 0.75rem; text-transform: uppercase;">File Name</p>
                    <p style="margin: 0.25rem 0 0 0; color: #2c3e50; font-size: 0.875rem; word-break: break-all;">{{ $receipt->original_filename }}</p>
                </div>
            </div>

            <!-- Account Status Card -->
            <div class="card" style="border-left: 4px solid #27ae60;">
                <h3 style="margin: 0 0 1rem 0; color: #2c3e50;">💰 Student Account Status</h3>
                
                <div style="background: #f8f9fa; padding: 1rem; border-radius: 0.5rem; margin-bottom: 1rem;">
                    <div style="margin-bottom: 0.75rem;">
                        <span style="color: #666; font-size: 0.75rem; text-transform: uppercase;">Total Tuition Fee</span>
                        <p style="margin: 0.25rem 0 0 0; font-weight: 700; color: #2c3e50; font-size: 1.125rem;">K{{ number_format($receipt->student->tuition_fee, 2) }}</p>
                    </div>

                    <div style="margin-bottom: 0.75rem;">
                        <span style="color: #666; font-size: 0.75rem; text-transform: uppercase;">Already Paid</span>
                        <p style="margin: 0.25rem 0 0 0; font-weight: 700; color: #27ae60; font-size: 1.125rem;">K{{ number_format($receipt->student->getPaidAmount(), 2) }}</p>
                    </div>

                    <div style="padding-top: 0.75rem; border-top: 2px solid #dee2e6;">
                        <span style="color: #666; font-size: 0.75rem; text-transform: uppercase;">Current Balance</span>
                        <p style="margin: 0.25rem 0 0 0; font-weight: 700; color: #e74c3c; font-size: 1.125rem;">K{{ number_format($receipt->student->getBalance(), 2) }}</p>
                    </div>
                </div>

                <div style="padding: 0.75rem; background: #e8f4f8; border-radius: 0.375rem;">
                    <p style="margin: 0; color: #084298; font-size: 0.875rem;">
                        <strong>If approved:</strong> New balance will be 
                        <strong>K{{ number_format($receipt->student->getBalance() - $receipt->amount, 2) }}</strong>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Forms -->
    @if($receipt->isPending())
        <div class="finance-grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
            <!-- Approve Form -->
            <div class="card" style="background: linear-gradient(135deg, #d4edda, #c3e6cb); border-left: 4px solid #27ae60;">
                <h3 style="color: #155724; margin: 0 0 1rem 0; display: flex; align-items: center; gap: 0.5rem;">
                    <span style="font-size: 1.5rem;">✓</span> Approve Receipt
                </h3>
                <form action="{{ route('receipts.approve', $receipt) }}" method="POST">
                    @csrf
                    <div style="margin-bottom: 1.5rem;">
                        <label for="notes" style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #155724;">
                            Approval Notes (Optional)
                        </label>
                        <textarea 
                            id="notes" 
                            name="notes" 
                            placeholder="e.g., Receipt verified, amount matches bank record, payment approved"
                            style="width: 100%; padding: 0.75rem; border: 2px solid #c3e6cb; border-radius: 0.375rem; font-size: 1rem; resize: vertical; min-height: 100px; background: white;"
                        ></textarea>
                        <span style="color: #155724; font-size: 0.75rem;">These notes will be saved in the system for reference.</span>
                    </div>
                    <button 
                        type="submit" 
                        style="width: 100%; padding: 1rem; background: #27ae60; color: white; border: none; border-radius: 0.375rem; font-size: 1rem; font-weight: 700; cursor: pointer; transition: background 0.2s;"
                        onmouseover="this.style.background='#229954'" 
                        onmouseout="this.style.background='#27ae60'"
                        onclick="return confirm('Are you sure you want to approve this receipt? This will create a payment record and update the student balance.')"
                    >
                        ✓ Approve & Create Payment Record
                    </button>
                </form>
            </div>

            <!-- Reject Form -->
            <div class="card" style="background: linear-gradient(135deg, #f8d7da, #f5c2c7); border-left: 4px solid #e74c3c;">
                <h3 style="color: #721c24; margin: 0 0 1rem 0; display: flex; align-items: center; gap: 0.5rem;">
                    <span style="font-size: 1.5rem;">✗</span> Reject Receipt
                </h3>
                <form action="{{ route('receipts.reject', $receipt) }}" method="POST">
                    @csrf
                    <div style="margin-bottom: 1.5rem;">
                        <label for="reason" style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #721c24;">
                            Reason for Rejection <span style="color: #e74c3c;">*</span>
                        </label>
                        <textarea 
                            id="reason" 
                            name="reason" 
                            placeholder="e.g., Amount doesn't match, Receipt image is unclear, Duplicate submission, Payment date invalid"
                            required
                            style="width: 100%; padding: 0.75rem; border: 2px solid #f5c2c7; border-radius: 0.375rem; font-size: 1rem; resize: vertical; min-height: 100px; background: white;"
                        ></textarea>
                        <span style="color: #721c24; font-size: 0.75rem;"><strong>Important:</strong> This reason will be shown to the student.</span>
                    </div>
                    <button 
                        type="submit" 
                        style="width: 100%; padding: 1rem; background: #e74c3c; color: white; border: none; border-radius: 0.375rem; font-size: 1rem; font-weight: 700; cursor: pointer; transition: background 0.2s;"
                        onmouseover="this.style.background='#c0392b'" 
                        onmouseout="this.style.background='#e74c3c'"
                        onclick="return confirm('Are you sure you want to reject this receipt? The student will be notified with your rejection reason.')"
                    >
                        ✗ Reject Receipt
                    </button>
                </form>
            </div>
        </div>

        <!-- Action Guidelines -->
        <div class="card" style="margin-top: 2rem; background: #e8f4f8; border-left: 4px solid #3498db;">
            <h3 style="margin: 0 0 1rem 0; color: #2c3e50;">📋 Review Guidelines</h3>
            <div class="finance-grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
                <div>
                    <h4 style="margin: 0 0 0.5rem 0; color: #27ae60;">✓ Approve if:</h4>
                    <ul style="margin: 0; padding-left: 1.5rem; color: #555; line-height: 1.8;">
                        <li>Receipt is clear and legible</li>
                        <li>Amount matches student's claim</li>
                        <li>Payment date is reasonable</li>
                        <li>Receipt appears authentic</li>
                        <li>Amount aligns with balance due</li>
                    </ul>
                </div>
                <div>
                    <h4 style="margin: 0 0 0.5rem 0; color: #e74c3c;">✗ Reject if:</h4>
                    <ul style="margin: 0; padding-left: 1.5rem; color: #555; line-height: 1.8;">
                        <li>Receipt is blurry or unreadable</li>
                        <li>Amount doesn't match claim</li>
                        <li>Receipt appears edited/fake</li>
                        <li>Duplicate submission detected</li>
                        <li>Payment date is suspicious</li>
                    </ul>
                </div>
            </div>
        </div>
    @else
        <!-- Review History -->
        <div class="card" style="border-left: 4px solid {{ $receipt->status === 'approved' ? '#27ae60' : '#e74c3c' }};">
            <h3 style="margin: 0 0 1rem 0; color: #2c3e50;">📋 Review History</h3>
            
            <div style="display: grid; gap: 1rem;">
                <div>
                    <p style="margin: 0; color: #666; font-size: 0.75rem; text-transform: uppercase;">Status</p>
                    <span style="padding: 0.5rem 1rem; border-radius: 0.375rem; font-weight: 700; display: inline-block; margin-top: 0.5rem;
                        background: {{ $receipt->status === 'approved' ? '#d4edda' : '#f8d7da' }};
                        color: {{ $receipt->status === 'approved' ? '#155724' : '#721c24' }};">
                        {{ $receipt->status === 'approved' ? '✓ Approved' : '✗ Rejected' }}
                    </span>
                </div>

                <div>
                    <p style="margin: 0; color: #666; font-size: 0.75rem; text-transform: uppercase;">Reviewed By</p>
                    <p style="margin: 0.25rem 0 0 0; font-weight: 600; color: #2c3e50;">{{ $receipt->reviewer->name ?? 'Unknown' }}</p>
                </div>

                <div>
                    <p style="margin: 0; color: #666; font-size: 0.75rem; text-transform: uppercase;">Reviewed At</p>
                    <p style="margin: 0.25rem 0 0 0; font-weight: 600; color: #2c3e50;">{{ $receipt->reviewed_at->format('M d, Y h:i A') }}</p>
                    <p style="margin: 0.25rem 0 0 0; color: #999; font-size: 0.75rem;">{{ $receipt->reviewed_at->diffForHumans() }}</p>
                </div>

                <div>
                    <p style="margin: 0; color: #666; font-size: 0.75rem; text-transform: uppercase;">
                        {{ $receipt->status === 'approved' ? 'Approval Notes' : 'Rejection Reason' }}
                    </p>
                    <p style="margin: 0.5rem 0 0 0; padding: 0.75rem; background: #f8f9fa; border-radius: 0.375rem; color: #2c3e50;">
                        {{ $receipt->status === 'approved' 
                            ? ($receipt->accountant_notes ?: 'No notes provided') 
                            : ($receipt->rejection_reason ?: 'No reason provided') }}
                    </p>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
