<?php

namespace Database\Seeders;

use App\Models\ClinicalSign;
use App\Models\ClinicalSystem;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ClinicalSystemSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $clinicalSystem = ClinicalSystem::firstOrCreate(
            ['name' => 'Cardiovascular System'],
            ['description' => 'Examination and signs relevant to the cardiovascular system.'],
        );

        ClinicalSign::firstOrCreate(
            ['clinical_system_id' => $clinicalSystem->id, 'name' => 'Raised jugular venous pressure'],
            [
                'description' => 'Visible distension of the internal jugular vein.',
                'interpretation' => 'Suggests right sided heart failure or fluid overload.',
                'diagnostic_relevance' => 'Congestive heart failure, tricuspid regurgitation, pericardial disease.',
            ]
        );
    }
}
