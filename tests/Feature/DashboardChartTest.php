<?php

namespace Tests\Feature;

use Tests\TestCase;

class DashboardChartTest extends TestCase
{
    public function test_empty_chart_shows_message_without_canvas(): void
    {
        $this->view('components.dashboard-chart', [
            'chartId' => 'reviewChart',
            'label' => 'Revenue',
            'hasData' => false,
            'emptyMessage' => 'No completed payments yet.',
        ])->assertSee('No completed payments yet.')
            ->assertDontSee('<canvas', false);
    }

    public function test_populated_chart_has_label_and_library_failure_fallback(): void
    {
        $this->view('components.dashboard-chart', [
            'chartId' => 'reviewChart',
            'label' => 'Revenue',
            'hasData' => true,
            'emptyMessage' => 'No completed payments yet.',
        ])->assertSee('<canvas id="reviewChart"', false)
            ->assertSee('aria-label="Revenue"', false)
            ->assertSee('Chart unavailable.')
            ->assertDontSee('No completed payments yet.');
    }
}