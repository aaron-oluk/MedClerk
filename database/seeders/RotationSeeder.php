<?php

namespace Database\Seeders;

use App\Models\ClinicalSign;
use App\Models\Department;
use App\Models\Institution;
use App\Models\LogbookEntry;
use App\Models\Rotation;
use App\Models\Skill;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RotationSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $institution = Institution::where('slug', 'kabale-university')->firstOrFail();
        $department = Department::where('institution_id', $institution->id)
            ->where('name', 'Internal Medicine')
            ->firstOrFail();
        $student = User::where('email', 'student@medclerk.test')->firstOrFail();
        $lecturer = User::where('email', 'lecturer@medclerk.test')->firstOrFail();

        $rotation = Rotation::updateOrCreate(
            [
                'institution_id' => $institution->id,
                'department_id' => $department->id,
                'student_id' => $student->id,
                'name' => 'Internal Medicine Clerkship',
            ],
            [
                'supervisor_id' => $lecturer->id,
                'start_date' => '2026-05-01',
                'end_date' => '2026-06-26',
                'status' => 'active',
                'required_encounters' => 40,
            ]
        );

        $clinicalSign = ClinicalSign::where('name', 'Raised jugular venous pressure')->first();
        $skill = Skill::where('name', 'Cardiovascular examination')->first();

        LogbookEntry::firstOrCreate(
            [
                'rotation_id' => $rotation->id,
                'student_id' => $student->id,
                'encounter_date' => '2026-05-10',
            ],
            [
                'clinical_sign_id' => $clinicalSign?->id,
                'skill_id' => $skill?->id,
                'findings' => ['jvp' => 'raised', 'note' => 'Observed in a patient with fluid overload.'],
                'notes' => 'Supervised encounter during ward round.',
            ]
        );
    }
}
