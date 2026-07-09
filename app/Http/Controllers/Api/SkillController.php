<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Skill;
use Illuminate\Http\Request;

class SkillController extends Controller
{
    public function index()
    {
        return Skill::query()->with('clinicalSystem', 'tags')->orderBy('name')->paginate(50);
    }

    public function show(Skill $skill)
    {
        return $skill->load('clinicalSystem', 'tags');
    }

    public function store(Request $request)
    {
        $this->authorizeContentWrite($request);

        $data = $request->validate([
            'clinical_system_id' => ['nullable', 'exists:clinical_systems,id'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'procedure_steps' => ['nullable', 'array'],
            'competency_codes' => ['nullable', 'array'],
            'equipment' => ['nullable', 'array'],
            'est_minutes' => ['nullable', 'integer', 'min:0'],
        ]);

        return Skill::create($data);
    }

    public function update(Request $request, Skill $skill)
    {
        $this->authorizeContentWrite($request);

        $data = $request->validate([
            'clinical_system_id' => ['nullable', 'exists:clinical_systems,id'],
            'name' => ['sometimes', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'procedure_steps' => ['nullable', 'array'],
            'competency_codes' => ['nullable', 'array'],
            'equipment' => ['nullable', 'array'],
            'est_minutes' => ['nullable', 'integer', 'min:0'],
        ]);

        $skill->update($data);

        return $skill;
    }

    public function destroy(Request $request, Skill $skill)
    {
        $this->authorizeContentWrite($request);

        $skill->delete();

        return response()->noContent();
    }

    protected function authorizeContentWrite(Request $request): void
    {
        abort_unless(
            $request->user()->isLecturer() || $request->user()->isAdmin() || $request->user()->isSuperadmin(),
            403,
            'You do not have permission to manage the content library.'
        );
    }
}
