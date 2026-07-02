@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Institutions') }}
    </h2>
@endsection

@section('content')
    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <table class="w-full text-left text-sm">
                        <thead>
                            <tr class="border-b border-gray-200">
                                <th class="py-2">Name</th>
                                <th class="py-2">Country</th>
                                <th class="py-2">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($institutions as $institution)
                                <tr class="border-b border-gray-100">
                                    <td class="py-2">{{ $institution->name }}</td>
                                    <td class="py-2">{{ $institution->country ?? 'Not set' }}</td>
                                    <td class="py-2">{{ $institution->status }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="py-4 text-gray-500">No institutions yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <form method="POST" action="{{ route('institutions.store') }}" class="p-6 space-y-6">
                    @csrf

                    <h3 class="text-lg font-medium text-gray-900">
                        {{ __('Add an institution') }}
                    </h3>

                    <div>
                        <label for="name" class="block font-medium text-sm text-gray-700">{{ __('Name') }}</label>
                        <input id="name" name="name" type="text" value="{{ old('name') }}" required autofocus
                               class="mt-1 block w-full border-gray-300 focus:border-teal-500 focus:ring-teal-500 rounded-lg shadow-sm">
                        @error('name')
                            <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="country" class="block font-medium text-sm text-gray-700">{{ __('Country') }}</label>
                        <input id="country" name="country" type="text" value="{{ old('country') }}"
                               class="mt-1 block w-full border-gray-300 focus:border-teal-500 focus:ring-teal-500 rounded-lg shadow-sm">
                        @error('country')
                            <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit" class="inline-flex items-center justify-center px-4 py-2.5 bg-teal-600 border border-transparent rounded-lg font-semibold text-sm text-white hover:bg-teal-500 focus:bg-teal-500 active:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        {{ __('Save') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
