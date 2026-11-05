@if($hasData)
    <canvas id="{{ $chartId }}" role="img" aria-label="{{ $label }}"></canvas>
    <p id="{{ $chartId }}-fallback" class="dashboard-chart-empty" hidden>Chart unavailable. Reload the page to try again.</p>
@else
    <p class="dashboard-chart-empty" role="status">{{ $emptyMessage }}</p>
@endif
@once
    <style>
        .dashboard-workspace .dashboard-chart-empty { display:grid; place-items:center; min-height:200px; padding:24px; text-align:center; color:#5f5670; background:#f7f5fc; border:1px dashed #d8cde7; border-radius:8px; font-size:14px; line-height:1.6; }
        .dashboard-workspace .dashboard-chart-empty[hidden] { display:none; }
    </style>
    <script>
        function renderDashboardChart(chartId, configuration) {
            const canvas = document.getElementById(chartId);
            if (!canvas) return;
            if (typeof Chart === 'undefined') {
                canvas.hidden = true;
                canvas.style.setProperty('display', 'none', 'important');
                document.getElementById(chartId + '-fallback').hidden = false;
                return;
            }
            return new Chart(canvas, configuration);
        }
    </script>
@endonce