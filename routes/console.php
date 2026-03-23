<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Lecturer;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('make:test-lecturer {email? : Email for the lecturer account} {--password= : Optional plaintext password} {--force : Update existing user to lecturer if needed}', function () {
    $email = $this->argument('email') ?: 'lecturer@test.com';
    $plainPassword = $this->option('password') ?: Str::random(10);
    $force = (bool) $this->option('force');

    $existingUser = User::where('email', $email)->first();
    if ($existingUser && $existingUser->role !== 'lecturer' && !$force) {
        $this->error("A user with {$email} already exists with role '{$existingUser->role}'. Re-run with --force to convert to lecturer.");
        return self::FAILURE;
    }

    $user = $existingUser ?: User::create([
        'name' => 'Test Lecturer',
        'email' => $email,
        'password' => Hash::make($plainPassword),
        'role' => 'lecturer',
        'phone' => null,
    ]);

    if ($existingUser) {
        $user->update([
            'role' => 'lecturer',
            // If caller passed a password, reset it.
            'password' => $this->option('password') ? Hash::make($plainPassword) : $user->password,
        ]);
    }

    $lecturer = Lecturer::where('user_id', $user->id)->first();
    if (!$lecturer) {
        $year = date('Y');
        $sequence = str_pad((string) (Lecturer::count() + 1), 4, '0', STR_PAD_LEFT);
        $lecturerCode = 'LEC' . $year . $sequence;
        $tries = 0;
        while (Lecturer::where('lecturer_code', $lecturerCode)->exists() && $tries < 50) {
            $tries++;
            $sequence = str_pad((string) (Lecturer::count() + 1 + $tries), 4, '0', STR_PAD_LEFT);
            $lecturerCode = 'LEC' . $year . $sequence;
        }

        $lecturer = Lecturer::create([
            'user_id' => $user->id,
            'lecturer_code' => $lecturerCode,
            'first_name' => 'Test',
            'last_name' => 'Lecturer',
            'email' => $email,
            'phone' => null,
            'school_code' => null,
            'status' => 'active',
        ]);
    }

    $this->info('Lecturer test user ready:');
    $this->line('  Email: ' . $email);
    $this->line('  Password: ' . $plainPassword . ($this->option('password') ? '' : '  (generated)'));
    $this->line('  Lecturer Code: ' . $lecturer->lecturer_code);
    return self::SUCCESS;
})->purpose('Create a lecturer test user (User + Lecturer)');
