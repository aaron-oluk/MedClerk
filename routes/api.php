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
use App\Http\Controllers\Api\SkillController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/auth/login', [AuthTokenController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user()->load('institution', 'department');
    });

    Route::post('/auth/logout', [AuthTokenController::class, 'logout']);

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
