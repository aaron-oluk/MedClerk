<?php

namespace App\Models;

use App\Models\Concerns\Taggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ClinicalSign extends Model
{
    use HasFactory, Taggable;

    protected $fillable = [
        'clinical_system_id',
        'name',
        'eponym',
        'description',
        'interpretation',
        'technique',
        'diagnostic_relevance',
        'red_flags',
        'difficulty',
        'last_reviewed',
        'media_urls',
        'media_type',
        'media_duration',
    ];

    protected function casts(): array
    {
        return [
            'media_urls' => 'array',
            'red_flags' => 'array',
            'last_reviewed' => 'date',
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
}
