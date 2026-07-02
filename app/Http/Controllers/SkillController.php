<?php

namespace App\Http\Controllers;

use App\Models\ClinicalSystem;
use App\Models\Skill;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SkillController extends Controller
{
    public function index(Request $request): View
    {
        $skills = Skill::with('clinicalSystem')->orderBy('name')->get();
        $canCreate = $this->canManageContent($request->user());

        return view('skills.index', [
            'skills' => $skills,
            'clinicalSystems' => $canCreate ? ClinicalSystem::orderBy('name')->get() : collect(),
            'canCreate' => $canCreate,
        ]);
    }

    public function store(Request $request)
    {
        abort_unless($this->canManageContent($request->user()), 403, 'You do not have permission to manage the content library.');

        $data = $request->validate([
            'clinical_system_id' => ['nullable', 'exists:clinical_systems,id'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'procedure_steps' => ['nullable', 'string'],
        ]);

        if (! empty($data['procedure_steps'])) {
            $data['procedure_steps'] = collect(preg_split('/\r\n|\r|\n/', $data['procedure_steps']))
                ->map(fn ($step) => trim($step))
                ->filter()
                ->values()
                ->all();
        } else {
            unset($data['procedure_steps']);
        }

        Skill::create($data);

        return redirect()->route('skills.index');
    }

    protected function canManageContent($user): bool
    {
        return $user->isLecturer() || $user->isAdmin() || $user->isSuperadmin();
    }
}
