<?php

namespace Tests\Feature;

use App\Models\Program;
use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudentProgramSelectionTest extends TestCase
{
    use RefreshDatabase;

    public function test_student_creation_form_lists_active_programs_from_the_database(): void
    {
        Program::create([
            'name' => 'Renewable Energy Systems',
            'duration_years' => 4,
            'status' => 'active',
        ]);

        $this->actingAs($this->admin())
            ->get(route('students.create'))
            ->assertOk()
            ->assertSee('Renewable Energy Systems')
            ->assertDontSee('Computer Science');
    }

    public function test_student_edit_form_lists_active_programs_and_preserves_existing_inactive_program(): void
    {
        Program::create([
            'name' => 'Renewable Energy Systems',
            'duration_years' => 4,
            'status' => 'active',
        ]);
        Program::create([
            'name' => 'Legacy Programme',
            'duration_years' => 3,
            'status' => 'inactive',
        ]);
        $student = $this->student('Legacy Programme');

        $this->actingAs($this->admin())
            ->get(route('students.edit', $student))
            ->assertOk()
            ->assertSee('Renewable Energy Systems')
            ->assertSee('Legacy Programme')
            ->assertDontSee('Computer Science');
    }

    public function test_student_program_update_rejects_programs_not_in_the_database(): void
    {
        Program::create([
            'name' => 'Renewable Energy Systems',
            'duration_years' => 4,
            'status' => 'active',
        ]);
        $student = $this->student('Renewable Energy Systems');

        $this->actingAs($this->admin())
            ->from(route('students.edit', $student))
            ->put(route('students.update', $student), [
                'first_name' => $student->first_name,
                'last_name' => $student->last_name,
                'email' => $student->email,
                'date_of_birth' => $student->date_of_birth->format('Y-m-d'),
                'gender' => $student->gender,
                'program' => 'Computer Science',
                'status' => $student->status,
            ])
            ->assertSessionHasErrors('program');

        $this->assertSame('Renewable Energy Systems', $student->fresh()->program);
    }

    private function admin(): User
    {
        return User::factory()->create([
            'role' => 'admin',
            'must_change_password' => false,
        ]);
    }

    private function student(string $program): Student
    {
        return Student::create([
            'student_id' => fake()->unique()->numerify('STU#####'),
            'first_name' => 'Test',
            'last_name' => 'Student',
            'email' => fake()->unique()->safeEmail(),
            'date_of_birth' => '2005-01-01',
            'gender' => 'other',
            'enrollment_date' => '2025-01-01',
            'program' => $program,
            'year' => 1,
            'semester' => 1,
            'status' => 'active',
        ]);
    }
}
