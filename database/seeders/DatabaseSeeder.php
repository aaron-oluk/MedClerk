<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            InstitutionSeeder::class,
            UserSeeder::class,
            ProgramSeeder::class,
            ClinicalSystemSeeder::class,
            SkillSeeder::class,
            TagSeeder::class,
            RotationSeeder::class,
            AssessmentSeeder::class,
            FeedbackSeeder::class,
        ]);
    }
}
