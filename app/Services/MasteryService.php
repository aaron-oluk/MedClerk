<?php

namespace App\Services;

use App\Models\Assessment;

class MasteryService
{
    /**
     * Average assessment score (as a percentage of max_score) for the given student,
     * grouped by the clinical system of the assessed skill. Systems the student has no
     * assessments in are simply absent from the result.
     *
     * @return array<int, float> clinical_system_id => mastery percentage (0-100)
     */
    public function bySystemFor(int $studentId): array
    {
        return Assessment::query()
            ->join('skills', 'assessments.skill_id', '=', 'skills.id')
            ->where('assessments.student_id', $studentId)
            ->whereNotNull('skills.clinical_system_id')
            ->selectRaw('skills.clinical_system_id as clinical_system_id, AVG(assessments.score / assessments.max_score * 100) as mastery')
            ->groupBy('skills.clinical_system_id')
            ->get()
            ->mapWithKeys(fn ($row) => [(int) $row->clinical_system_id => round((float) $row->mastery, 1)])
            ->all();
    }

    /**
     * Average assessment score (as a percentage of max_score) for the given student and skill.
     */
    public function forSkill(int $studentId, int $skillId): ?float
    {
        $mastery = Assessment::query()
            ->where('student_id', $studentId)
            ->where('skill_id', $skillId)
            ->selectRaw('AVG(score / max_score * 100) as mastery')
            ->value('mastery');

        return $mastery !== null ? round((float) $mastery, 1) : null;
    }
}
