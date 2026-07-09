<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ClinicalSign;
use Illuminate\Http\Request;

class ClinicalSignController extends Controller
{
    public function index(Request $request)
    {
        $query = ClinicalSign::query()->with('clinicalSystem', 'tags')->orderBy('name');

        if ($request->filled('clinical_system_id')) {
            $query->where('clinical_system_id', $request->integer('clinical_system_id'));
        }

        return $query->paginate(50);
    }

    public function show(ClinicalSign $clinicalSign)
    {
        return $clinicalSign->load('clinicalSystem', 'tags');
    }

    public function store(Request $request)
    {
        $this->authorizeContentWrite($request);

        $data = $request->validate([
            'clinical_system_id' => ['required', 'exists:clinical_systems,id'],
            'name' => ['required', 'string', 'max:255'],
            'eponym' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'interpretation' => ['nullable', 'string'],
            'technique' => ['nullable', 'string'],
            'diagnostic_relevance' => ['nullable', 'string'],
            'red_flags' => ['nullable', 'array'],
            'difficulty' => ['nullable', 'string', 'in:core,intermediate,advanced'],
            'last_reviewed' => ['nullable', 'date'],
            'media_urls' => ['nullable', 'array'],
            'media_type' => ['nullable', 'string', 'in:video,image,audio,text'],
            'media_duration' => ['nullable', 'string', 'max:20'],
        ]);

        return ClinicalSign::create($data);
    }

    public function update(Request $request, ClinicalSign $clinicalSign)
    {
        $this->authorizeContentWrite($request);

        $data = $request->validate([
            'clinical_system_id' => ['sometimes', 'exists:clinical_systems,id'],
            'name' => ['sometimes', 'string', 'max:255'],
            'eponym' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'interpretation' => ['nullable', 'string'],
            'technique' => ['nullable', 'string'],
            'diagnostic_relevance' => ['nullable', 'string'],
            'red_flags' => ['nullable', 'array'],
            'difficulty' => ['nullable', 'string', 'in:core,intermediate,advanced'],
            'last_reviewed' => ['nullable', 'date'],
            'media_urls' => ['nullable', 'array'],
            'media_type' => ['nullable', 'string', 'in:video,image,audio,text'],
            'media_duration' => ['nullable', 'string', 'max:20'],
        ]);

        $clinicalSign->update($data);

        return $clinicalSign;
    }

    public function destroy(Request $request, ClinicalSign $clinicalSign)
    {
        $this->authorizeContentWrite($request);

        $clinicalSign->delete();

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
