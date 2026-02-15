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
    
    .stat-card.blue { 
        border-left-color: #3b82f6; 
        background: linear-gradient(135deg, #ffffff, #dbeafe);
    }
    .stat-card.green { 
        border-left-color: #10b981; 
        background: linear-gradient(135deg, #ffffff, #d1fae5);
    }
    .stat-card.orange { 
        border-left-color: #f59e0b; 
        background: linear-gradient(135deg, #ffffff, #fef3c7);
    }
    .stat-card.red { 
        border-left-color: #ef4444; 
        background: linear-gradient(135deg, #ffffff, #fee2e2);
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
    
    .student-table {
        background: white;
        border-radius: 0.5rem;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        overflow: hidden;
    }
    
    .student-table table {
        width: 100%;
        border-collapse: collapse;
    }
    
    .student-table th {
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
    
    .student-table td {
        padding: 1rem;
        border-bottom: 1px solid #f3f4f6;
    }
    
    .student-table tr:hover {
        background: #f9fafb;
    }
    
    .student-table tr:last-child td {
        border-bottom: none;
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
    
    .status-badge.active {
        background: #d1fae5;
        color: #065f46;
    }
    
    .status-badge.inactive {
        background: #fee2e2;
        color: #991b1b;
    }
    
    .financial-badge {
        padding: 0.25rem 0.5rem;
        border-radius: 0.25rem;
        font-size: 0.75rem;
        font-weight: 600;
    }
    
    .financial-badge.paid {
        background: #d1fae5;
        color: #065f46;
    }
    
    .financial-badge.owing {
        background: #fef3c7;
        color: #92400e;
    }
    
    .action-btn {
        padding: 0.375rem 0.75rem;
        border-radius: 0.375rem;
        font-size: 0.75rem;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.2s;
        display: inline-block;
        border: none;
        cursor: pointer;
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
    
    .action-btn.success {
        background: #10b981;
        color: white;
    }
    
    .action-btn.success:hover {
        background: #059669;
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
            <h1 style="font-size: 1.875rem; font-weight: 800; color: #111827; margin: 0;">💰 Student Financial Records</h1>
            <p style="color: #6b7280; margin: 0.5rem 0 0 0;">View student information and financial status</p>
        </div>
        <div style="display: flex; gap: 0.75rem;">
            <a href="{{ route('students.export') }}" class="action-btn success">
                📊 Export Financial Report
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

    <!-- Enhanced Financial Stats -->
    <div class="stats-grid">
        <div class="stat-card blue">
            <div class="stat-header">
                <div class="stat-label">👥 Total Students</div>
                <div class="stat-icon">👥</div>
            </div>
            <div class="stat-value">{{ number_format($stats['total']) }}</div>
            <div class="stat-subtitle">{{ $stats['active'] }} active students</div>
        </div>
        
        <div class="stat-card green">
            <div class="stat-header">
                <div class="stat-label">💰 Total Collected</div>
                <div class="stat-icon">💰</div>
            </div>
            <div class="stat-value">K{{ number_format($stats['total_collected'], 0) }}</div>
            <div class="stat-subtitle">from K{{ number_format($stats['total_fees'], 0) }} total fees</div>
        </div>
        
        <div class="stat-card orange">
            <div class="stat-header">
                <div class="stat-label">⏳ Outstanding Balance</div>
                <div class="stat-icon">⏳</div>
            </div>
            <div class="stat-value">K{{ number_format($stats['outstanding_balance'], 0) }}</div>
            <div class="stat-subtitle">{{ $stats['students_owing'] }} students owing</div>
        </div>
        
        <div class="stat-card red">
            <div class="stat-header">
                <div class="stat-label">📋 Pending Receipts</div>
                <div class="stat-icon">📋</div>
            </div>
            <div class="stat-value">{{ $stats['pending_receipts'] }}</div>
            <div class="stat-subtitle">awaiting review</div>
        </div>
    </div>

    <!-- Quick Status Filters -->
    <div class="quick-filters">
        <button class="quick-filter-btn {{ !request('financial_status') ? 'active' : '' }}" 
                onclick="window.location.href='{{ route('students.index', array_merge(request()->except(['financial_status', 'page']), [])) }}'">
            📊 All Students
        </button>
        <button class="quick-filter-btn {{ request('financial_status') == 'owing' ? 'active' : '' }}" 
                onclick="window.location.href='{{ route('students.index', array_merge(request()->except('page'), ['financial_status' => 'owing'])) }}'">
            ⚠️ Owing Balance
        </button>
        <button class="quick-filter-btn {{ request('financial_status') == 'paid' ? 'active' : '' }}" 
                onclick="window.location.href='{{ route('students.index', array_merge(request()->except('page'), ['financial_status' => 'paid'])) }}'">
            ✓ Fully Paid
        </button>
    </div>

    <!-- Advanced Filters (Collapsible) -->
    <div class="filter-section">
        <div class="filter-header" onclick="toggleFilters()">
            <span>🔍 Search & Filters</span>
            <span id="filter-toggle">▼</span>
        </div>
        <div class="filter-content" id="filter-content">
            <form method="GET" action="{{ route('students.index') }}">
                <div class="filter-grid">
                    <div class="form-group">
                        <label>🔎 Search</label>
                        <input type="text" name="search" value="{{ request('search') }}" 
                               placeholder="Name, Email, or Student ID...">
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
                        <label>📚 Year</label>
                        <select name="year">
                            <option value="">All Years</option>
                            @for($i = 1; $i <= 5; $i++)
                                <option value="{{ $i }}" {{ request('year') == $i ? 'selected' : '' }}>Year {{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="form-group">
                        <label>📅 Semester</label>
                        <select name="semester">
                            <option value="">All Semesters</option>
                            <option value="1" {{ request('semester') == '1' ? 'selected' : '' }}>Semester 1</option>
                            <option value="2" {{ request('semester') == '2' ? 'selected' : '' }}>Semester 2</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>💰 Financial Status</label>
                        <select name="financial_status">
                            <option value="">All Statuses</option>
                            <option value="owing" {{ request('financial_status') == 'owing' ? 'selected' : '' }}>Owing Balance</option>
                            <option value="paid" {{ request('financial_status') == 'paid' ? 'selected' : '' }}>Fully Paid</option>
                        </select>
                    </div>
                </div>
                <div style="margin-top: 1.5rem; display: flex; gap: 0.75rem;">
                    <button type="submit" class="action-btn primary">
                        Apply Filters
                    </button>
                    <a href="{{ route('students.index') }}" class="action-btn secondary">
                        Clear All
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Students Table -->
    <div class="student-table">
        @if($students->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th>Student ID</th>
                        <th>Name</th>
                        <th>Program</th>
                        <th>Year/Sem</th>
                        <th>Status</th>
                        <th>Tuition Fee</th>
                        <th>Total Paid</th>
                        <th>Balance</th>
                        <th>Pending</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($students as $student)
                        <tr>
                            <td>
                                <span style="font-weight: 700; color: #111827; font-family: monospace;">
                                    {{ $student->student_id }}
                                </span>
                            </td>
                            <td>
                                <div>
                                    <p style="font-weight: 600; color: #111827; margin: 0;">
                                        {{ $student->first_name }} {{ $student->last_name }}
                                    </p>
                                    <p style="font-size: 0.75rem; color: #6b7280; margin: 0.25rem 0 0 0;">
                                        {{ $student->email }}
                                    </p>
                                </div>
                            </td>
                            <td>
                                <span style="font-size: 0.875rem; color: #374151;">
                                    {{ $student->program ?? 'N/A' }}
                                </span>
                            </td>
                            <td>
                                <span style="font-size: 0.875rem; color: #374151;">
                                    Y{{ $student->year ?? '-' }} / S{{ $student->semester ?? '-' }}
                                </span>
                            </td>
                            <td>
                                <span class="status-badge {{ $student->status }}">
                                    {{ $student->status === 'active' ? '✓' : '✗' }} {{ ucfirst($student->status) }}
                                </span>
                            </td>
                            <td>
                                <span style="font-weight: 600; color: #111827;">
                                    K{{ number_format($student->tuition_fee ?? 0, 2) }}
                                </span>
                            </td>
                            <td>
                                <span style="font-weight: 600; color: #059669;">
                                    K{{ number_format($student->total_paid, 2) }}
                                </span>
                            </td>
                            <td>
                                @if($student->balance > 0)
                                    <span style="font-weight: 700; color: #dc2626;">
                                        K{{ number_format($student->balance, 2) }}
                                    </span>
                                    <br>
                                    <span class="financial-badge owing">Owing</span>
                                @else
                                    <span style="font-weight: 600; color: #059669;">K0.00</span>
                                    <br>
                                    <span class="financial-badge paid">Paid</span>
                                @endif
                            </td>
                            <td>
                                @if($student->pending_receipts > 0)
                                    <span style="padding: 0.25rem 0.5rem; background: #fef3c7; color: #92400e; border-radius: 0.25rem; font-size: 0.75rem; font-weight: 600;">
                                        {{ $student->pending_receipts }} receipt{{ $student->pending_receipts > 1 ? 's' : '' }}
                                    </span>
                                @else
                                    <span style="color: #9ca3af; font-size: 0.875rem;">None</span>
                                @endif
                            </td>
                            <td>
                                <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                                    <a href="{{ route('payments.student-payments', $student) }}" class="action-btn primary">
                                        💰 Payments
                                    </a>
                                    <a href="{{ route('receipts.student-receipts', $student) }}" class="action-btn secondary">
                                        📋 Receipts
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="empty-state">
                <div class="empty-state-icon">👥</div>
                <h3 style="font-size: 1.25rem; font-weight: 700; color: #374151; margin: 0 0 0.5rem 0;">
                    No Students Found
                </h3>
                <p style="margin: 0; color: #6b7280;">
                    @if(request()->hasAny(['search', 'program', 'year', 'semester', 'financial_status']))
                        Try adjusting your filters or search terms.
                    @else
                        No student records available.
                    @endif
                </p>
                @if(request()->hasAny(['search', 'program', 'year', 'semester', 'financial_status']))
                    <a href="{{ route('students.index') }}" class="action-btn primary" style="margin-top: 1rem;">
                        Clear Filters
                    </a>
                @endif
            </div>
        @endif
    </div>

    @if($students->hasPages())
        <div style="margin-top: 1.5rem; display: flex; justify-content: center;">
            {{ $students->links() }}
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

// Auto-expand filters if any filter is active
@if(request()->hasAny(['search', 'program', 'year', 'semester', 'financial_status']))
    document.addEventListener('DOMContentLoaded', function() {
        toggleFilters();
    });
@endif
</script>

@endsection
