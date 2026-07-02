<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\SyncsTags;
use App\Models\ClinicalSign;
use App\Models\ClinicalSystem;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ClinicalSystemController extends Controller
{
    use SyncsTags;

    public function index(Request $request): View
    {
        $query = ClinicalSystem::withCount(['clinicalSigns', 'skills'])->with('tags')->orderBy('name');

        if ($request->filled('q')) {
            $search = $request->string('q');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhereHas('tags', fn ($t) => $t->where('name', 'like', "%{$search}%"));
            });
        }

        $systems = $query->get();

        return view('clinical-systems.index', [
            'systems' => $systems,
            'canCreate' => $this->canManageContent($request->user()),
            'search' => $request->string('q')->toString(),
        ]);
    }

    public function show(Request $request, ClinicalSystem $clinicalSystem): View
    {
        $clinicalSystem->load([
            'clinicalSigns' => fn ($q) => $q->orderBy('name'),
            'clinicalSigns.tags',
            'skills' => fn ($q) => $q->orderBy('name'),
            'tags',
        ]);

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
            'tags' => ['nullable', 'string'],
        ]);

        $tagsInput = $data['tags'] ?? null;
        unset($data['tags']);

        $system = ClinicalSystem::create($data);

        $this->syncTagsFromInput($system, $tagsInput);

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
            'media_urls' => ['nullable', 'string'],
            'tags' => ['nullable', 'string'],
        ]);

        $tagsInput = $data['tags'] ?? null;
        unset($data['tags']);

        $data['clinical_system_id'] = $clinicalSystem->id;

        $mediaUrls = collect(preg_split('/\r\n|\r|\n/', $data['media_urls'] ?? ''))
            ->map(fn ($url) => trim($url))
            ->filter()
            ->values()
            ->all();

        if (empty($mediaUrls)) {
            unset($data['media_urls']);
        } else {
            $data['media_urls'] = $mediaUrls;
        }

        $sign = ClinicalSign::create($data);

        $this->syncTagsFromInput($sign, $tagsInput);

        return redirect()->route('clinical-systems.show', $clinicalSystem);
    }

    protected function canManageContent(User $user): bool
    {
        return $user->isLecturer() || $user->isAdmin() || $user->isSuperadmin();
    }

    protected function authorizeContentWrite(Request $request): void
    {
        abort_unless($this->canManageContent($request->user()), 403, 'You do not have permission to manage the content library.');
    }
}
