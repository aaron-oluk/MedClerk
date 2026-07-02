@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Clinical Library') }}
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
                                <th class="py-2">{{ __('Clinical system') }}</th>
                                <th class="py-2">{{ __('Signs') }}</th>
                                <th class="py-2">{{ __('Skills') }}</th>
                                <th class="py-2"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($systems as $system)
                                <tr class="border-b border-gray-100">
                                    <td class="py-2">{{ $system->name }}</td>
                                    <td class="py-2">{{ $system->clinical_signs_count }}</td>
                                    <td class="py-2">{{ $system->skills_count }}</td>
                                    <td class="py-2 text-right">
                                        <a href="{{ route('clinical-systems.show', $system) }}"
                                            class="text-teal-600 hover:text-teal-500 font-medium">
                                            {{ __('View') }}
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="py-4 text-gray-500">{{ __('No clinical systems yet.') }}</td>
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
                            <input id="name" name="name" type="text" value="{{ old('name') }}" required
                                placeholder="e.g. Respiratory System"
                                class="mt-1 block w-full border-gray-300 focus:border-teal-500 focus:ring-teal-500 rounded-lg shadow-sm">
                            @error('name')
                                <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="description"
                                class="block font-medium text-sm text-gray-700">{{ __('Description') }}</label>
                            <textarea id="description" name="description" rows="3"
                                class="mt-1 block w-full border-gray-300 focus:border-teal-500 focus:ring-teal-500 rounded-lg shadow-sm">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <button type="submit"
                            class="inline-flex items-center justify-center px-4 py-2.5 bg-teal-600 border border-transparent rounded-lg font-semibold text-sm text-white hover:bg-teal-500 focus:bg-teal-500 active:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            {{ __('Save') }}
                        </button>
                    </form>
                </div>
            @endif
        </div>
    </div>
@endsection