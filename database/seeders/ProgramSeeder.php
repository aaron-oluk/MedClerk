<?php

namespace Database\Seeders;

use App\Models\Cohort;
use App\Models\CohortEnrollment;
use App\Models\Institution;
use App\Models\Program;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProgramSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $institution = Institution::where('slug', 'kabale-university')->firstOrFail();

        $program = Program::firstOrCreate(
            ['institution_id' => $institution->id, 'code' => 'MBCHB'],
            [
                'name' => 'Bachelor of Medicine and Bachelor of Surgery',
                'description' => 'Undergraduate medical program.',
            ]
        );

        $cohort = Cohort::firstOrCreate(
            ['program_id' => $program->id, 'name' => '2026 Intake'],
            ['start_date' => '2026-02-01']
        );

        $student = User::where('email', 'student@medclerk.test')->firstOrFail();

        CohortEnrollment::firstOrCreate(
            ['cohort_id' => $cohort->id, 'user_id' => $student->id],
            ['status' => 'active', 'enrolled_at' => '2026-02-01'],
        );
    }
}
