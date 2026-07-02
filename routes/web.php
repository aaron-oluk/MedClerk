<?php

use App\Http\Controllers\AssessmentController;
use App\Http\Controllers\ClinicalSystemController;
use App\Http\Controllers\CohortController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\InstitutionController;
use App\Http\Controllers\LogbookEntryController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProgramController;
use App\Http\Controllers\RotationController;
use App\Http\Controllers\SkillController;
use App\Models\User;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/rotations', [RotationController::class, 'index'])->name('rotations.index');
    Route::post('/rotations', [RotationController::class, 'store'])->name('rotations.store');

    Route::get('/logbook-entries', [LogbookEntryController::class, 'index'])->name('logbook-entries.index');
    Route::post('/logbook-entries', [LogbookEntryController::class, 'store'])->name('logbook-entries.store');

    Route::get('/clinical-systems', [ClinicalSystemController::class, 'index'])->name('clinical-systems.index');
    Route::post('/clinical-systems', [ClinicalSystemController::class, 'store'])->name('clinical-systems.store');
    Route::get('/clinical-systems/{clinicalSystem}', [ClinicalSystemController::class, 'show'])->name('clinical-systems.show');
    Route::post('/clinical-systems/{clinicalSystem}/signs', [ClinicalSystemController::class, 'storeSign'])->name('clinical-systems.signs.store');

    Route::get('/skills', [SkillController::class, 'index'])->name('skills.index');
    Route::post('/skills', [SkillController::class, 'store'])->name('skills.store');

    Route::get('/assessments', [AssessmentController::class, 'index'])->name('assessments.index');
    Route::post('/assessments', [AssessmentController::class, 'store'])->name('assessments.store');

    Route::get('/feedback', [FeedbackController::class, 'index'])->name('feedback.index');
    Route::post('/feedback', [FeedbackController::class, 'store'])->name('feedback.store');
});

Route::middleware(['auth', 'verified', 'role:' . User::ROLE_ADMIN . ',' . User::ROLE_SUPERADMIN])->group(function () {
    Route::get('/institutions', [InstitutionController::class, 'index'])->name('institutions.index');
    Route::post('/institutions', [InstitutionController::class, 'store'])->name('institutions.store');

    Route::get('/programs', [ProgramController::class, 'index'])->name('programs.index');
    Route::post('/programs', [ProgramController::class, 'store'])->name('programs.store');
    Route::get('/programs/{program}', [ProgramController::class, 'show'])->name('programs.show');
    Route::post('/programs/{program}/cohorts', [CohortController::class, 'store'])->name('programs.cohorts.store');

    Route::post('/cohorts/{cohort}/enrollments', [CohortController::class, 'storeEnrollment'])->name('cohorts.enrollments.store');
});

require __DIR__.'/auth.php';
