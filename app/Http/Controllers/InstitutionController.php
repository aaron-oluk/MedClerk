<?php

namespace App\Http\Controllers;

use App\Models\Institution;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class InstitutionController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Institutions/Index', [
            'institutions' => Institution::query()->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $this->authorize('create', Institution::class);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'country' => ['nullable', 'string', 'max:255'],
        ]);

        $data['slug'] = Str::slug($data['name']);

        Institution::create($data);

        return redirect()->route('institutions.index');
    }
}
