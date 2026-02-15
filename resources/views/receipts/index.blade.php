@extends(auth()->user()->isSuperAdmin() ? 'layouts.super-admin' : 'layouts.admin')

@section('content')
<style>
    .card {
        background: white;
        padding: 1.5rem;
        border-radius: 0.5rem;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        margin-bottom: 1.5rem;
    }
    
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }
    
    .stat-card {
        background: white;
        padding: 1.5rem;
        border-radius: 0.5rem;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        border-left: 4px solid;
        transition: transform 0.2s, box-shadow 0.2s;
    }
    
    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.15);
    }
    
    .stat-card.pending { 
        border-left-color: #f59e0b; 
        background: linear-gradient(135deg, #ffffff, #fef3c7);
    }
    .stat-card.approved { 
        border-left-color: #10b981; 
        background: linear-gradient(135deg, #ffffff, #d1fae5);
    }
    .stat-card.rejected { 
        border-left-color: #ef4444; 
        background: linear-gradient(135deg, #ffffff, #fee2e2);
    }
    .stat-card.processing { 
        border-left-color: #3b82f6; 
        background: linear-gradient(135deg, #ffffff, #dbeafe);
    }
    
    .stat-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 0.75rem;
    }
    
    .stat-icon {
        font-size: 2rem;
        opacity: 0.8;
    }
    
    .stat-label { 
        color: #6b7280; 
        font-size: 0.75rem; 
        text-transform: uppercase;
        font-weight: 600;
        letter-spacing: 0.05em;
    }
    
    .stat-value { 
        font-size: 2rem; 
        font-weight: 800; 
        color: #111827;
        line-height: 1;
        margin: 0.5rem 0;
    }
    
    .stat-subtitle { 
        color: #6b7280; 
        font-size: 0.875rem; 
        margin-top: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .quick-filters {
        display: flex;
        gap: 0.75rem;
        flex-wrap: wrap;
        margin-bottom: 1.5rem;
    }
    
    .quick-filter-btn {
        padding: 0.625rem 1.25rem;
        border-radius: 0.5rem;
        border: 2px solid #e5e7eb;
        background: white;
        cursor: pointer;
        transition: all 0.2s;
        font-size: 0.875rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .quick-filter-btn:hover {
        border-color: #3b82f6;
        background: #eff6ff;
        transform: translateY(-1px);
    }
    
    .quick-filter-btn.active {
        background: #3b82f6;
        color: white;
        border-color: #3b82f6;
    }
    
    .quick-filter-btn .badge {
        background: rgba(0,0,0,0.1);
        padding: 0.125rem 0.5rem;
        border-radius: 0.25rem;
        font-size: 0.75rem;
    }
    
    .quick-filter-btn.active .badge {
        background: rgba(255,255,255,0.2);
    }
    
    .filter-section {
        background: white;
        padding: 0;
        border-radius: 0.5rem;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        margin-bottom: 1.5rem;
        overflow: hidden;
    }
    
    .filter-header {
        padding: 1rem 1.5rem;
        background: #f9fafb;
        border-bottom: 1px solid #e5e7eb;
        cursor: pointer;
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-weight: 600;
        color: #374151;
    }
    
    .filter-header:hover {
        background: #f3f4f6;
    }
    
    .filter-content {
        padding: 1.5rem;
        display: none;
    }
    
    .filter-content.active {
        display: block;
    }
    
    .filter-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
    }
    
    .form-group {
        display: flex;
        flex-direction: column;
    }
    
    .form-group label {
        font-size: 0.875rem;
        font-weight: 600;
        color: #374151;
        margin-bottom: 0.5rem;
    }
    
    .form-group input,
    .form-group select {
        padding: 0.625rem 0.75rem;
        border: 2px solid #e5e7eb;
        border-radius: 0.375rem;
        font-size: 0.875rem;
        transition: border-color 0.2s;
    }
    
    .form-group input:focus,
    .form-group select:focus {
        outline: none;
        border-color: #3b82f6;
    }
    
    .receipt-table {
        background: white;
        border-radius: 0.5rem;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        overflow: hidden;
    }
    
    .receipt-table table {
        width: 100%;
        border-collapse: collapse;
    }
    
    .receipt-table th {
        background: #f9fafb;
        padding: 1rem;
        text-align: left;
        font-size: 0.75rem;
        font-weight: 700;
        color: #6b7280;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        border-bottom: 2px solid #e5e7eb;
    }
    
    .receipt-table td {
        padding: 1rem;
        border-bottom: 1px solid #f3f4f6;
    }
    
    .receipt-table tr:hover {
        background: #f9fafb;
    }
    
    .receipt-table tr:last-child td {
        border-bottom: none;
    }
    
    .thumbnail {
        width: 50px;
        height: 50px;
        border-radius: 0.375rem;
        object-fit: cover;
        border: 2px solid #e5e7eb;
    }
    
    .file-icon {
        width: 50px;
        height: 50px;
        border-radius: 0.375rem;
        background: #f3f4f6;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        border: 2px solid #e5e7eb;
    }
    
    .status-badge {
        padding: 0.375rem 0.75rem;
        border-radius: 0.375rem;
        font-size: 0.75rem;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
    }
    
    .status-badge.pending {
        background: #fef3c7;
        color: #92400e;
    }
    
    .status-badge.approved {
        background: #d1fae5;
        color: #065f46;
    }
    
    .status-badge.rejected {
        background: #fee2e2;
        color: #991b1b;
    }
    
    .action-btn {
        padding: 0.5rem 1rem;
        border-radius: 0.375rem;
        font-size: 0.875rem;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.2s;
        display: inline-block;
    }
    
    .action-btn.primary {
        background: #3b82f6;
        color: white;
    }
    
    .action-btn.primary:hover {
        background: #2563eb;
    }
    
    .action-btn.secondary {
        background: #f3f4f6;
        color: #374151;
    }
    
    .action-btn.secondary:hover {
        background: #e5e7eb;
    }
    
    .waiting-badge {
        padding: 0.25rem 0.5rem;
        border-radius: 0.25rem;
        font-size: 0.75rem;
        font-weight: 600;
    }
    
    .waiting-badge.urgent {
        background: #fee2e2;
        color: #991b1b;
    }
    
    .waiting-badge.high {
        background: #fef3c7;
        color: #92400e;
    }
    
    .waiting-badge.normal {
        background: #e5e7eb;
        color: #374151;
    }
    
    .bulk-actions {
        padding: 1rem 1.5rem;
        background: #f9fafb;
        border-bottom: 1px solid #e5e7eb;
        display: none;
        align-items: center;
        gap: 1rem;
    }
    
    .bulk-actions.active {
        display: flex;
    }
    
    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        color: #6b7280;
    }
    
    .empty-state-icon {
        font-size: 4rem;
        margin-bottom: 1rem;
        opacity: 0.5;
    }
</style>

<div style="margin-bottom: 2rem;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
        <div>
            <h1 style="font-size: 1.875rem; font-weight: 800; color: #111827; margin: 0;">📋 All Receipts</h1>
            <p style="color: #6b7280; margin: 0.5rem 0 0 0;">Comprehensive receipt management and review system</p>
        </div>
        <div style="display: flex; gap: 0.75rem;">
            <a href="{{ route('receipts.pending') }}" class="action-btn primary">
                ⏳ Pending Reviews ({{ $stats['pending_count'] }})
            </a>
            <a href="{{ route('receipts.export', request()->query()) }}" class="action-btn secondary">
                📊 Export Excel
            </a>
        </div>
    </div>


    @if(session('success'))
        <div style="padding: 1rem 1.5rem; background: #d1fae5; color: #065f46; border-radius: 0.5rem; margin-bottom: 1.5rem; border-left: 4px solid #10b981;">
            ✓ {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div style="padding: 1rem 1.5rem; background: #fee2e2; color: #991b1b; border-radius: 0.5rem; margin-bottom: 1.5rem; border-left: 4px solid #ef4444;">
            <strong>⚠️ Errors:</strong>
            <ul style="margin: 0.5rem 0 0 0; padding-left: 1.5rem;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Enhanced Stats Cards -->
    <div class="stats-grid">
        <div class="stat-card pending">
            <div class="stat-header">
                <div class="stat-label">⏳ Pending Review</div>
                <div class="stat-icon">⏳</div>
            </div>
            <div class="stat-value">{{ $stats['pending_count'] }}</div>
            <div class="stat-subtitle">
                <span>K{{ number_format($stats['pending_amount'], 2) }}</span>
                <span style="color: #d97706;">awaiting review</span>
            </div>
        </div>
        
        <div class="stat-card approved">
            <div class="stat-header">
                <div class="stat-label">✅ Approved</div>
                <div class="stat-icon">✓</div>
            </div>
            <div class="stat-value">{{ $stats['approved_count'] }}</div>
            <div class="stat-subtitle">
                <span>K{{ number_format($stats['approved_amount'], 2) }}</span>
                <span style="color: #059669;">{{ $stats['approval_rate'] }}% approval rate</span>
            </div>
        </div>
        
        <div class="stat-card rejected">
            <div class="stat-header">
                <div class="stat-label">❌ Rejected</div>
                <div class="stat-icon">✗</div>
            </div>
            <div class="stat-value">{{ $stats['rejected_count'] }}</div>
            <div class="stat-subtitle">
                <span>K{{ number_format($stats['rejected_amount'], 2) }}</span>
                <span style="color: #dc2626;">rejected</span>
            </div>
        </div>
        
        <div class="stat-card processing">
            <div class="stat-header">
                <div class="stat-label">🕒 Avg Processing</div>
                <div class="stat-icon">⏱️</div>
            </div>
            <div class="stat-value">{{ $stats['avg_processing_days'] }}</div>
            <div class="stat-subtitle">
                <span>days to review</span>
                <span style="color: #2563eb;">average time</span>
            </div>
        </div>
    </div>

    <!-- Quick Status Filters -->
    <div class="quick-filters">
        <button class="quick-filter-btn {{ !request('status') ? 'active' : '' }}" 
                onclick="window.location.href='{{ route('receipts.index', array_merge(request()->except('status'), [])) }}'">
            📊 All Receipts
            <span class="badge">{{ $stats['total_count'] }}</span>
        </button>
        <button class="quick-filter-btn {{ request('status') == 'pending' ? 'active' : '' }}" 
                onclick="window.location.href='{{ route('receipts.index', array_merge(request()->except('page'), ['status' => 'pending'])) }}'">
            ⏳ Pending
            <span class="badge">{{ $stats['pending_count'] }}</span>
        </button>
        <button class="quick-filter-btn {{ request('status') == 'approved' ? 'active' : '' }}" 
                onclick="window.location.href='{{ route('receipts.index', array_merge(request()->except('page'), ['status' => 'approved'])) }}'">
            ✓ Approved
            <span class="badge">{{ $stats['approved_count'] }}</span>
        </button>
        <button class="quick-filter-btn {{ request('status') == 'rejected' ? 'active' : '' }}" 
                onclick="window.location.href='{{ route('receipts.index', array_merge(request()->except('page'), ['status' => 'rejected'])) }}'">
            ✗ Rejected
            <span class="badge">{{ $stats['rejected_count'] }}</span>
        </button>
        
        <!-- Quick Date Filters -->
        <div style="margin-left: auto; display: flex; gap: 0.5rem;">
            <button class="quick-filter-btn {{ request('date_from') == now()->toDateString() ? 'active' : '' }}" 
                    onclick="window.location.href='{{ route('receipts.index', array_merge(request()->except('page'), ['date_from' => now()->toDateString(), 'date_to' => now()->toDateString()])) }}'">
                📅 Today
            </button>
            <button class="quick-filter-btn {{ request('date_from') == now()->startOfWeek()->toDateString() && request('date_to') == now()->endOfWeek()->toDateString() ? 'active' : '' }}" 
                    onclick="window.location.href='{{ route('receipts.index', array_merge(request()->except('page'), ['date_from' => now()->startOfWeek()->toDateString(), 'date_to' => now()->endOfWeek()->toDateString()])) }}'">
                📅 This Week
            </button>
            <button class="quick-filter-btn {{ request('date_from') == now()->startOfMonth()->toDateString() ? 'active' : '' }}" 
                    onclick="window.location.href='{{ route('receipts.index', array_merge(request()->except('page'), ['date_from' => now()->startOfMonth()->toDateString(), 'date_to' => now()->endOfMonth()->toDateString()])) }}'">
                📅 This Month
            </button>
        </div>
    </div>

    <!-- Advanced Filters (Collapsible) -->
    <div class="filter-section">
        <div class="filter-header" onclick="toggleFilters()">
            <span>🔍 Advanced Filters & Search</span>
            <span id="filter-toggle">▼</span>
        </div>
        <div class="filter-content" id="filter-content">
            <form method="GET" action="{{ route('receipts.index') }}">
                <div class="filter-grid">
                    <div class="form-group">
                        <label>🔎 Search</label>
                        <input type="text" name="search" value="{{ request('search') }}" 
                               placeholder="Student name, ID, filename...">
                    </div>
                    <div class="form-group">
                        <label>📊 Status</label>
                        <select name="status">
                            <option value="">All Statuses</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>⏳ Pending</option>
                            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>✓ Approved</option>
                            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>✗ Rejected</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>🎓 Program</label>
                        <select name="program">
                            <option value="">All Programs</option>
                            @foreach($programs as $program)
                                <option value="{{ $program }}" {{ request('program') == $program ? 'selected' : '' }}>{{ $program }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>👤 Reviewer</label>
                        <select name="reviewer_id">
                            <option value="">All Reviewers</option>
                            @foreach($reviewers as $reviewer)
                                <option value="{{ $reviewer->id }}" {{ request('reviewer_id') == $reviewer->id ? 'selected' : '' }}>
                                    {{ $reviewer->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>📅 Date From</label>
                        <input type="date" name="date_from" value="{{ request('date_from') }}">
                    </div>
                    <div class="form-group">
                        <label>📅 Date To</label>
                        <input type="date" name="date_to" value="{{ request('date_to') }}">
                    </div>
                    <div class="form-group">
                        <label>💰 Min Amount (K)</label>
                        <input type="number" step="0.01" name="amount_min" value="{{ request('amount_min') }}" 
                               placeholder="0.00">
                    </div>
                    <div class="form-group">
                        <label>💰 Max Amount (K)</label>
                        <input type="number" step="0.01" name="amount_max" value="{{ request('amount_max') }}" 
                               placeholder="10000.00">
                    </div>
                </div>
                <div style="margin-top: 1.5rem; display: flex; gap: 0.75rem;">
                    <button type="submit" class="action-btn primary">
                        Apply Filters
                    </button>
                    <a href="{{ route('receipts.index') }}" class="action-btn secondary">
                        Clear All
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Receipts Table -->
    <div class="receipt-table">
        @if($receipts->count() > 0)
            <!-- Bulk Actions Bar -->
            <div class="bulk-actions" id="bulk-actions">
                <input type="checkbox" id="select-all" onchange="toggleAllCheckboxes(this)">
                <span id="selected-count" style="font-weight: 600; color: #374151;">0 selected</span>
                <button class="action-btn primary" style="padding: 0.5rem 1rem;" onclick="bulkApprove()">
                    ✓ Bulk Approve
                </button>
                <button class="action-btn secondary" style="padding: 0.5rem 1rem; background: #fee2e2; color: #991b1b;" onclick="bulkReject()">
                    ✗ Bulk Reject
                </button>
            </div>
            
            <table>
                <thead>
                    <tr>
                        <th style="width: 40px;">
                            <input type="checkbox" onchange="toggleAllCheckboxes(this)">
                        </th>
                        <th style="width: 60px;">Preview</th>
                        <th>Student</th>
                        <th>Program</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Uploaded</th>
                        <th>Reviewed</th>
                        <th>Waiting</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($receipts as $receipt)
                        @php
                            $fileExt = strtoupper(pathinfo($receipt->file_path, PATHINFO_EXTENSION));
                            $filePath = storage_path('app/public/' . $receipt->file_path);
                            $fileSize = file_exists($filePath) ? round(filesize($filePath) / 1024, 1) : 0;
                            $isImage = in_array($fileExt, ['JPG', 'JPEG', 'PNG', 'GIF']);
                            
                            // Calculate waiting time for pending receipts
                            $waitingDays = $receipt->status === 'pending' 
                                ? $receipt->uploaded_at->diffInDays(now()) 
                                : null;
                            $waitingClass = $waitingDays > 5 ? 'urgent' : ($waitingDays > 3 ? 'high' : 'normal');
                            
                            // Processing time for reviewed receipts
                            $processingDays = $receipt->reviewed_at 
                                ? round($receipt->uploaded_at->diffInHours($receipt->reviewed_at) / 24, 1) 
                                : null;
                        @endphp
                        <tr>
                            <td>
                                @if($receipt->status === 'pending')
                                    <input type="checkbox" class="receipt-checkbox" value="{{ $receipt->id }}" onchange="updateBulkActions()">
                                @endif
                            </td>
                            <td>
                                @if($isImage)
                                    <img src="{{ asset('storage/' . $receipt->file_path) }}" alt="Receipt" class="thumbnail">
                                @else
                                    <div class="file-icon">📄</div>
                                @endif
                            </td>
                            <td>
                                <div>
                                    <p style="font-weight: 600; color: #111827; margin: 0;">
                                        {{ $receipt->student->first_name }} {{ $receipt->student->last_name }}
                                    </p>
                                    <p style="font-size: 0.75rem; color: #6b7280; margin: 0.25rem 0 0 0;">
                                        {{ $receipt->student->student_id }}
                                    </p>
                                </div>
                            </td>
                            <td>
                                <span style="font-size: 0.875rem; color: #374151;">
                                    {{ $receipt->student->program ?? 'N/A' }}
                                </span>
                            </td>
                            <td>
                                <span style="font-weight: 700; color: #111827; font-size: 0.875rem;">
                                    K{{ number_format($receipt->amount, 2) }}
                                </span>
                                <div style="font-size: 0.75rem; color: #6b7280; margin-top: 0.25rem;">
                                    {{ $fileExt }} • {{ $fileSize }} KB
                                </div>
                            </td>
                            <td>
                                <span class="status-badge {{ $receipt->status }}">
                                    @if($receipt->status === 'pending')
                                        ⏳ Pending
                                    @elseif($receipt->status === 'approved')
                                        ✓ Approved
                                    @else
                                        ✗ Rejected
                                    @endif
                                </span>
                            </td>
                            <td>
                                <div style="font-size: 0.875rem; color: #374151;">
                                    {{ $receipt->uploaded_at->format('M d, Y') }}
                                </div>
                                <div style="font-size: 0.75rem; color: #6b7280;">
                                    {{ $receipt->uploaded_at->format('h:i A') }}
                                </div>
                                <div style="font-size: 0.75rem; color: #9ca3af; margin-top: 0.25rem;">
                                    {{ $receipt->uploaded_at->diffForHumans() }}
                                </div>
                            </td>
                            <td>
                                @if($receipt->reviewed_at)
                                    <div style="font-size: 0.875rem; color: #374151;">
                                        {{ $receipt->reviewed_at->format('M d, Y') }}
                                    </div>
                                    <div style="font-size: 0.75rem; color: #6b7280;">
                                        {{ $receipt->reviewer->name ?? 'Unknown' }}
                                    </div>
                                @else
                                    <span style="color: #9ca3af; font-size: 0.875rem;">Not reviewed</span>
                                @endif
                            </td>
                            <td>
                                @if($receipt->status === 'pending' && $waitingDays !== null)
                                    <span class="waiting-badge {{ $waitingClass }}">
                                        @if($waitingClass === 'urgent')
                                            🔴 {{ $waitingDays }}d
                                        @elseif($waitingClass === 'high')
                                            🟡 {{ $waitingDays }}d
                                        @else
                                            🟢 {{ $waitingDays }}d
                                        @endif
                                    </span>
                                @elseif($processingDays !== null)
                                    <span style="font-size: 0.875rem; color: #6b7280;">
                                        {{ $processingDays }}d
                                    </span>
                                @else
                                    <span style="color: #9ca3af;">-</span>
                                @endif
                            </td>
                            <td>
                                <div style="display: flex; gap: 0.5rem;">
                                    <a href="{{ route('receipts.show', $receipt) }}" class="action-btn primary" style="padding: 0.375rem 0.75rem; font-size: 0.75rem;">
                                        👁️ Review
                                    </a>
                                    <a href="{{ route('receipts.download', $receipt) }}" class="action-btn secondary" style="padding: 0.375rem 0.75rem; font-size: 0.75rem;" target="_blank">
                                        📥 File
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="empty-state">
                <div class="empty-state-icon">📭</div>
                <h3 style="font-size: 1.25rem; font-weight: 700; color: #374151; margin: 0 0 0.5rem 0;">
                    No Receipts Found
                </h3>
                <p style="margin: 0; color: #6b7280;">
                    @if(request()->hasAny(['search', 'status', 'program', 'date_from', 'amount_min']))
                        Try adjusting your filters or search terms.
                    @else
                        No receipts have been uploaded yet.
                    @endif
                </p>
                @if(request()->hasAny(['search', 'status', 'program', 'date_from', 'amount_min']))
                    <a href="{{ route('receipts.index') }}" class="action-btn primary" style="margin-top: 1rem;">
                        Clear Filters
                    </a>
                @endif
            </div>
        @endif
    </div>

    @if($receipts->hasPages())
        <div style="margin-top: 1.5rem; display: flex; justify-content: center;">
            {{ $receipts->links() }}
        </div>
    @endif
</div>

<script>
function toggleFilters() {
    const content = document.getElementById('filter-content');
    const toggle = document.getElementById('filter-toggle');
    content.classList.toggle('active');
    toggle.textContent = content.classList.contains('active') ? '▲' : '▼';
}

function toggleAllCheckboxes(source) {
    const checkboxes = document.querySelectorAll('.receipt-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = source.checked;
    });
    updateBulkActions();
}

function updateBulkActions() {
    const checkboxes = document.querySelectorAll('.receipt-checkbox:checked');
    const count = checkboxes.length;
    const bulkActions = document.getElementById('bulk-actions');
    const selectedCount = document.getElementById('selected-count');
    
    if (count > 0) {
        bulkActions.classList.add('active');
        selectedCount.textContent = count + ' selected';
    } else {
        bulkActions.classList.remove('active');
    }
}

function bulkApprove() {
    const checkboxes = document.querySelectorAll('.receipt-checkbox:checked');
    const ids = Array.from(checkboxes).map(cb => cb.value);
    
    if (ids.length === 0) return;
    
    if (confirm(`Are you sure you want to approve ${ids.length} receipt(s)?`)) {
        // TODO: Implement bulk approval API endpoint
        alert('Bulk approval feature - to be implemented with controller method');
    }
}

function bulkReject() {
    const checkboxes = document.querySelectorAll('.receipt-checkbox:checked');
    const ids = Array.from(checkboxes).map(cb => cb.value);
    
    if (ids.length === 0) return;
    
    const reason = prompt(`Enter rejection reason for ${ids.length} receipt(s):`);
    if (reason) {
        // TODO: Implement bulk rejection API endpoint
        alert('Bulk rejection feature - to be implemented with controller method');
    }
}

// Auto-expand filters if any filter is active
@if(request()->hasAny(['search', 'program', 'reviewer_id', 'date_from', 'date_to', 'amount_min', 'amount_max']))
    document.addEventListener('DOMContentLoaded', function() {
        toggleFilters();
    });
@endif
</script>

@endsection
