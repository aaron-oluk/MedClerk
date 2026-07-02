<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Institution;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InstitutionSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $institution = Institution::firstOrCreate(
            ['slug' => 'kabale-university'],
            [
                'name' => 'Kabale University',
                'country' => 'Uganda',
                'status' => 'active',
            ]
        );

        Department::firstOrCreate(
            ['institution_id' => $institution->id, 'name' => 'Internal Medicine'],
        );
    }
}
