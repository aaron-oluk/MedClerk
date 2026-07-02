<?php

namespace Database\Seeders;

use App\Models\Assessment;
use App\Models\ClinicalSign;
use App\Models\ClinicalSystem;
use App\Models\Cohort;
use App\Models\CohortEnrollment;
use App\Models\Department;
use App\Models\Feedback;
use App\Models\Institution;
use App\Models\LogbookEntry;
use App\Models\Program;
use App\Models\Rotation;
use App\Models\Skill;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $institution = Institution::create([
            'name' => 'Kabale University',
            'slug' => 'kabale-university',
            'country' => 'Uganda',
            'status' => 'active',
        ]);

        $department = Department::create([
            'institution_id' => $institution->id,
            'name' => 'Internal Medicine',
        ]);

        $program = Program::create([
            'institution_id' => $institution->id,
            'name' => 'Bachelor of Medicine and Bachelor of Surgery',
            'code' => 'MBCHB',
            'description' => 'Undergraduate medical program.',
        ]);

        $cohort = Cohort::create([
            'program_id' => $program->id,
            'name' => '2026 Intake',
            'start_date' => '2026-02-01',
        ]);

        $superadmin = User::create([
            'name' => 'Platform Superadmin',
            'email' => 'superadmin@medclerk.test',
            'password' => bcrypt('password'),
            'role' => User::ROLE_SUPERADMIN,
        ]);

        $admin = User::create([
            'name' => 'Kabale Admin',
            'email' => 'admin@medclerk.test',
            'password' => bcrypt('password'),
            'role' => User::ROLE_ADMIN,
            'institution_id' => $institution->id,
            'department_id' => $department->id,
        ]);

        $lecturer = User::create([
            'name' => 'Dr Grace Mugisha',
            'email' => 'lecturer@medclerk.test',
            'password' => bcrypt('password'),
            'role' => User::ROLE_LECTURER,
            'institution_id' => $institution->id,
            'department_id' => $department->id,
        ]);

        $student = User::create([
            'name' => 'Brian Tumusiime',
            'email' => 'student@medclerk.test',
            'password' => bcrypt('password'),
            'role' => User::ROLE_STUDENT,
            'institution_id' => $institution->id,
            'department_id' => $department->id,
            'student_number' => 'KAB2026001',
        ]);

        CohortEnrollment::create([
            'cohort_id' => $cohort->id,
            'user_id' => $student->id,
            'status' => 'active',
            'enrolled_at' => '2026-02-01',
        ]);

        $clinicalSystem = ClinicalSystem::create([
            'name' => 'Cardiovascular System',
            'description' => 'Examination and signs relevant to the cardiovascular system.',
        ]);

        $clinicalSign = ClinicalSign::create([
            'clinical_system_id' => $clinicalSystem->id,
            'name' => 'Raised jugular venous pressure',
            'description' => 'Visible distension of the internal jugular vein.',
            'interpretation' => 'Suggests right sided heart failure or fluid overload.',
            'diagnostic_relevance' => 'Congestive heart failure, tricuspid regurgitation, pericardial disease.',
        ]);

        $skill = Skill::create([
            'clinical_system_id' => $clinicalSystem->id,
            'name' => 'Cardiovascular examination',
            'description' => 'Structured approach to examining the cardiovascular system.',
            'procedure_steps' => [
                'Introduce yourself and gain consent',
                'Inspect for signs of breathlessness or cyanosis',
                'Palpate the pulse and assess the apex beat',
                'Auscultate the four cardiac areas',
            ],
            'competency_codes' => ['CBME.CVS.01'],
        ]);

        $tag = Tag::create(['name' => 'Cardiovascular', 'slug' => 'cardiovascular']);
        $clinicalSystem->tags()->attach($tag);
        $skill->tags()->attach($tag);

        $rotation = Rotation::create([
            'institution_id' => $institution->id,
            'department_id' => $department->id,
            'student_id' => $student->id,
            'supervisor_id' => $lecturer->id,
            'name' => 'Internal Medicine Clerkship',
            'start_date' => '2026-05-01',
            'end_date' => '2026-06-26',
            'status' => 'active',
        ]);

        LogbookEntry::create([
            'rotation_id' => $rotation->id,
            'student_id' => $student->id,
            'clinical_sign_id' => $clinicalSign->id,
            'skill_id' => $skill->id,
            'encounter_date' => '2026-05-10',
            'findings' => ['jvp' => 'raised', 'note' => 'Observed in a patient with fluid overload.'],
            'notes' => 'Supervised encounter during ward round.',
        ]);

        $assessment = Assessment::create([
            'student_id' => $student->id,
            'skill_id' => $skill->id,
            'rotation_id' => $rotation->id,
            'assessor_id' => $lecturer->id,
            'score' => 16,
            'max_score' => 20,
            'curriculum_version' => '2026.1',
            'assessed_at' => '2026-05-12',
        ]);

        Feedback::create([
            'student_id' => $student->id,
            'assessment_id' => $assessment->id,
            'given_by' => $lecturer->id,
            'strengths' => 'Thorough and systematic examination technique.',
            'areas_to_improve' => 'Work on explaining findings to the patient in plain language.',
            'follow_up_date' => '2026-06-01',
        ]);

        $this->command?->info('Seeded demo accounts, password for all is: password');
        $this->command?->table(
            ['role', 'email'],
            [
                [User::ROLE_SUPERADMIN, $superadmin->email],
                [User::ROLE_ADMIN, $admin->email],
                [User::ROLE_LECTURER, $lecturer->email],
                [User::ROLE_STUDENT, $student->email],
            ]
        );
    }
}
