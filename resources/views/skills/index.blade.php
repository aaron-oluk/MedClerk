@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Skills') }}
    </h2>
@endsection

@section('content')
    <div class="py-12">
        <div class="mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <table class="w-full text-left text-sm">
                        <thead>
                            <tr class="border-b border-gray-200">
                                <th class="py-2">{{ __('Name') }}</th>
                                <th class="py-2">{{ __('Clinical system') }}</th>
                                <th class="py-2">{{ __('Procedure steps') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($skills as $skill)
                                <tr class="border-b border-gray-100">
                                    <td class="py-2">{{ $skill->name }}</td>
                                    <td class="py-2">{{ $skill->clinicalSystem->name ?? 'Not linked' }}</td>
                                    <td class="py-2 text-gray-500">{{ is_array($skill->procedure_steps) ? count($skill->procedure_steps) : 0 }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="py-4 text-gray-500">{{ __('No skills yet.') }}</td>
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

                        <div>
                            <label for="procedure_steps" class="block font-medium text-sm text-gray-700">{{ __('Procedure steps') }}</label>
                            <p class="text-xs text-gray-500 mt-1">{{ __('One step per line.') }}</p>
                            <textarea id="procedure_steps" name="procedure_steps" rows="4"
                                      class="mt-1 block w-full border-gray-300 focus:border-teal-500 focus:ring-teal-500 rounded-lg shadow-sm">{{ old('procedure_steps') }}</textarea>
                            @error('procedure_steps')
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
