<?php

namespace App\Models;

use App\Models\Concerns\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LogbookEntry extends Model
{
    use Auditable, HasFactory;

    public const TYPE_OBSERVED = 'observed';
    public const TYPE_ASSISTED = 'assisted';
    public const TYPE_PERFORMED = 'performed';

    protected $fillable = [
        'client_uuid',
        'rotation_id',
        'student_id',
        'clinical_sign_id',
        'skill_id',
        'encounter_date',
        'encounter_type',
        'findings',
        'notes',
        'signed_off_by',
        'signed_off_at',
    ];

    protected function casts(): array
    {
        return [
            'encounter_date' => 'date',
            'findings' => 'array',
            'signed_off_at' => 'datetime',
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

    public function signedOffBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'signed_off_by');
    }

    public function isSignedOff(): bool
    {
        return $this->signed_off_at !== null;
    }

    protected function auditInstitutionId(): ?int
    {
        return $this->rotation?->institution_id;
    }
}
