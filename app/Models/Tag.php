<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Tag extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
    ];

    public function clinicalSystems(): MorphToMany
    {
        return $this->morphedByMany(ClinicalSystem::class, 'taggable', 'resource_tags');
    }

    public function clinicalSigns(): MorphToMany
    {
        return $this->morphedByMany(ClinicalSign::class, 'taggable', 'resource_tags');
    }

    public function skills(): MorphToMany
    {
        return $this->morphedByMany(Skill::class, 'taggable', 'resource_tags');
    }
}
