<?php

namespace Tests\Feature;

use App\Mail\StudentLoginDetails;
use App\Models\Program;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class StudentCredentialsEmailTest extends TestCase
{
    use RefreshDatabase;

    public function test_student_login_details_are_emailed_after_student_creation(): void
    {
        Mail::fake();
        Program::create([
            'name' => 'Computer Science',
            'duration_years' => 4,
            'status' => 'active',
        ]);
        $this->actingAs(User::factory()->create([
            'role' => 'admin',
            'must_change_password' => false,
        ]));

        $this->post(route('students.store'), [
            'first_name' => 'Taylor',
            'last_name' => 'Student',
            'email' => 'taylor.student@example.com',
            'school_code' => '01',
            'nrc' => '123456/78/9',
            'date_of_birth' => '2005-04-15',
            'gender' => 'other',
            'enrollment_date' => '2025-01-15',
            'program' => 'Computer Science',
            'student_type' => 'regular',
            'year' => 1,
            'semester' => 1,
            'tuition_fee' => 5000,
            'status' => 'active',
        ])->assertRedirect();

        Mail::assertSent(StudentLoginDetails::class, function (StudentLoginDetails $mail) {
            return $mail->hasTo('taylor.student@example.com')
                && $mail->student->student_id === '202501456'
                && $mail->temporaryPassword === 'welcome123';
        });
    }
}