<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Institution;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $institution = Institution::where('slug', 'kabale-university')->firstOrFail();
        $department = Department::where('institution_id', $institution->id)
            ->where('name', 'Internal Medicine')
            ->firstOrFail();

        $accounts = [
            [
                'name' => 'Platform Superadmin',
                'email' => 'superadmin@medclerk.test',
                'role' => User::ROLE_SUPERADMIN,
                'institution_id' => null,
                'department_id' => null,
                'student_number' => null,
            ],
            [
                'name' => 'Kabale Admin',
                'email' => 'admin@medclerk.test',
                'role' => User::ROLE_ADMIN,
                'institution_id' => $institution->id,
                'department_id' => $department->id,
                'student_number' => null,
            ],
            [
                'name' => 'Dr Grace Mugisha',
                'email' => 'lecturer@medclerk.test',
                'role' => User::ROLE_LECTURER,
                'institution_id' => $institution->id,
                'department_id' => $department->id,
                'student_number' => null,
            ],
            [
                'name' => 'Brian Tumusiime',
                'email' => 'student@medclerk.test',
                'role' => User::ROLE_STUDENT,
                'institution_id' => $institution->id,
                'department_id' => $department->id,
                'student_number' => 'KAB2026001',
            ],
        ];

        foreach ($accounts as $account) {
            User::firstOrCreate(
                ['email' => $account['email']],
                [
                    'name' => $account['name'],
                    'password' => bcrypt('password'),
                    'role' => $account['role'],
                    'institution_id' => $account['institution_id'],
                    'department_id' => $account['department_id'],
                    'student_number' => $account['student_number'],
                ]
            );
        }

        $this->command?->info('Seeded demo accounts, password for all is: password');
        $this->command?->table(
            ['role', 'email'],
            collect($accounts)->map(fn ($account) => [$account['role'], $account['email']])->all()
        );
    }
}
