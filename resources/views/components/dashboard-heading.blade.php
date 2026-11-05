@once
    <link rel="stylesheet" href="{{ asset('css/dashboards.css') }}?v={{ filemtime(public_path('css/dashboards.css')) }}">
@endonce
<header class="dashboard-heading">
    <div class="dashboard-heading-copy">
        <p class="dashboard-eyebrow">{{ $area }}</p>
        <h1>{{ $title }}</h1>
        <p class="dashboard-description">{{ $description }}</p>
    </div>
    <time class="dashboard-date" datetime="{{ now()->toDateString() }}">
        @include('components.sidebar-icon', ['name' => 'calendar'])
        {{ now()->format('l, d F Y') }}
    </time>
</header>