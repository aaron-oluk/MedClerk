<?php

namespace Database\Seeders;

use App\Models\Assessment;
use App\Models\Rotation;
use App\Models\Skill;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AssessmentSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $student = User::where('email', 'student@medclerk.test')->firstOrFail();
        $lecturer = User::where('email', 'lecturer@medclerk.test')->firstOrFail();
        $skill = Skill::where('name', 'Cardiovascular examination')->firstOrFail();
        $rotation = Rotation::where('student_id', $student->id)
            ->where('name', 'Internal Medicine Clerkship')
            ->firstOrFail();

        Assessment::firstOrCreate(
            [
                'student_id' => $student->id,
                'skill_id' => $skill->id,
                'rotation_id' => $rotation->id,
                'assessor_id' => $lecturer->id,
            ],
            [
                'score' => 16,
                'max_score' => 20,
                'curriculum_version' => '2026.1',
                'assessed_at' => '2026-05-12',
            ]
        );
    }
}
