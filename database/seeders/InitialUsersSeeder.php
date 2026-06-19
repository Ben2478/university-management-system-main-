<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class InitialUsersSeeder extends Seeder
{
    public function run(): void
    {
        $superAdminEmail = env('INITIAL_SUPERADMIN_EMAIL', 'superadmin@school.edu');
        $superAdminName = env('INITIAL_SUPERADMIN_NAME', 'Super Admin');
        $adminEmail = env('INITIAL_ADMIN_EMAIL', 'accountant@school.edu');
        $adminName = env('INITIAL_ADMIN_NAME', 'Accountant John');
        $extraAdminEmails = env('INITIAL_EXTRA_ADMIN_EMAILS', '');
        $disableLegacySeedUsers = filter_var(env('INITIAL_DISABLE_LEGACY_SEED_USERS', 'false'), FILTER_VALIDATE_BOOL);

        User::updateOrCreate(
            ['email' => $superAdminEmail],
            [
                'name' => $superAdminName,
                'password' => 'welcome123',
                'role' => 'super_admin',
                'phone' => '+260123456789',
                'address' => '123 Admin Street',
                'must_change_password' => true,
            ]
        );

        User::updateOrCreate(
            ['email' => $adminEmail],
            [
                'name' => $adminName,
                'password' => 'welcome123',
                'role' => 'admin',
                'phone' => '+260123456790',
                'address' => '456 Finance Avenue',
                'must_change_password' => true,
            ]
        );

        $emails = collect(preg_split('/\s*,\s*/', (string) $extraAdminEmails, -1, PREG_SPLIT_NO_EMPTY))
            ->map(fn ($email) => trim($email))
            ->filter()
            ->unique()
            ->values();

        foreach ($emails as $email) {
            if (Str::lower($email) === Str::lower($superAdminEmail) || Str::lower($email) === Str::lower($adminEmail)) {
                continue;
            }

            $localPart = Str::before($email, '@');
            $nameGuess = Str::of($localPart)
                ->replace(['.', '_', '-'], ' ')
                ->trim()
                ->title();

            User::updateOrCreate(
                ['email' => $email],
                [
                    'name' => (string) $nameGuess,
                    'password' => 'welcome123',
                    'role' => 'admin',
                    'must_change_password' => true,
                ]
            );
        }

        if ($disableLegacySeedUsers) {
            $legacyEmails = [
                'superadmin@school.edu',
                'accountant@school.edu',
            ];

            foreach ($legacyEmails as $legacyEmail) {
                if (Str::lower($legacyEmail) === Str::lower($superAdminEmail) || Str::lower($legacyEmail) === Str::lower($adminEmail)) {
                    continue;
                }

                User::where('email', $legacyEmail)
                    ->whereNull('deleted_at')
                    ->update(['deleted_at' => now()]);
            }
        }
    }
}
