@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Skills') }}
    </h2>
@endsection

@section('content')
    <div class="py-12">
        <div class="mx-auto sm:px-6 lg:px-8 space-y-6">
            <form method="GET" action="{{ route('skills.index') }}" class="flex gap-3">
                <input type="text" name="q" value="{{ $search }}" placeholder="{{ __('Search by name or tag') }}"
                       class="block w-full max-w-sm border-gray-300 focus:border-teal-500 focus:ring-teal-500 rounded-lg shadow-sm text-sm">
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg font-medium text-sm text-gray-700 hover:bg-gray-50">
                    {{ __('Search') }}
                </button>
                @if ($search)
                    <a href="{{ route('skills.index') }}" class="inline-flex items-center px-4 py-2 text-sm text-gray-500 hover:text-gray-700">
                        {{ __('Clear') }}
                    </a>
                @endif
            </form>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <table class="w-full text-left text-sm">
                        <thead>
                            <tr class="border-b border-gray-200">
                                <th class="py-2">{{ __('Name') }}</th>
                                <th class="py-2">{{ __('Clinical system') }}</th>
                                <th class="py-2">{{ __('Competency codes') }}</th>
                                <th class="py-2">{{ __('Equipment') }}</th>
                                <th class="py-2">{{ __('Est. minutes') }}</th>
                                <th class="py-2">{{ __('Procedure steps') }}</th>
                                <th class="py-2">{{ __('Tags') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($skills as $skill)
                                <tr class="border-b border-gray-100">
                                    <td class="py-2">{{ $skill->name }}</td>
                                    <td class="py-2">{{ $skill->clinicalSystem->name ?? 'Not linked' }}</td>
                                    <td class="py-2 text-gray-500">{{ is_array($skill->competency_codes) ? implode(', ', $skill->competency_codes) : 'Not recorded' }}</td>
                                    <td class="py-2 text-gray-500">{{ is_array($skill->equipment) ? implode(', ', $skill->equipment) : 'Not recorded' }}</td>
                                    <td class="py-2 text-gray-500">{{ $skill->est_minutes ?? 'Not recorded' }}</td>
                                    <td class="py-2 text-gray-500">{{ is_array($skill->procedure_steps) ? count($skill->procedure_steps) : 0 }}</td>
                                    <td class="py-2">
                                        @foreach ($skill->tags as $tag)
                                            <span class="inline-flex items-center rounded-full bg-gray-100 px-2 py-0.5 text-xs text-gray-600 mr-1">{{ $tag->name }}</span>
                                        @endforeach
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="py-4 text-gray-500">{{ __('No skills yet.') }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            @if ($canCreate)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <form method="POST" action="{{ route('skills.store') }}" class="p-6 space-y-6">
                        @csrf

                        <h3 class="text-lg font-medium text-gray-900">
                            {{ __('Add a skill') }}
                        </h3>

                        <div>
                            <label for="name" class="block font-medium text-sm text-gray-700">{{ __('Name') }}</label>
                            <input id="name" name="name" type="text" value="{{ old('name') }}" required placeholder="e.g. Cardiovascular examination"
                                   class="mt-1 block w-full border-gray-300 focus:border-teal-500 focus:ring-teal-500 rounded-lg shadow-sm">
                            @error('name')
                                <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="clinical_system_id" class="block font-medium text-sm text-gray-700">{{ __('Clinical system') }}</label>
                            <select id="clinical_system_id" name="clinical_system_id"
                                    class="mt-1 block w-full border-gray-300 focus:border-teal-500 focus:ring-teal-500 rounded-lg shadow-sm">
                                <option value="">{{ __('Not linked') }}</option>
                                @foreach ($clinicalSystems as $system)
                                    <option value="{{ $system->id }}" @selected(old('clinical_system_id') == $system->id)>{{ $system->name }}</option>
                                @endforeach
                            </select>
                            @error('clinical_system_id')
                                <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="description" class="block font-medium text-sm text-gray-700">{{ __('Description') }}</label>
                            <textarea id="description" name="description" rows="2"
                                      class="mt-1 block w-full border-gray-300 focus:border-teal-500 focus:ring-teal-500 rounded-lg shadow-sm">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <div x-data="{ steps: @js(old('procedure_steps', [['title' => '', 'detail' => '', 'caution' => '']])) }">
                            <label class="block font-medium text-sm text-gray-700">{{ __('Procedure steps') }}</label>
                            <p class="text-xs text-gray-500 mt-1">{{ __('Add each step of the procedure, with an optional caution note.') }}</p>

                            <div class="mt-2 space-y-3">
                                <template x-for="(step, index) in steps" :key="index">
                                    <div class="border border-gray-200 rounded-lg p-4 space-y-2">
                                        <div class="flex items-center justify-between">
                                            <span class="text-xs font-medium text-gray-500" x-text="'{{ __('Step') }} ' + (index + 1)"></span>
                                            <button type="button" @click="steps.splice(index, 1)" x-show="steps.length > 1" class="text-xs text-red-600 hover:text-red-500">
                                                {{ __('Remove') }}
                                            </button>
                                        </div>

                                        <input type="text" :name="'procedure_steps[' + index + '][title]'" x-model="step.title" placeholder="{{ __('Step title') }}"
                                               class="block w-full border-gray-300 focus:border-teal-500 focus:ring-teal-500 rounded-lg shadow-sm text-sm">

                                        <textarea :name="'procedure_steps[' + index + '][detail]'" x-model="step.detail" rows="2" placeholder="{{ __('Detail') }}"
                                                  class="block w-full border-gray-300 focus:border-teal-500 focus:ring-teal-500 rounded-lg shadow-sm text-sm"></textarea>

                                        <input type="text" :name="'procedure_steps[' + index + '][caution]'" x-model="step.caution" placeholder="{{ __('Caution (optional)') }}"
                                               class="block w-full border-gray-300 focus:border-teal-500 focus:ring-teal-500 rounded-lg shadow-sm text-sm">
                                    </div>
                                </template>
                            </div>

                            <button type="button" @click="steps.push({ title: '', detail: '', caution: '' })"
                                    class="mt-3 inline-flex items-center px-3 py-1.5 bg-white border border-gray-300 rounded-lg font-medium text-xs text-gray-700 hover:bg-gray-50">
                                {{ __('+ Add step') }}
                            </button>

                            @error('procedure_steps.*.title')
                                <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="competency_codes" class="block font-medium text-sm text-gray-700">{{ __('Competency codes') }}</label>
                            <p class="text-xs text-gray-500 mt-1">{{ __('Comma separated, e.g. CBME.CVS.01, CBME.CVS.02') }}</p>
                            <input id="competency_codes" name="competency_codes" type="text" value="{{ old('competency_codes') }}"
                                   class="mt-1 block w-full border-gray-300 focus:border-teal-500 focus:ring-teal-500 rounded-lg shadow-sm">
                            @error('competency_codes')
                                <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <div>
                                <label for="equipment" class="block font-medium text-sm text-gray-700">{{ __('Equipment') }}</label>
                                <p class="text-xs text-gray-500 mt-1">{{ __('Comma separated, e.g. Stethoscope, Sphygmomanometer') }}</p>
                                <input id="equipment" name="equipment" type="text" value="{{ old('equipment') }}"
                                       class="mt-1 block w-full border-gray-300 focus:border-teal-500 focus:ring-teal-500 rounded-lg shadow-sm">
                                @error('equipment')
                                    <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="est_minutes" class="block font-medium text-sm text-gray-700">{{ __('Est. minutes') }}</label>
                                <input id="est_minutes" name="est_minutes" type="number" min="0" value="{{ old('est_minutes') }}" placeholder="e.g. 10"
                                       class="mt-1 block w-full border-gray-300 focus:border-teal-500 focus:ring-teal-500 rounded-lg shadow-sm">
                                @error('est_minutes')
                                    <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label for="tags" class="block font-medium text-sm text-gray-700">{{ __('Tags') }}</label>
                            <p class="text-xs text-gray-500 mt-1">{{ __('Comma separated.') }}</p>
                            <input id="tags" name="tags" type="text" value="{{ old('tags') }}" placeholder="e.g. Cardiovascular, Core skill"
                                   class="mt-1 block w-full border-gray-300 focus:border-teal-500 focus:ring-teal-500 rounded-lg shadow-sm">
                            @error('tags')
                                <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <button type="submit" class="inline-flex items-center justify-center px-4 py-2.5 bg-teal-600 border border-transparent rounded-lg font-semibold text-sm text-white hover:bg-teal-500 focus:bg-teal-500 active:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            {{ __('Save') }}
                        </button>
                    </form>
                </div>
            @endif
        </div>
    </div>
@endsection
