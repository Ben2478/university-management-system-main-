@php($sidebarUser = auth()->user())
<style>
    .phoenix-app-shell { display:flex; align-items:stretch; min-height:calc(100vh - 76px); }
    .phoenix-app-shell > .phoenix-app-content { flex:1 1 0; min-width:0; background:#f6f7fb; padding:0; }
    .phoenix-app-content > .container { width:100%; min-width:0; }
    .phoenix-app-content .breadcrumb { width:min(100% - 2rem,1200px); margin-right:auto; margin-left:auto; }
    @media (max-width:680px) {
        .phoenix-app-shell { min-height:calc(100vh - 109px); }
        .phoenix-app-content > .container { padding:1.15rem 1rem; }
        .phoenix-app-content .breadcrumb { width:calc(100% - 2rem); }
    }

    .phoenix-sidebar {
        --sidebar-width: 272px;
        --sidebar-compact-width: 82px;
        position: relative;
        z-index: 20;
        display: flex;
        flex: 0 0 var(--sidebar-width);
        flex-direction: column;
        width: var(--sidebar-width);
        min-height: calc(100vh - 76px);
        padding: 1rem 0.7rem 0.85rem;
        overflow-x: hidden;
        overflow-y: auto;
        overscroll-behavior: contain;
        color: #f7f5ff;
        background: linear-gradient(180deg, #191629 0%, #211b37 58%, #241a3d 100%);
        border-right: 1px solid rgba(255,255,255,.07);
        box-shadow: 8px 0 28px rgba(24,16,43,.12);
        transition: width .22s ease, flex-basis .22s ease, transform .24s ease;
        scrollbar-color: rgba(209,195,239,.28) transparent;
        scrollbar-width: thin;
    }
    .phoenix-sidebar::-webkit-scrollbar { width: 6px; }
    .phoenix-sidebar::-webkit-scrollbar-thumb { background: rgba(209,195,239,.25); border-radius: 999px; }
    .phoenix-sidebar-top { display:flex; align-items:center; justify-content:flex-end; min-height:34px; margin:0 .2rem .55rem; }
    .phoenix-sidebar-collapse { display:grid; place-items:center; width:34px; height:34px; padding:0; color:#c8bfdc; background:transparent; border:1px solid rgba(255,255,255,.1); border-radius:.55rem; cursor:pointer; transition:background .16s ease,color .16s ease,border-color .16s ease; }
    .phoenix-sidebar-collapse:hover { color:#fff; background:rgba(255,255,255,.08); border-color:rgba(255,255,255,.2); }
    .phoenix-sidebar-collapse svg { width:17px; height:17px; fill:none; stroke:currentColor; stroke-width:1.8; stroke-linecap:round; stroke-linejoin:round; }
    .phoenix-sidebar-group { margin:0 0 .28rem; }
    .phoenix-sidebar-group + .phoenix-sidebar-group { border-top:1px solid rgba(255,255,255,.065); padding-top:.45rem; }
    .phoenix-sidebar-group-title { display:flex; align-items:center; width:100%; min-height:42px; gap:.75rem; padding:.55rem .7rem; color:#c2b9d5; background:transparent; border:0; border-radius:.65rem; cursor:pointer; text-align:left; font:inherit; font-size:.75rem; font-weight:650; }
    .phoenix-sidebar-group-title:hover { color:#fff; background:rgba(255,255,255,.055); }
    .phoenix-sidebar-group-title:focus-visible, .phoenix-sidebar-link:focus-visible, .phoenix-sidebar-collapse:focus-visible { outline:3px solid #d5b9ff; outline-offset:2px; }
    .phoenix-sidebar-group-title .phoenix-sidebar-icon { color:#bba2ed; }
    .phoenix-sidebar-group-label { flex:1; white-space:nowrap; }
    .phoenix-sidebar-chevron { width:15px; height:15px; flex:0 0 15px; fill:none; stroke:#a99fbd; stroke-width:1.8; stroke-linecap:round; stroke-linejoin:round; transition:transform .18s ease; }
    .phoenix-sidebar-group-title[aria-expanded="true"] .phoenix-sidebar-chevron { transform:rotate(180deg); }
    .phoenix-sidebar-items { display:grid; grid-template-rows:0fr; opacity:.72; transition:grid-template-rows .2s ease,opacity .2s ease; }
    .phoenix-sidebar-items-inner { min-height:0; overflow:hidden; }
    .phoenix-sidebar-items.open { grid-template-rows:1fr; opacity:1; }
    .phoenix-sidebar-link { position:relative; display:flex; align-items:center; min-height:42px; gap:.8rem; margin:.12rem 0; padding:.55rem .75rem; color:#d5cfe3; text-decoration:none; border:1px solid transparent; border-radius:.7rem; transition:background .16s ease,color .16s ease,border-color .16s ease,transform .16s ease; }
    .phoenix-sidebar-link:hover { color:#fff; background:rgba(255,255,255,.07); transform:translateX(1px); }
    .phoenix-sidebar a.phoenix-sidebar-link.is-active, .phoenix-sidebar a.phoenix-sidebar-link.is-active:hover { color:#fff; background:linear-gradient(105deg,rgba(131,81,218,.95),rgba(92,54,164,.8)); border-color:rgba(216,194,255,.2); box-shadow:0 5px 16px rgba(103,64,178,.24),inset 0 1px rgba(255,255,255,.12); font-weight:650; }
    .phoenix-sidebar-link.is-active::before { position:absolute; left:-.7rem; width:3px; height:22px; background:#d8baff; border-radius:0 4px 4px 0; content:""; }
    .phoenix-sidebar-icon { width:19px; height:19px; flex:0 0 19px; fill:none; stroke:currentColor; stroke-width:1.7; stroke-linecap:round; stroke-linejoin:round; }
    .phoenix-sidebar-link-label { overflow:hidden; white-space:nowrap; text-overflow:ellipsis; }
    .phoenix-sidebar-quick { margin-top:auto; padding-top:.8rem; border-top:1px solid rgba(255,255,255,.09); }
    .phoenix-sidebar-quick-title { margin:0 0 .45rem; padding:.35rem .7rem; color:#9e94b4; font-size:.67rem; font-weight:700; letter-spacing:.1em; text-transform:uppercase; white-space:nowrap; }
    .phoenix-sidebar-link.is-quick { color:#e4d8fa; }
    .phoenix-sidebar-link.is-quick .phoenix-sidebar-icon { color:#c49bf5; }
    .phoenix-sidebar-empty { padding:.35rem .75rem; color:#827995; font-size:.72rem; }
    .sidebar-overlay { z-index:1490; }
    @media (min-width:981px) {
        body.sidebar-collapsed .phoenix-sidebar { width:var(--sidebar-compact-width); flex-basis:var(--sidebar-compact-width); padding-right:.55rem; padding-left:.55rem; }
        body.sidebar-collapsed .phoenix-sidebar-top { justify-content:center; }
        body.sidebar-collapsed .phoenix-sidebar-collapse svg { transform:rotate(180deg); }
        body.sidebar-collapsed .phoenix-sidebar-group-title { justify-content:center; padding-right:.2rem; padding-left:.2rem; }
        body.sidebar-collapsed .phoenix-sidebar-group-label, body.sidebar-collapsed .phoenix-sidebar-chevron, body.sidebar-collapsed .phoenix-sidebar-link-label, body.sidebar-collapsed .phoenix-sidebar-quick-title { display:none; }
        body.sidebar-collapsed .phoenix-sidebar-items { display:none; }
        body.sidebar-collapsed .phoenix-sidebar-link { justify-content:center; padding:.55rem .2rem; }
        body.sidebar-collapsed .phoenix-sidebar-link.is-active::before { left:-.55rem; }
    }
    @media (max-width:980px) {
        .phoenix-sidebar { position:fixed; top:76px; left:0; bottom:0; z-index:2000; width:min(300px,86vw); min-height:0; height:calc(100dvh - 76px); transform:translateX(-105%); box-shadow:18px 0 38px rgba(17,11,30,.3); }
        body.sidebar-open .phoenix-sidebar { transform:translateX(0); }
        body.sidebar-open .sidebar-overlay { display:block; }
        body.sidebar-open { overflow:hidden; }
        .phoenix-sidebar-collapse { display:none; }
    }
    @media (max-width:680px) { .phoenix-sidebar { top:109px; height:calc(100dvh - 109px); } }
    @media (prefers-reduced-motion:reduce) { .phoenix-sidebar, .phoenix-sidebar * { scroll-behavior:auto !important; transition-duration:.01ms !important; } }
</style>

<nav class="sidebar phoenix-sidebar" id="sidebar" aria-label="Main navigation" data-sidebar-role="{{ $sidebarUser->role }}">
    <div class="phoenix-sidebar-top">
        <button type="button" class="phoenix-sidebar-collapse" data-sidebar-collapse aria-label="Collapse sidebar" aria-pressed="false" title="Collapse sidebar">
            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="m14 5-7 7 7 7M7 12h14"/></svg>
        </button>
    </div>

    @if($sidebarUser->isSuperAdmin())
        <div class="phoenix-sidebar-group">
            <a class="phoenix-sidebar-link" href="{{ route('dashboard') }}" title="Dashboard">
                @include('components.sidebar-icon', ['name' => 'grid'])<span class="phoenix-sidebar-link-label">Dashboard</span>
            </a>
        </div>
        <div class="phoenix-sidebar-group">
            <button type="button" class="phoenix-sidebar-group-title" data-sidebar-group="academic" aria-controls="academic" aria-expanded="false" title="Academic Management">
                @include('components.sidebar-icon', ['name' => 'academic'])<span class="phoenix-sidebar-group-label">Academic Management</span><svg class="phoenix-sidebar-chevron" viewBox="0 0 24 24" aria-hidden="true"><path d="m7 10 5 5 5-5"/></svg>
            </button>
            <div class="phoenix-sidebar-items" id="academic"><div class="phoenix-sidebar-items-inner">
                <a class="phoenix-sidebar-link" href="{{ route('programs.index') }}" title="Programs">@include('components.sidebar-icon', ['name' => 'programs'])<span class="phoenix-sidebar-link-label">Programs</span></a>
                <a class="phoenix-sidebar-link" href="{{ route('academic-calendar.index') }}" title="Academic Calendar">@include('components.sidebar-icon', ['name' => 'calendar'])<span class="phoenix-sidebar-link-label">Academic Calendar</span></a>
                <a class="phoenix-sidebar-link" href="{{ route('students.index') }}" title="Students">@include('components.sidebar-icon', ['name' => 'students'])<span class="phoenix-sidebar-link-label">Students</span></a>
                <a class="phoenix-sidebar-link" href="{{ route('lecturers.index') }}" title="Lecturers">@include('components.sidebar-icon', ['name' => 'lecturers'])<span class="phoenix-sidebar-link-label">Lecturers</span></a>
                <a class="phoenix-sidebar-link" href="{{ route('courses.index') }}" title="Courses">@include('components.sidebar-icon', ['name' => 'courses'])<span class="phoenix-sidebar-link-label">Courses</span></a>
                <a class="phoenix-sidebar-link" href="{{ route('enrollments.index') }}" title="Enrollments">@include('components.sidebar-icon', ['name' => 'enrollments'])<span class="phoenix-sidebar-link-label">Enrollments</span></a>
            </div></div>
        </div>
        <div class="phoenix-sidebar-group">
            <button type="button" class="phoenix-sidebar-group-title" data-sidebar-group="records" aria-controls="records" aria-expanded="false" title="Academic Records">
                @include('components.sidebar-icon', ['name' => 'records'])<span class="phoenix-sidebar-group-label">Academic Records</span><svg class="phoenix-sidebar-chevron" viewBox="0 0 24 24" aria-hidden="true"><path d="m7 10 5 5 5-5"/></svg>
            </button>
            <div class="phoenix-sidebar-items" id="records"><div class="phoenix-sidebar-items-inner">
                <a class="phoenix-sidebar-link" href="{{ route('grades.index') }}" title="Grades">@include('components.sidebar-icon', ['name' => 'grades'])<span class="phoenix-sidebar-link-label">Grades</span></a>
                <a class="phoenix-sidebar-link" href="{{ route('attendance.index') }}" title="Attendance">@include('components.sidebar-icon', ['name' => 'attendance'])<span class="phoenix-sidebar-link-label">Attendance</span></a>
            </div></div>
        </div>
        <div class="phoenix-sidebar-group">
            <button type="button" class="phoenix-sidebar-group-title" data-sidebar-group="financial" aria-controls="financial" aria-expanded="false" title="Financial">
                @include('components.sidebar-icon', ['name' => 'finance'])<span class="phoenix-sidebar-group-label">Financial</span><svg class="phoenix-sidebar-chevron" viewBox="0 0 24 24" aria-hidden="true"><path d="m7 10 5 5 5-5"/></svg>
            </button>
            <div class="phoenix-sidebar-items" id="financial"><div class="phoenix-sidebar-items-inner">
                <a class="phoenix-sidebar-link" href="{{ route('payments.index') }}" title="Payments">@include('components.sidebar-icon', ['name' => 'payments'])<span class="phoenix-sidebar-link-label">Payments</span></a>
                <a class="phoenix-sidebar-link" href="{{ route('receipts.index') }}" title="Receipts">@include('components.sidebar-icon', ['name' => 'receipts'])<span class="phoenix-sidebar-link-label">Receipts</span></a>
            </div></div>
        </div>
        <div class="phoenix-sidebar-group">
            <button type="button" class="phoenix-sidebar-group-title" data-sidebar-group="admin" aria-controls="admin" aria-expanded="false" title="Administration">
                @include('components.sidebar-icon', ['name' => 'admin'])<span class="phoenix-sidebar-group-label">Administration</span><svg class="phoenix-sidebar-chevron" viewBox="0 0 24 24" aria-hidden="true"><path d="m7 10 5 5 5-5"/></svg>
            </button>
            <div class="phoenix-sidebar-items" id="admin"><div class="phoenix-sidebar-items-inner">
                <a class="phoenix-sidebar-link" href="{{ route('users.index') }}" title="User Management">@include('components.sidebar-icon', ['name' => 'settings'])<span class="phoenix-sidebar-link-label">User Management</span></a>
            </div></div>
        </div>
        <div class="phoenix-sidebar-group">
            <button type="button" class="phoenix-sidebar-group-title" data-sidebar-group="account" aria-controls="account" aria-expanded="false" title="Account">
                @include('components.sidebar-icon', ['name' => 'account'])<span class="phoenix-sidebar-group-label">Account</span><svg class="phoenix-sidebar-chevron" viewBox="0 0 24 24" aria-hidden="true"><path d="m7 10 5 5 5-5"/></svg>
            </button>
            <div class="phoenix-sidebar-items" id="account"><div class="phoenix-sidebar-items-inner">
                <a class="phoenix-sidebar-link" href="{{ route('profile.edit') }}" title="Profile Settings">@include('components.sidebar-icon', ['name' => 'profile'])<span class="phoenix-sidebar-link-label">Profile Settings</span></a>
                <a class="phoenix-sidebar-link" href="{{ route('logout') }}" data-logout-link title="Logout">@include('components.sidebar-icon', ['name' => 'logout'])<span class="phoenix-sidebar-link-label">Logout</span></a>
            </div></div>
        </div>
        <div class="phoenix-sidebar-quick">
            <p class="phoenix-sidebar-quick-title">Quick Actions</p>
            <a class="phoenix-sidebar-link is-quick" href="{{ route('students.create') }}" title="Add Student">@include('components.sidebar-icon', ['name' => 'quick'])<span class="phoenix-sidebar-link-label">Add Student</span></a>
            <a class="phoenix-sidebar-link is-quick" href="{{ route('courses.create') }}" title="Add Course">@include('components.sidebar-icon', ['name' => 'quick'])<span class="phoenix-sidebar-link-label">Add Course</span></a>
            <a class="phoenix-sidebar-link is-quick" href="{{ route('enrollments.create') }}" title="Create Enrollment">@include('components.sidebar-icon', ['name' => 'quick'])<span class="phoenix-sidebar-link-label">Create Enrollment</span></a>
            <a class="phoenix-sidebar-link is-quick" href="{{ route('programs.create') }}" title="Add Program">@include('components.sidebar-icon', ['name' => 'quick'])<span class="phoenix-sidebar-link-label">Add Program</span></a>
        </div>
    @elseif($sidebarUser->isAdmin())
        <div class="phoenix-sidebar-group"><a class="phoenix-sidebar-link" href="{{ route('dashboard') }}" title="Dashboard">@include('components.sidebar-icon', ['name' => 'grid'])<span class="phoenix-sidebar-link-label">Dashboard</span></a></div>
        <div class="phoenix-sidebar-group">
            <button type="button" class="phoenix-sidebar-group-title" data-sidebar-group="academic" aria-controls="academic" aria-expanded="false" title="Academic Management">@include('components.sidebar-icon', ['name' => 'academic'])<span class="phoenix-sidebar-group-label">Academic Management</span><svg class="phoenix-sidebar-chevron" viewBox="0 0 24 24" aria-hidden="true"><path d="m7 10 5 5 5-5"/></svg></button>
            <div class="phoenix-sidebar-items" id="academic"><div class="phoenix-sidebar-items-inner">
                <a class="phoenix-sidebar-link" href="{{ route('programs.index') }}" title="Programs">@include('components.sidebar-icon', ['name' => 'programs'])<span class="phoenix-sidebar-link-label">Programs</span></a>
                <a class="phoenix-sidebar-link" href="{{ route('academic-calendar.index') }}" title="Academic Calendar">@include('components.sidebar-icon', ['name' => 'calendar'])<span class="phoenix-sidebar-link-label">Academic Calendar</span></a>
                <a class="phoenix-sidebar-link" href="{{ route('students.index') }}" title="Students">@include('components.sidebar-icon', ['name' => 'students'])<span class="phoenix-sidebar-link-label">Students</span></a>
            </div></div>
        </div>
        <div class="phoenix-sidebar-group">
            <button type="button" class="phoenix-sidebar-group-title" data-sidebar-group="financial" aria-controls="financial" aria-expanded="false" title="Financial">@include('components.sidebar-icon', ['name' => 'finance'])<span class="phoenix-sidebar-group-label">Financial</span><svg class="phoenix-sidebar-chevron" viewBox="0 0 24 24" aria-hidden="true"><path d="m7 10 5 5 5-5"/></svg></button>
            <div class="phoenix-sidebar-items" id="financial"><div class="phoenix-sidebar-items-inner">
                <a class="phoenix-sidebar-link" href="{{ route('receipts.pending') }}" title="Pending Reviews">@include('components.sidebar-icon', ['name' => 'receipts'])<span class="phoenix-sidebar-link-label">Pending Reviews</span></a>
                <a class="phoenix-sidebar-link" href="{{ route('receipts.index') }}" title="All Receipts">@include('components.sidebar-icon', ['name' => 'receipts'])<span class="phoenix-sidebar-link-label">All Receipts</span></a>
                <a class="phoenix-sidebar-link" href="{{ route('payments.index') }}" title="Payments">@include('components.sidebar-icon', ['name' => 'payments'])<span class="phoenix-sidebar-link-label">Payments</span></a>
                <a class="phoenix-sidebar-link" href="{{ route('receipts.reports') }}" title="Receipt Analytics">@include('components.sidebar-icon', ['name' => 'reports'])<span class="phoenix-sidebar-link-label">Receipt Analytics</span></a>
                <a class="phoenix-sidebar-link" href="{{ route('payments.reports') }}" title="Payment Analytics">@include('components.sidebar-icon', ['name' => 'reports'])<span class="phoenix-sidebar-link-label">Payment Analytics</span></a>
            </div></div>
        </div>
        <div class="phoenix-sidebar-group">
            <button type="button" class="phoenix-sidebar-group-title" data-sidebar-group="account" aria-controls="account" aria-expanded="false" title="Account">@include('components.sidebar-icon', ['name' => 'account'])<span class="phoenix-sidebar-group-label">Account</span><svg class="phoenix-sidebar-chevron" viewBox="0 0 24 24" aria-hidden="true"><path d="m7 10 5 5 5-5"/></svg></button>
            <div class="phoenix-sidebar-items" id="account"><div class="phoenix-sidebar-items-inner"><a class="phoenix-sidebar-link" href="{{ route('profile.edit') }}" title="Profile Settings">@include('components.sidebar-icon', ['name' => 'profile'])<span class="phoenix-sidebar-link-label">Profile Settings</span></a></div></div>
        </div>
        <div class="phoenix-sidebar-quick"><p class="phoenix-sidebar-quick-title">Quick Actions</p>
            <a class="phoenix-sidebar-link is-quick" href="{{ route('receipts.pending') }}" title="Review Receipts">@include('components.sidebar-icon', ['name' => 'quick'])<span class="phoenix-sidebar-link-label">Review Receipts</span></a>
            <a class="phoenix-sidebar-link is-quick" href="{{ route('students.create') }}" title="Add Student">@include('components.sidebar-icon', ['name' => 'quick'])<span class="phoenix-sidebar-link-label">Add Student</span></a>
        </div>
    @elseif($sidebarUser->isStudent())
        <div class="phoenix-sidebar-group"><a class="phoenix-sidebar-link" href="{{ route('dashboard') }}" title="Dashboard">@include('components.sidebar-icon', ['name' => 'grid'])<span class="phoenix-sidebar-link-label">Home</span></a></div>
        <div class="phoenix-sidebar-group">
            <button type="button" class="phoenix-sidebar-group-title" data-sidebar-group="academic" aria-controls="academic" aria-expanded="false" title="Academic">@include('components.sidebar-icon', ['name' => 'academic'])<span class="phoenix-sidebar-group-label">Academic</span><svg class="phoenix-sidebar-chevron" viewBox="0 0 24 24" aria-hidden="true"><path d="m7 10 5 5 5-5"/></svg></button>
            <div class="phoenix-sidebar-items" id="academic"><div class="phoenix-sidebar-items-inner">
                <a class="phoenix-sidebar-link" href="{{ route('enrollments.index') }}" title="My Courses">@include('components.sidebar-icon', ['name' => 'courses'])<span class="phoenix-sidebar-link-label">My Courses</span></a>
                <a class="phoenix-sidebar-link" href="{{ route('grades.index') }}" title="My Grades">@include('components.sidebar-icon', ['name' => 'grades'])<span class="phoenix-sidebar-link-label">My Grades</span></a>
            </div></div>
        </div>
        <div class="phoenix-sidebar-group">
            <button type="button" class="phoenix-sidebar-group-title" data-sidebar-group="financial" aria-controls="financial" aria-expanded="false" title="Financial">@include('components.sidebar-icon', ['name' => 'finance'])<span class="phoenix-sidebar-group-label">Financial</span><svg class="phoenix-sidebar-chevron" viewBox="0 0 24 24" aria-hidden="true"><path d="m7 10 5 5 5-5"/></svg></button>
            <div class="phoenix-sidebar-items" id="financial"><div class="phoenix-sidebar-items-inner">
                <a class="phoenix-sidebar-link" href="{{ route('receipts.create') }}" title="Upload Receipt">@include('components.sidebar-icon', ['name' => 'receipts'])<span class="phoenix-sidebar-link-label">Upload Receipt</span></a>
                <a class="phoenix-sidebar-link" href="{{ route('receipts.student-history') }}" title="Receipt History">@include('components.sidebar-icon', ['name' => 'receipts'])<span class="phoenix-sidebar-link-label">Receipt History</span></a>
                <a class="phoenix-sidebar-link" href="{{ route('payments.my-history') }}" title="Payment History">@include('components.sidebar-icon', ['name' => 'payments'])<span class="phoenix-sidebar-link-label">Payment History</span></a>
            </div></div>
        </div>
        <div class="phoenix-sidebar-group"><button type="button" class="phoenix-sidebar-group-title" data-sidebar-group="account" aria-controls="account" aria-expanded="false" title="Account">@include('components.sidebar-icon', ['name' => 'account'])<span class="phoenix-sidebar-group-label">Account</span><svg class="phoenix-sidebar-chevron" viewBox="0 0 24 24" aria-hidden="true"><path d="m7 10 5 5 5-5"/></svg></button><div class="phoenix-sidebar-items" id="account"><div class="phoenix-sidebar-items-inner"><a class="phoenix-sidebar-link" href="{{ route('profile.edit') }}" title="Profile Settings">@include('components.sidebar-icon', ['name' => 'profile'])<span class="phoenix-sidebar-link-label">Profile Settings</span></a></div></div></div>
        <div class="phoenix-sidebar-quick"><p class="phoenix-sidebar-quick-title">Quick Actions</p><a class="phoenix-sidebar-link is-quick" href="{{ route('enrollments.available') }}" title="Browse Courses">@include('components.sidebar-icon', ['name' => 'quick'])<span class="phoenix-sidebar-link-label">Browse Courses</span></a><a class="phoenix-sidebar-link is-quick" href="{{ route('receipts.create') }}" title="Upload Receipt">@include('components.sidebar-icon', ['name' => 'quick'])<span class="phoenix-sidebar-link-label">Upload Receipt</span></a></div>
    @elseif($sidebarUser->isLecturer())
        <div class="phoenix-sidebar-group"><a class="phoenix-sidebar-link" href="{{ route('dashboard') }}" title="Dashboard">@include('components.sidebar-icon', ['name' => 'grid'])<span class="phoenix-sidebar-link-label">Overview</span></a></div>
        <div class="phoenix-sidebar-group">
            <button type="button" class="phoenix-sidebar-group-title" data-sidebar-group="teaching" aria-controls="teaching" aria-expanded="false" title="Teaching">@include('components.sidebar-icon', ['name' => 'academic'])<span class="phoenix-sidebar-group-label">Teaching</span><svg class="phoenix-sidebar-chevron" viewBox="0 0 24 24" aria-hidden="true"><path d="m7 10 5 5 5-5"/></svg></button>
            <div class="phoenix-sidebar-items" id="teaching"><div class="phoenix-sidebar-items-inner"><a class="phoenix-sidebar-link" href="{{ route('dashboard') }}" title="My Courses">@include('components.sidebar-icon', ['name' => 'courses'])<span class="phoenix-sidebar-link-label">My Courses</span></a><a class="phoenix-sidebar-link" href="{{ route('grades.index') }}" title="Grades">@include('components.sidebar-icon', ['name' => 'grades'])<span class="phoenix-sidebar-link-label">Grades</span></a><a class="phoenix-sidebar-link" href="{{ route('attendance.index') }}" title="Attendance">@include('components.sidebar-icon', ['name' => 'attendance'])<span class="phoenix-sidebar-link-label">Attendance</span></a></div></div>
        </div>
        <div class="phoenix-sidebar-group"><button type="button" class="phoenix-sidebar-group-title" data-sidebar-group="account" aria-controls="account" aria-expanded="false" title="Account">@include('components.sidebar-icon', ['name' => 'account'])<span class="phoenix-sidebar-group-label">Account</span><svg class="phoenix-sidebar-chevron" viewBox="0 0 24 24" aria-hidden="true"><path d="m7 10 5 5 5-5"/></svg></button><div class="phoenix-sidebar-items" id="account"><div class="phoenix-sidebar-items-inner"><a class="phoenix-sidebar-link" href="{{ route('profile.edit') }}" title="Profile Settings">@include('components.sidebar-icon', ['name' => 'profile'])<span class="phoenix-sidebar-link-label">Profile Settings</span></a></div></div></div>
        <div class="phoenix-sidebar-quick"><p class="phoenix-sidebar-quick-title">Quick Actions</p><a class="phoenix-sidebar-link is-quick" href="{{ route('grades.index') }}" title="Record Grades">@include('components.sidebar-icon', ['name' => 'quick'])<span class="phoenix-sidebar-link-label">Record Grades</span></a><a class="phoenix-sidebar-link is-quick" href="{{ route('attendance.index') }}" title="Take Attendance">@include('components.sidebar-icon', ['name' => 'quick'])<span class="phoenix-sidebar-link-label">Take Attendance</span></a></div>
    @else
        <div class="phoenix-sidebar-group"><a class="phoenix-sidebar-link" href="{{ route('dashboard') }}" title="Dashboard">@include('components.sidebar-icon', ['name' => 'grid'])<span class="phoenix-sidebar-link-label">Overview</span></a></div>
        <div class="phoenix-sidebar-group"><button type="button" class="phoenix-sidebar-group-title" data-sidebar-group="children" aria-controls="children" aria-expanded="false" title="Children">@include('components.sidebar-icon', ['name' => 'students'])<span class="phoenix-sidebar-group-label">Children</span><svg class="phoenix-sidebar-chevron" viewBox="0 0 24 24" aria-hidden="true"><path d="m7 10 5 5 5-5"/></svg></button><div class="phoenix-sidebar-items" id="children"><div class="phoenix-sidebar-items-inner"><a class="phoenix-sidebar-link" href="{{ route('dashboard') }}" title="My Children">@include('components.sidebar-icon', ['name' => 'students'])<span class="phoenix-sidebar-link-label">My Children</span></a></div></div></div>
        <div class="phoenix-sidebar-group"><button type="button" class="phoenix-sidebar-group-title" data-sidebar-group="account" aria-controls="account" aria-expanded="false" title="Account">@include('components.sidebar-icon', ['name' => 'account'])<span class="phoenix-sidebar-group-label">Account</span><svg class="phoenix-sidebar-chevron" viewBox="0 0 24 24" aria-hidden="true"><path d="m7 10 5 5 5-5"/></svg></button><div class="phoenix-sidebar-items" id="account"><div class="phoenix-sidebar-items-inner"><a class="phoenix-sidebar-link" href="{{ route('profile.edit') }}" title="Profile Settings">@include('components.sidebar-icon', ['name' => 'profile'])<span class="phoenix-sidebar-link-label">Profile Settings</span></a></div></div></div>
        <div class="phoenix-sidebar-quick"><p class="phoenix-sidebar-quick-title">Quick Actions</p><a class="phoenix-sidebar-link is-quick" href="{{ route('dashboard') }}" title="View Children">@include('components.sidebar-icon', ['name' => 'quick'])<span class="phoenix-sidebar-link-label">View Children</span></a></div>
    @endif
</nav>

<script>
    window.PhoenixSidebar = window.PhoenixSidebar || {};
    window.PhoenixSidebar.toggleCollapsed = function () {
        document.body.classList.toggle('sidebar-collapsed');
        const isCollapsed = document.body.classList.contains('sidebar-collapsed');
        const button = document.querySelector('[data-sidebar-collapse]');
        button?.setAttribute('aria-pressed', String(isCollapsed));
        button?.setAttribute('aria-label', isCollapsed ? 'Expand sidebar' : 'Collapse sidebar');
        button?.setAttribute('title', isCollapsed ? 'Expand sidebar' : 'Collapse sidebar');
        try { localStorage.setItem('phoenix-sidebar-collapsed-{{ $sidebarUser->role }}', isCollapsed ? '1' : '0'); } catch (error) {}
    };

    document.addEventListener('DOMContentLoaded', function () {
        const sidebar = document.querySelector('.phoenix-sidebar');
        if (!sidebar) return;

        const role = sidebar.dataset.sidebarRole;
        const collapseButton = sidebar.querySelector('[data-sidebar-collapse]');
        try {
            const isCollapsed = localStorage.getItem(`phoenix-sidebar-collapsed-${role}`) === '1';
            document.body.classList.toggle('sidebar-collapsed', isCollapsed);
            collapseButton?.setAttribute('aria-pressed', String(isCollapsed));
            collapseButton?.setAttribute('aria-label', isCollapsed ? 'Expand sidebar' : 'Collapse sidebar');
            collapseButton?.setAttribute('title', isCollapsed ? 'Expand sidebar' : 'Collapse sidebar');
        } catch (error) {}

        sidebar.querySelectorAll('[data-sidebar-group]').forEach((button) => {
            const group = document.getElementById(button.getAttribute('aria-controls'));
            const key = `phoenix-sidebar-group-${role}-${button.dataset.sidebarGroup}`;
            let savedState = null;
            try { savedState = localStorage.getItem(key); } catch (error) {}
            const isOpen = savedState === 'open';
            group?.classList.toggle('open', isOpen);
            button.setAttribute('aria-expanded', String(isOpen));
            button.addEventListener('click', function () {
                const nextState = !group.classList.contains('open');
                group.classList.toggle('open', nextState);
                button.setAttribute('aria-expanded', String(nextState));
                try { localStorage.setItem(key, nextState ? 'open' : 'closed'); } catch (error) {}
            });
        });

        const currentPath = window.location.pathname.replace(/\/+$/, '') || '/';
        let activeLink = null;
        let activeLength = -1;
        sidebar.querySelectorAll('a[href]').forEach((link) => {
            try {
                const linkPath = new URL(link.href, window.location.origin).pathname.replace(/\/+$/, '') || '/';
                if (linkPath === '/' ? currentPath === '/' : currentPath === linkPath || currentPath.startsWith(linkPath + '/')) {
                    if (linkPath.length > activeLength) {
                        activeLink = link;
                        activeLength = linkPath.length;
                    }
                }
            } catch (error) {}
        });

        if (activeLink) {
            activeLink.classList.add('is-active');
            activeLink.setAttribute('aria-current', 'page');
            const group = activeLink.closest('.phoenix-sidebar-items');
            if (group) {
                group.classList.add('open');
                const button = sidebar.querySelector(`[aria-controls="${group.id}"]`);
                button?.setAttribute('aria-expanded', 'true');
                try { localStorage.setItem(`phoenix-sidebar-group-${role}-${button?.dataset.sidebarGroup}`, 'open'); } catch (error) {}
            }
        }

        collapseButton?.addEventListener('click', window.PhoenixSidebar.toggleCollapsed);
        sidebar.querySelectorAll('a').forEach((link) => link.addEventListener('click', function (event) {
            if (link.hasAttribute('data-logout-link')) {
                event.preventDefault();
                document.getElementById('logout-form')?.requestSubmit();
                return;
            }
            document.body.classList.remove('sidebar-open');
            document.querySelector('.phoenix-nav-toggle')?.setAttribute('aria-expanded', 'false');
        }));
        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape') {
                document.body.classList.remove('sidebar-open');
                document.querySelector('.phoenix-nav-toggle')?.setAttribute('aria-expanded', 'false');
            }
        });
    });
</script>
