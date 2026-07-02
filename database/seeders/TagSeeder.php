<?php

namespace Database\Seeders;

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
        $tag = Tag::firstOrCreate(['slug' => 'cardiovascular'], ['name' => 'Cardiovascular']);

        $clinicalSystem = ClinicalSystem::where('name', 'Cardiovascular System')->firstOrFail();
        $skill = Skill::where('name', 'Cardiovascular examination')->firstOrFail();

        $clinicalSystem->tags()->syncWithoutDetaching($tag);
        $skill->tags()->syncWithoutDetaching($tag);
    }
}
