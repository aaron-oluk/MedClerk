<?php

namespace Database\Seeders;

use App\Models\Assessment;
use App\Models\Feedback;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FeedbackSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $student = User::where('email', 'student@medclerk.test')->firstOrFail();
        $lecturer = User::where('email', 'lecturer@medclerk.test')->firstOrFail();
        $assessment = Assessment::where('student_id', $student->id)
            ->where('assessor_id', $lecturer->id)
            ->firstOrFail();

        Feedback::firstOrCreate(
            [
                'student_id' => $student->id,
                'assessment_id' => $assessment->id,
                'given_by' => $lecturer->id,
            ],
            [
                'strengths' => 'Thorough and systematic examination technique.',
                'areas_to_improve' => 'Work on explaining findings to the patient in plain language.',
                'follow_up_date' => '2026-06-01',
            ]
        );
    }
}
