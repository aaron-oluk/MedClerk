<?php

namespace App\Models;

use App\Models\Concerns\Taggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Skill extends Model
{
    use HasFactory, Taggable;

    protected $fillable = [
        'clinical_system_id',
        'name',
        'description',
        'procedure_steps',
        'competency_codes',
    ];

    protected function casts(): array
    {
        return [
            'procedure_steps' => 'array',
            'competency_codes' => 'array',
        ];
    }

    public function clinicalSystem(): BelongsTo
    {
        return $this->belongsTo(ClinicalSystem::class);
    }

    public function logbookEntries(): HasMany
    {
        return $this->hasMany(LogbookEntry::class);
    }

    public function assessments(): HasMany
    {
        return $this->hasMany(Assessment::class);
    }
}
