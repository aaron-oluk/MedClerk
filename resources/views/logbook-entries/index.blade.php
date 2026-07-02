@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Logbook') }}
    </h2>
@endsection

@section('content')
    <div class="py-4">
        {{-- <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6"> --}}
            <div class="mx-auto sm:px-6 lg:px-8 space-y-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <table class="w-full text-left text-sm">
                            <thead>
                                <tr class="border-b border-gray-200">
                                    <th class="py-2">{{ __('Date') }}</th>
                                    <th class="py-2">{{ __('Student') }}</th>
                                    <th class="py-2">{{ __('Rotation') }}</th>
                                    <th class="py-2">{{ __('Clinical sign') }}</th>
                                    <th class="py-2">{{ __('Skill') }}</th>
                                    <th class="py-2">{{ __('Notes') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($entries as $entry)
                                    <tr class="border-b border-gray-100">
                                        <td class="py-2 whitespace-nowrap">{{ $entry->encounter_date->format('d M Y') }}</td>
                                        <td class="py-2">{{ $entry->rotation->student->name }}</td>
                                        <td class="py-2">{{ $entry->rotation->name }}</td>
                                        <td class="py-2">{{ $entry->clinicalSign->name ?? 'Not recorded' }}</td>
                                        <td class="py-2">{{ $entry->skill->name ?? 'Not recorded' }}</td>
                                        <td class="py-2 text-gray-500">
                                            {{ $entry->notes ? \Illuminate\Support\Str::limit($entry->notes, 50) : __('No notes') }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="py-4 text-gray-500">{{ __('No logbook entries yet.') }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if ($entries->hasPages())
                        <div class="px-6 pb-6">
                            {{ $entries->links() }}
                        </div>
                    @endif
                </div>

                @if ($canCreate)
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <form method="POST" action="{{ route('logbook-entries.store') }}" class="p-6 space-y-6">
                            @csrf

                            <h3 class="text-lg font-medium text-gray-900">
                                {{ __('Log a clinical encounter') }}
                            </h3>

                            <div>
                                <label for="rotation_id"
                                    class="block font-medium text-sm text-gray-700">{{ __('Rotation') }}</label>
                                <select id="rotation_id" name="rotation_id" required
                                    class="mt-1 block w-full border-gray-300 focus:border-teal-500 focus:ring-teal-500 rounded-lg shadow-sm">
                                    @foreach ($rotations as $rotation)
                                        <option value="{{ $rotation->id }}" @selected(old('rotation_id') == $rotation->id)>
                                            {{ $rotation->name }}@if ($rotation->relationLoaded('student') && $rotation->student)
                                            ({{ $rotation->student->name }}) @endif
                                        </option>
                                    @endforeach
                                </select>
                                @error('rotation_id')
                                    <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="encounter_date"
                                    class="block font-medium text-sm text-gray-700">{{ __('Encounter date') }}</label>
                                <input id="encounter_date" name="encounter_date" type="date"
                                    value="{{ old('encounter_date', now()->toDateString()) }}" required
                                    class="mt-1 block w-full border-gray-300 focus:border-teal-500 focus:ring-teal-500 rounded-lg shadow-sm">
                                @error('encounter_date')
                                    <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                                <div>
                                    <label for="clinical_sign_id"
                                        class="block font-medium text-sm text-gray-700">{{ __('Clinical sign') }}</label>
                                    <select id="clinical_sign_id" name="clinical_sign_id"
                                        class="mt-1 block w-full border-gray-300 focus:border-teal-500 focus:ring-teal-500 rounded-lg shadow-sm">
                                        <option value="">{{ __('Not applicable') }}</option>
                                        @foreach ($clinicalSigns as $sign)
                                            <option value="{{ $sign->id }}" @selected(old('clinical_sign_id') == $sign->id)>
                                                {{ $sign->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('clinical_sign_id')
                                        <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="skill_id"
                                        class="block font-medium text-sm text-gray-700">{{ __('Skill') }}</label>
                                    <select id="skill_id" name="skill_id"
                                        class="mt-1 block w-full border-gray-300 focus:border-teal-500 focus:ring-teal-500 rounded-lg shadow-sm">
                                        <option value="">{{ __('Not applicable') }}</option>
                                        @foreach ($skills as $skill)
                                            <option value="{{ $skill->id }}" @selected(old('skill_id') == $skill->id)>
                                                {{ $skill->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('skill_id')
                                        <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div>
                                <label for="notes" class="block font-medium text-sm text-gray-700">{{ __('Notes') }}</label>
                                <textarea id="notes" name="notes" rows="3" placeholder="What did you observe or perform?"
                                    class="mt-1 block w-full border-gray-300 focus:border-teal-500 focus:ring-teal-500 rounded-lg shadow-sm">{{ old('notes') }}</textarea>
                                @error('notes')
                                    <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                                @enderror
                            </div>

                            <button type="submit"
                                class="inline-flex items-center justify-center px-4 py-2.5 bg-teal-600 border border-transparent rounded-lg font-semibold text-sm text-white hover:bg-teal-500 focus:bg-teal-500 active:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                {{ __('Save entry') }}
                            </button>
                        </form>
                    </div>
                @elseif (Auth::user()->isStudent())
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 text-sm text-gray-500">
                        {{ __('You need an active rotation before you can log a clinical encounter. Ask your administrator to assign you one.') }}
                    </div>
                @endif
            </div>
        </div>
@endsection