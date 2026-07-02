<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\SyncsTags;
use App\Models\ClinicalSystem;
use App\Models\Skill;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SkillController extends Controller
{
    use SyncsTags;

    public function index(Request $request): View
    {
        $query = Skill::with(['clinicalSystem', 'tags'])->orderBy('name');

        if ($request->filled('q')) {
            $search = $request->string('q');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhereHas('tags', fn ($t) => $t->where('name', 'like', "%{$search}%"));
            });
        }

        $skills = $query->get();
        $canCreate = $this->canManageContent($request->user());

        return view('skills.index', [
            'skills' => $skills,
            'clinicalSystems' => $canCreate ? ClinicalSystem::orderBy('name')->get() : collect(),
            'canCreate' => $canCreate,
            'search' => $request->string('q')->toString(),
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
            'competency_codes' => ['nullable', 'string'],
            'tags' => ['nullable', 'string'],
        ]);

        $tagsInput = $data['tags'] ?? null;
        unset($data['tags']);

        $data['procedure_steps'] = $this->linesToArray($data['procedure_steps'] ?? null);
        $data['competency_codes'] = $this->commaToArray($data['competency_codes'] ?? null);

        if (empty($data['procedure_steps'])) {
            unset($data['procedure_steps']);
        }
        if (empty($data['competency_codes'])) {
            unset($data['competency_codes']);
        }

        $skill = Skill::create($data);

        $this->syncTagsFromInput($skill, $tagsInput);

        return redirect()->route('skills.index');
    }

    protected function linesToArray(?string $value): array
    {
        if (blank($value)) {
            return [];
        }

        return collect(preg_split('/\r\n|\r|\n/', $value))
            ->map(fn ($line) => trim($line))
            ->filter()
            ->values()
            ->all();
    }

    protected function commaToArray(?string $value): array
    {
        if (blank($value)) {
            return [];
        }

        return collect(explode(',', $value))
            ->map(fn ($item) => trim($item))
            ->filter()
            ->values()
            ->all();
    }

    protected function canManageContent(User $user): bool
    {
        return $user->isLecturer() || $user->isAdmin() || $user->isSuperadmin();
    }
}
