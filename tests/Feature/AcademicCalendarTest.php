<?php

namespace Tests\Feature;

use App\Models\AcademicCalendar;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AcademicCalendarTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_semester_and_nested_term_timeframes(): void
    {
        $this->actingAs($this->admin())
            ->post(route('academic-calendar.store'), $this->calendarData())
            ->assertRedirect(route('academic-calendar.index'));

        $this->assertDatabaseHas('academic_calendars', [
            'academic_year' => '2026/2027',
            'semester' => 1,
        ]);
        $this->assertSame('2026-12-20', AcademicCalendar::first()->term_3_end_date->format('Y-m-d'));
    }

    public function test_term_dates_must_be_ordered_and_within_semester_dates(): void
    {
        $data = $this->calendarData();
        $data['term_2_start_date'] = '2026-04-01';

        $this->actingAs($this->admin())
            ->from(route('academic-calendar.create'))
            ->post(route('academic-calendar.store'), $data)
            ->assertSessionHasErrors('term_2_start_date');

        $this->assertDatabaseCount('academic_calendars', 0);
    }

    public function test_semester_periods_for_the_same_year_cannot_overlap(): void
    {
        AcademicCalendar::create($this->calendarData());
        $secondSemester = $this->calendarData([
            'semester' => 2,
            'semester_start_date' => '2026-06-01',
            'semester_end_date' => '2026-12-31',
            'term_1_start_date' => '2026-06-01',
            'term_1_end_date' => '2026-07-01',
            'term_2_start_date' => '2026-07-02',
            'term_2_end_date' => '2026-08-01',
            'term_3_start_date' => '2026-08-02',
            'term_3_end_date' => '2026-12-31',
        ]);

        $this->actingAs($this->admin())
            ->from(route('academic-calendar.create'))
            ->post(route('academic-calendar.store'), $secondSemester)
            ->assertSessionHasErrors('semester_start_date');

        $this->assertDatabaseCount('academic_calendars', 1);
    }

    private function admin(): User
    {
        return User::factory()->create([
            'role' => 'admin',
            'must_change_password' => false,
        ]);
    }

    private function calendarData(array $overrides = []): array
    {
        return array_merge([
            'academic_year' => '2026/2027',
            'semester' => 1,
            'semester_start_date' => '2026-01-01',
            'semester_end_date' => '2026-12-31',
            'term_1_start_date' => '2026-01-01',
            'term_1_end_date' => '2026-04-30',
            'term_2_start_date' => '2026-05-01',
            'term_2_end_date' => '2026-08-31',
            'term_3_start_date' => '2026-09-01',
            'term_3_end_date' => '2026-12-20',
        ], $overrides);
    }
}