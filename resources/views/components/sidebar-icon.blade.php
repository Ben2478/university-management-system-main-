@php($iconName = $name ?? 'grid')
<svg class="phoenix-sidebar-icon" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
    @switch($iconName)
        @case('grid')
            <rect x="3.5" y="3.5" width="7" height="7" rx="1.5"/><rect x="13.5" y="3.5" width="7" height="7" rx="1.5"/><rect x="3.5" y="13.5" width="7" height="7" rx="1.5"/><rect x="13.5" y="13.5" width="7" height="7" rx="1.5"/>
            @break
        @case('academic')
            <path d="m3 9 9-5 9 5-9 5-9-5Z"/><path d="M6 11v5c3.5 3 8.5 3 12 0v-5M21 9v7"/>
            @break
        @case('records')
            <path d="M8 4h8l3 3v13H5V4h3Z"/><path d="M14 4v4h5M8 12h8M8 16h6"/>
            @break
        @case('finance')
            <rect x="3" y="5" width="18" height="14" rx="2"/><path d="M3 9h18M7 15h4"/>
            @break
        @case('admin')
            <path d="M12 3 20 6v5c0 5-3.5 8.5-8 10-4.5-1.5-8-5-8-10V6l8-3Z"/><path d="m9 12 2 2 4-4"/>
            @break
        @case('account')
        @case('profile')
            <circle cx="12" cy="8" r="3.5"/><path d="M5 20c.7-3.2 3.2-5 7-5s6.3 1.8 7 5"/>
            @break
        @case('programs')
            <path d="m12 3 9 5-9 5-9-5 9-5Z"/><path d="m3 12 9 5 9-5M3 16l9 5 9-5"/>
            @break
        @case('calendar')
            <rect x="3.5" y="5" width="17" height="16" rx="2"/><path d="M7 3v4M17 3v4M4 10h16M8 14h2M14 14h2M8 17h2"/>
            @break
        @case('students')
            <path d="M16 20v-1.5a4 4 0 0 0-4-4H7a4 4 0 0 0-4 4V20"/><circle cx="9.5" cy="7.5" r="3.5"/><path d="M17 11a3.5 3.5 0 1 0-1-6.8M21 20v-1.5a4 4 0 0 0-3-3.9"/>
            @break
        @case('lecturers')
            <circle cx="12" cy="7" r="3.5"/><path d="M5 20a7 7 0 0 1 14 0M18 5l2 2-2 2"/>
            @break
        @case('courses')
            <path d="M4 5.5A2.5 2.5 0 0 1 6.5 3H20v17H6.5A2.5 2.5 0 0 0 4 22V5.5Z"/><path d="M4 17a2.5 2.5 0 0 1 2.5-2.5H20M8 7h7"/>
            @break
        @case('enrollments')
            <path d="M9 6h11M9 12h11M9 18h11"/><path d="m3.5 6 1.5 1.5L7.5 5M3.5 12l1.5 1.5L7.5 11M3.5 18l1.5 1.5L7.5 17"/>
            @break
        @case('grades')
            <path d="M4 20V11M10 20V5M16 20v-8M22 20H2"/>
            @break
        @case('attendance')
            <circle cx="12" cy="12" r="9"/><path d="m8 12 2.5 2.5L16.5 9"/>
            @break
        @case('payments')
            <rect x="3" y="5" width="18" height="14" rx="2"/><path d="M3 9h18M7 15h3M16 15h1"/>
            @break
        @case('receipts')
            <path d="M6 3h12v18l-3-2-3 2-3-2-3 2V3Z"/><path d="M9 8h6M9 12h6M9 16h3"/>
            @break
        @case('reports')
            <path d="M4 20V4M4 20h17"/><path d="m7 15 4-4 3 2 5-6"/>
            @break
        @case('settings')
            <circle cx="12" cy="12" r="3"/><path d="m19.4 15 .1.1 1.4 1.1-1.4 2.4-1.7-.7a8 8 0 0 1-1.4.8l-.3 1.8h-2.8l-.3-1.8a8 8 0 0 1-1.4-.8l-1.7.7-1.4-2.4 1.4-1.1a7 7 0 0 1 0-1.7l-1.4-1.1 1.4-2.4 1.7.7a8 8 0 0 1 1.4-.8l.3-1.8h2.8l.3 1.8a8 8 0 0 1 1.4.8l1.7-.7 1.4 2.4-1.4 1.1a7 7 0 0 1 0 1.7Z" transform="translate(-1 -1) scale(.96)"/>
            @break
        @case('logout')
            <path d="M10 4H5v16h5M14 8l4 4-4 4M8 12h10"/>
            @break
        @case('quick')
            <path d="M12 5v14M5 12h14"/>
            @break
        @default
            <circle cx="12" cy="12" r="8"/>
    @endswitch
</svg>
