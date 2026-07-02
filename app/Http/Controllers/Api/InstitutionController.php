<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Institution;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class InstitutionController extends Controller
{
    public function index()
    {
        return Institution::query()->orderBy('name')->paginate(25);
    }

    public function show(Institution $institution)
    {
        return $institution->load('departments', 'programs');
    }

    public function store(Request $request)
    {
        $this->authorize('create', Institution::class);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'country' => ['nullable', 'string', 'max:255'],
            'status' => ['nullable', 'string', 'in:active,inactive'],
        ]);

        $data['slug'] = Str::slug($data['name']);

        return Institution::create($data);
    }

    public function update(Request $request, Institution $institution)
    {
        $this->authorize('update', $institution);

        $data = $request->validate([
            'name' => ['sometimes', 'string', 'max:255'],
            'country' => ['nullable', 'string', 'max:255'],
            'status' => ['nullable', 'string', 'in:active,inactive'],
        ]);

        if (isset($data['name'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        $institution->update($data);

        return $institution;
    }

    public function destroy(Institution $institution)
    {
        $this->authorize('delete', $institution);

        $institution->delete();

        return response()->noContent();
    }
}
