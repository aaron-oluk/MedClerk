<?php

namespace App\Models;

use App\Models\Concerns\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LogbookEntry extends Model
{
    use Auditable, HasFactory;

    protected $fillable = [
        'rotation_id',
        'student_id',
        'clinical_sign_id',
        'skill_id',
        'encounter_date',
        'findings',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'encounter_date' => 'date',
            'findings' => 'array',
        ];
    }

    public function rotation(): BelongsTo
    {
        return $this->belongsTo(Rotation::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function clinicalSign(): BelongsTo
    {
        return $this->belongsTo(ClinicalSign::class);
    }

    public function skill(): BelongsTo
    {
        return $this->belongsTo(Skill::class);
    }

    protected function auditInstitutionId(): ?int
    {
        return $this->rotation?->institution_id;
    }
}
