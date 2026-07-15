<?php

use App\Http\Controllers\Api\AssessmentController;
use App\Http\Controllers\Api\AuthTokenController;
use App\Http\Controllers\Api\ClinicalSignController;
use App\Http\Controllers\Api\ClinicalSystemController;
use App\Http\Controllers\Api\CohortController;
use App\Http\Controllers\Api\FeedbackController;
use App\Http\Controllers\Api\InstitutionController;
use App\Http\Controllers\Api\LogbookEntryController;
use App\Http\Controllers\Api\ProgramController;
use App\Http\Controllers\Api\RotationController;
use App\Http\Controllers\Api\SettingsController;
use App\Http\Controllers\Api\SkillController;
use App\Http\Controllers\Api\StudentLookupController;
use App\Models\Rotation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/auth/login', [AuthTokenController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        $user = $request->user()->load('institution', 'department');
        $activeRotation = Rotation::where('student_id', $user->id)
            ->where('status', 'active')
            ->latest('start_date')
            ->first();

        return array_merge($user->toArray(), [
            'programme' => $user->currentCohortEnrollment()?->cohort?->program?->name,
            'current_placement' => $activeRotation?->name,
        ]);
    });

    Route::post('/auth/logout', [AuthTokenController::class, 'logout']);

    Route::patch('/settings', [SettingsController::class, 'update']);

    Route::middleware('role:' . User::ROLE_LECTURER)->group(function () {
        Route::get('/students/search', [StudentLookupController::class, 'search']);
        Route::get('/students/{student}', [StudentLookupController::class, 'show']);
    });

    Route::name('api.')->group(function () {
        Route::apiResource('institutions', InstitutionController::class);
        Route::apiResource('programs', ProgramController::class);
        Route::apiResource('cohorts', CohortController::class);
        Route::apiResource('clinical-systems', ClinicalSystemController::class);
        Route::apiResource('clinical-signs', ClinicalSignController::class);
        Route::apiResource('skills', SkillController::class);
        Route::apiResource('rotations', RotationController::class);
        Route::apiResource('logbook-entries', LogbookEntryController::class);
        Route::apiResource('assessments', AssessmentController::class);
        Route::apiResource('feedback', FeedbackController::class);
    });
});
