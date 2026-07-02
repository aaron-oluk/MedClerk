<?php

namespace App\Models;

use App\Models\Concerns\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Feedback extends Model
{
    use Auditable, HasFactory;

    protected $fillable = [
        'student_id',
        'assessment_id',
        'given_by',
        'strengths',
        'areas_to_improve',
        'follow_up_date',
    ];

    protected function casts(): array
    {
        return [
            'follow_up_date' => 'date',
        ];
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function assessment(): BelongsTo
    {
        return $this->belongsTo(Assessment::class);
    }

    public function givenBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'given_by');
    }

    protected function auditInstitutionId(): ?int
    {
        return $this->student?->institution_id;
    }
}
