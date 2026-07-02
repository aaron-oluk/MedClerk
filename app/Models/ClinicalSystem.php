<?php

namespace App\Models;

use App\Models\Concerns\Taggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ClinicalSystem extends Model
{
    use HasFactory, Taggable;

    protected $fillable = [
        'name',
        'description',
    ];

    public function clinicalSigns(): HasMany
    {
        return $this->hasMany(ClinicalSign::class);
    }

    public function skills(): HasMany
    {
        return $this->hasMany(Skill::class);
    }
}
