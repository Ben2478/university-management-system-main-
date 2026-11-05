@php($insights = app(\App\Services\DashboardInsights::class)->forUser(auth()->user()))
<div class="dashboard-insights">
    <section class="dashboard-insight" aria-labelledby="dashboard-actions-title">
        <h2 id="dashboard-actions-title">Action Required</h2>
        @forelse($insights['actions'] as $action)
            <a class="dashboard-action-row" href="{{ $action['url'] }}">
                @include('components.sidebar-icon', ['name' => $action['icon']])
                <span>{{ $action['label'] }}</span>
                @if($action['count'] !== null)
                    <strong>{{ $action['count'] }}</strong>
                @endif
            </a>
        @empty
            <p class="dashboard-insight-empty">No outstanding actions for your account.</p>
        @endforelse
    </section>
    <section class="dashboard-insight" aria-labelledby="dashboard-period-title">
        <h2 id="dashboard-period-title">Current Academic Period</h2>
        @if($insights['calendar'])
            @php($activeCalendar = $insights['calendar'])
            <p class="dashboard-period-name">{{ $activeCalendar->academic_year }} <span>Semester {{ $activeCalendar->semester }}</span></p>
            @if($insights['term'])
                <p>Term {{ $insights['term'] }} ends {{ $activeCalendar->{'term_' . $insights['term'] . '_end_date'}->format('d M Y') }}</p>
            @else
                <p>Between scheduled terms</p>
            @endif
            <p>Semester ends {{ $activeCalendar->semester_end_date->format('d M Y') }}</p>
        @elseif($insights['nextCalendar'])
            <p class="dashboard-period-name">No semester in progress</p>
            <p>{{ $insights['nextCalendar']->academic_year }} / Semester {{ $insights['nextCalendar']->semester }} starts {{ $insights['nextCalendar']->semester_start_date->format('d M Y') }}.</p>
        @elseif($insights['hasCalendar'])
            <p class="dashboard-insight-empty">No academic period is scheduled for today.</p>
        @else
            <p class="dashboard-insight-empty">Calendar not configured.</p>
        @endif
        @if(auth()->user()->isSuperAdmin() || auth()->user()->isAdmin())
            <a class="dashboard-calendar-link" href="{{ route('academic-calendar.index') }}">Manage academic calendar</a>
        @endif
    </section>
</div>