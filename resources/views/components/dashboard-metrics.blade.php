<section class="dashboard-metrics {{ count($metrics) === 6 ? 'dashboard-metrics--six' : '' }}" aria-label="Dashboard metrics">
    @foreach($metrics as $metric)
        <article class="dashboard-metric" data-tone="{{ $metric['tone'] ?? 'purple' }}">
            <div class="dashboard-metric-top">
                <h2 class="dashboard-metric-label">{{ $metric['label'] }}</h2>
                <span class="dashboard-metric-icon">@include('components.sidebar-icon', ['name' => $metric['icon']])</span>
            </div>
            <p class="dashboard-metric-value">{{ $metric['value'] }}</p>
            <p class="dashboard-metric-detail">{{ $metric['detail'] }}</p>
        </article>
    @endforeach
</section>
@include('components.dashboard-insights')