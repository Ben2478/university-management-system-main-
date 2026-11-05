<?php

namespace Tests\Feature;

use App\Models\Program;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProgramCourseTermTest extends TestCase
{
    use RefreshDatabase;

    public function test_bulk_course_creation_saves_each_selected_term(): void
    {
        $program = $this->program();
        $this->post(route('programs.store-courses', $program), [
            'courses' => [$this->course('CS101', 3), $this->course('CS102', 2)],
        ])->assertRedirect(route('programs.show', $program))->assertSessionHasNoErrors();

        $this->assertDatabaseHas('courses', ['course_code' => 'CS101', 'term' => 3, 'department' => $program->name]);
        $this->assertDatabaseHas('courses', ['course_code' => 'CS102', 'term' => 2]);
        $this->get(route('programs.show', $program))
            ->assertOk()->assertSeeInOrder(['CS102', 'CS101'])->assertSee('Term 3');
    }

    public function test_invalid_term_rejects_the_whole_batch(): void
    {
        $program = $this->program();
        $this->post(route('programs.store-courses', $program), [
            'courses' => [$this->course('CS101', 1), $this->course('CS102', 4)],
        ])->assertSessionHasErrors('courses.1.term');

        $this->assertDatabaseCount('courses', 0);
    }

    public function test_bulk_form_uses_terms_not_semesters(): void
    {
        $program = $this->program();
        $this->get(route('programs.add-courses', $program))
            ->assertOk()->assertSee('name="courses[0][term]"', false)
            ->assertSee('Term 3')->assertDontSee('name="courses[0][semester]"', false);
    }

    private function program(): Program
    {
        $this->actingAs(User::factory()->create(['role' => 'super_admin', 'must_change_password' => false]));

        return Program::create(['name' => 'Computing', 'duration_years' => 4, 'status' => 'active']);
    }

    private function course(string $code, int $term): array
    {
        return ['course_code' => $code, 'course_name' => 'Computing Course', 'credits' => 3, 'term' => $term];
    }
}