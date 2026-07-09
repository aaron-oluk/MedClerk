<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ClinicalSystem;
use App\Services\MasteryService;
use Illuminate\Http\Request;

class ClinicalSystemController extends Controller
{
    public function __construct(private readonly MasteryService $mastery)
    {
    }

    public function index(Request $request)
    {
        $systems = ClinicalSystem::query()
            ->withCount('clinicalSigns')
            ->with('tags')
            ->orderBy('name')
            ->paginate(50);

        $masteryBySystem = $this->mastery->bySystemFor($request->user()->id);

        $systems->getCollection()->each(
            fn (ClinicalSystem $system) => $system->mastery_pct = $masteryBySystem[$system->id] ?? null
        );

        return $systems;
    }

    public function show(ClinicalSystem $clinicalSystem)
    {
        return $clinicalSystem->load('clinicalSigns', 'skills', 'tags');
    }

    public function store(Request $request)
    {
        $this->authorizeContentWrite($request);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'icon' => ['nullable', 'string', 'max:50'],
            'color' => ['nullable', 'string', 'max:20'],
        ]);

        return ClinicalSystem::create($data);
    }

    public function update(Request $request, ClinicalSystem $clinicalSystem)
    {
        $this->authorizeContentWrite($request);

        $data = $request->validate([
            'name' => ['sometimes', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'icon' => ['nullable', 'string', 'max:50'],
            'color' => ['nullable', 'string', 'max:20'],
        ]);

        $clinicalSystem->update($data);

        return $clinicalSystem;
    }

    public function destroy(Request $request, ClinicalSystem $clinicalSystem)
    {
        $this->authorizeContentWrite($request);

        $clinicalSystem->delete();

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
