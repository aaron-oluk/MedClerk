<?php

namespace Database\Seeders;

use App\Models\ClinicalSign;
use App\Models\ClinicalSystem;
use App\Models\Skill;
use App\Models\Tag;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $cardiovascular = Tag::firstOrCreate(['slug' => 'cardiovascular'], ['name' => 'Cardiovascular']);
        $bedside = Tag::firstOrCreate(['slug' => 'bedside'], ['name' => 'Bedside']);
        $examination = Tag::firstOrCreate(['slug' => 'examination'], ['name' => 'Examination']);
        $auscultation = Tag::firstOrCreate(['slug' => 'auscultation'], ['name' => 'Auscultation']);
        $neurological = Tag::firstOrCreate(['slug' => 'neurological'], ['name' => 'Neurological']);
        $abdominal = Tag::firstOrCreate(['slug' => 'abdominal'], ['name' => 'Abdominal']);
        $emergency = Tag::firstOrCreate(['slug' => 'emergency'], ['name' => 'Emergency']);

        $cardiovascularSystem = ClinicalSystem::where('name', 'Cardiovascular System')->firstOrFail();
        $cardiacExam = Skill::where('name', 'Cardiovascular examination')->firstOrFail();

        $cardiovascularSystem->tags()->syncWithoutDetaching([$cardiovascular->id]);
        $cardiacExam->tags()->syncWithoutDetaching([$cardiovascular->id, $bedside->id]);

        $this->tagSign('Raised jugular venous pressure', [$bedside->id, $cardiovascular->id, $examination->id]);
        $this->tagSign('Hepatojugular reflux', [$bedside->id, $cardiovascular->id]);
        $this->tagSign('Pleural rub', [$auscultation->id]);
        $this->tagSign('Murphy sign', [$bedside->id, $abdominal->id, $examination->id]);
        $this->tagSign('Romberg sign', [$neurological->id, $examination->id, $bedside->id]);
        $this->tagSign('Kernig sign', [$neurological->id, $emergency->id]);
        $this->tagSign('Bone point tenderness', [$examination->id]);
    }

    private function tagSign(string $name, array $tagIds): void
    {
        ClinicalSign::where('name', $name)->firstOrFail()->tags()->syncWithoutDetaching($tagIds);
    }
}
