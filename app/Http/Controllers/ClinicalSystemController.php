<?php

namespace App\Http\Controllers;

use App\Models\ClinicalSign;
use App\Models\ClinicalSystem;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ClinicalSystemController extends Controller
{
    public function index(Request $request): View
    {
        $systems = ClinicalSystem::withCount('clinicalSigns', 'skills')->orderBy('name')->get();

        return view('clinical-systems.index', [
            'systems' => $systems,
            'canCreate' => $this->canManageContent($request->user()),
        ]);
    }

    public function show(Request $request, ClinicalSystem $clinicalSystem): View
    {
        $clinicalSystem->load(['clinicalSigns' => fn ($q) => $q->orderBy('name'), 'skills' => fn ($q) => $q->orderBy('name')]);

        return view('clinical-systems.show', [
            'system' => $clinicalSystem,
            'canCreate' => $this->canManageContent($request->user()),
        ]);
    }

    public function store(Request $request)
    {
        $this->authorizeContentWrite($request);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ]);

        ClinicalSystem::create($data);

        return redirect()->route('clinical-systems.index');
    }

    public function storeSign(Request $request, ClinicalSystem $clinicalSystem)
    {
        $this->authorizeContentWrite($request);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'interpretation' => ['nullable', 'string'],
            'diagnostic_relevance' => ['nullable', 'string'],
        ]);

        $data['clinical_system_id'] = $clinicalSystem->id;

        ClinicalSign::create($data);

        return redirect()->route('clinical-systems.show', $clinicalSystem);
    }

    protected function canManageContent($user): bool
    {
        return $user->isLecturer() || $user->isAdmin() || $user->isSuperadmin();
    }

    protected function authorizeContentWrite(Request $request): void
    {
        abort_unless($this->canManageContent($request->user()), 403, 'You do not have permission to manage the content library.');
    }
}
