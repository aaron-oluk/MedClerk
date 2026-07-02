<?php

namespace Database\Seeders;

use App\Models\ClinicalSystem;
use App\Models\Skill;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SkillSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $clinicalSystem = ClinicalSystem::where('name', 'Cardiovascular System')->firstOrFail();

        Skill::firstOrCreate(
            ['clinical_system_id' => $clinicalSystem->id, 'name' => 'Cardiovascular examination'],
            [
                'description' => 'Structured approach to examining the cardiovascular system.',
                'procedure_steps' => [
                    'Introduce yourself and gain consent',
                    'Inspect for signs of breathlessness or cyanosis',
                    'Palpate the pulse and assess the apex beat',
                    'Auscultate the four cardiac areas',
                ],
                'competency_codes' => ['CBME.CVS.01'],
            ]
        );
    }
}
