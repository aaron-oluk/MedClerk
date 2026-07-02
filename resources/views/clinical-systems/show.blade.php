@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ $system->name }}
    </h2>
@endsection

@section('content')
    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if ($system->description)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 text-sm text-gray-700">
                    {{ $system->description }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('Clinical signs') }}</h3>

                    <table class="w-full text-left text-sm">
                        <thead>
                            <tr class="border-b border-gray-200">
                                <th class="py-2">{{ __('Name') }}</th>
                                <th class="py-2">{{ __('Interpretation') }}</th>
                                <th class="py-2">{{ __('Diagnostic relevance') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($system->clinicalSigns as $sign)
                                <tr class="border-b border-gray-100">
                                    <td class="py-2">{{ $sign->name }}</td>
                                    <td class="py-2 text-gray-500">{{ $sign->interpretation ?? 'Not recorded' }}</td>
                                    <td class="py-2 text-gray-500">{{ $sign->diagnostic_relevance ?? 'Not recorded' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="py-4 text-gray-500">{{ __('No clinical signs recorded yet.') }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            @if ($system->skills->isNotEmpty())
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('Related skills') }}</h3>

                        <ul class="space-y-2 text-sm">
                            @foreach ($system->skills as $skill)
                                <li>
                                    <a href="{{ route('skills.index') }}" class="text-teal-600 hover:text-teal-500 font-medium">{{ $skill->name }}</a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            @if ($canCreate)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <form method="POST" action="{{ route('clinical-systems.signs.store', $system) }}" class="p-6 space-y-6">
                        @csrf

                        <h3 class="text-lg font-medium text-gray-900">
                            {{ __('Add a clinical sign') }}
                        </h3>

                        <div>
                            <label for="name" class="block font-medium text-sm text-gray-700">{{ __('Name') }}</label>
                            <input id="name" name="name" type="text" value="{{ old('name') }}" required placeholder="e.g. Raised jugular venous pressure"
                                   class="mt-1 block w-full border-gray-300 focus:border-teal-500 focus:ring-teal-500 rounded-lg shadow-sm">
                            @error('name')
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
                            <label for="interpretation" class="block font-medium text-sm text-gray-700">{{ __('Interpretation') }}</label>
                            <textarea id="interpretation" name="interpretation" rows="2"
                                      class="mt-1 block w-full border-gray-300 focus:border-teal-500 focus:ring-teal-500 rounded-lg shadow-sm">{{ old('interpretation') }}</textarea>
                            @error('interpretation')
                                <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="diagnostic_relevance" class="block font-medium text-sm text-gray-700">{{ __('Diagnostic relevance') }}</label>
                            <textarea id="diagnostic_relevance" name="diagnostic_relevance" rows="2"
                                      class="mt-1 block w-full border-gray-300 focus:border-teal-500 focus:ring-teal-500 rounded-lg shadow-sm">{{ old('diagnostic_relevance') }}</textarea>
                            @error('diagnostic_relevance')
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
