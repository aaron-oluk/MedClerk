@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Clinical Library') }}
    </h2>
@endsection

@section('content')
    <div class="py-12">
        <div class="mx-auto sm:px-6 lg:px-8 space-y-6">
            <form method="GET" action="{{ route('clinical-systems.index') }}" class="flex gap-3">
                <input type="text" name="q" value="{{ $search }}" placeholder="{{ __('Search by name or tag') }}"
                       class="block w-full max-w-sm border-gray-300 focus:border-teal-500 focus:ring-teal-500 rounded-lg shadow-sm text-sm">
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg font-medium text-sm text-gray-700 hover:bg-gray-50">
                    {{ __('Search') }}
                </button>
                @if ($search)
                    <a href="{{ route('clinical-systems.index') }}" class="inline-flex items-center px-4 py-2 text-sm text-gray-500 hover:text-gray-700">
                        {{ __('Clear') }}
                    </a>
                @endif
            </form>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <table class="w-full text-left text-sm">
                        <thead>
                            <tr class="border-b border-gray-200">
                                <th class="py-2">{{ __('Clinical system') }}</th>
                                <th class="py-2">{{ __('Style') }}</th>
                                <th class="py-2">{{ __('Signs') }}</th>
                                <th class="py-2">{{ __('Skills') }}</th>
                                <th class="py-2">{{ __('Tags') }}</th>
                                <th class="py-2"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($systems as $system)
                                <tr class="border-b border-gray-100">
                                    <td class="py-2">{{ $system->name }}</td>
                                    <td class="py-2">
                                        <span class="inline-flex items-center gap-1.5">
                                            <span class="inline-block w-3 h-3 rounded-full border border-gray-200" style="background-color: {{ $system->color ?? '#e5e7eb' }}"></span>
                                            <span class="text-gray-500">{{ $system->icon ?? __('Not set') }}</span>
                                        </span>
                                    </td>
                                    <td class="py-2">{{ $system->clinical_signs_count }}</td>
                                    <td class="py-2">{{ $system->skills_count }}</td>
                                    <td class="py-2">
                                        @foreach ($system->tags as $tag)
                                            <span class="inline-flex items-center rounded-full bg-gray-100 px-2 py-0.5 text-xs text-gray-600 mr-1">{{ $tag->name }}</span>
                                        @endforeach
                                    </td>
                                    <td class="py-2 text-right">
                                        <a href="{{ route('clinical-systems.show', $system) }}" class="text-teal-600 hover:text-teal-500 font-medium">
                                            {{ __('View') }}
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-4 text-gray-500">{{ __('No clinical systems yet.') }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            @if ($canCreate)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <form method="POST" action="{{ route('clinical-systems.store') }}" class="p-6 space-y-6">
                        @csrf

                        <h3 class="text-lg font-medium text-gray-900">
                            {{ __('Add a clinical system') }}
                        </h3>

                        <div>
                            <label for="name" class="block font-medium text-sm text-gray-700">{{ __('Name') }}</label>
                            <input id="name" name="name" type="text" value="{{ old('name') }}" required placeholder="e.g. Respiratory System"
                                   class="mt-1 block w-full border-gray-300 focus:border-teal-500 focus:ring-teal-500 rounded-lg shadow-sm">
                            @error('name')
                                <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="description" class="block font-medium text-sm text-gray-700">{{ __('Description') }}</label>
                            <textarea id="description" name="description" rows="3"
                                      class="mt-1 block w-full border-gray-300 focus:border-teal-500 focus:ring-teal-500 rounded-lg shadow-sm">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="tags" class="block font-medium text-sm text-gray-700">{{ __('Tags') }}</label>
                            <p class="text-xs text-gray-500 mt-1">{{ __('Comma separated.') }}</p>
                            <input id="tags" name="tags" type="text" value="{{ old('tags') }}" placeholder="e.g. Cardiovascular, Core system"
                                   class="mt-1 block w-full border-gray-300 focus:border-teal-500 focus:ring-teal-500 rounded-lg shadow-sm">
                            @error('tags')
                                <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <div>
                                <label for="icon" class="block font-medium text-sm text-gray-700">{{ __('Icon') }}</label>
                                <p class="text-xs text-gray-500 mt-1">{{ __('One of: heart, lungs, stomach, brain, bone, gland, kidney, droplet, skin, ear.') }}</p>
                                <input id="icon" name="icon" type="text" value="{{ old('icon') }}" placeholder="e.g. heart"
                                       class="mt-1 block w-full border-gray-300 focus:border-teal-500 focus:ring-teal-500 rounded-lg shadow-sm">
                                @error('icon')
                                    <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="color" class="block font-medium text-sm text-gray-700">{{ __('Colour') }}</label>
                                <p class="text-xs text-gray-500 mt-1">{{ __('Used for the icon badge and mastery bar in the mobile app.') }}</p>
                                <input id="color" name="color" type="color" value="{{ old('color', '#0d9488') }}"
                                       class="mt-1 block h-10 w-20 border-gray-300 rounded-lg shadow-sm">
                                @error('color')
                                    <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                                @enderror
                            </div>
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
