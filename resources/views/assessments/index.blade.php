@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Assessments') }}
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
                                <th class="py-2">{{ __('Date') }}</th>
                                <th class="py-2">{{ __('Student') }}</th>
                                <th class="py-2">{{ __('Skill') }}</th>
                                <th class="py-2">{{ __('Rotation') }}</th>
                                <th class="py-2">{{ __('Assessor') }}</th>
                                <th class="py-2">{{ __('Score') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($assessments as $assessment)
                                <tr class="border-b border-gray-100">
                                    <td class="py-2 whitespace-nowrap">{{ $assessment->assessed_at->format('d M Y') }}</td>
                                    <td class="py-2">{{ $assessment->student->name }}</td>
                                    <td class="py-2">{{ $assessment->skill->name }}</td>
                                    <td class="py-2">{{ $assessment->rotation->name }}</td>
                                    <td class="py-2">{{ $assessment->assessor->name }}</td>
                                    <td class="py-2">{{ $assessment->score + 0 }} / {{ $assessment->max_score + 0 }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-4 text-gray-500">{{ __('No assessments yet.') }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($assessments->hasPages())
                    <div class="px-6 pb-6">
                        {{ $assessments->links() }}
                    </div>
                @endif
            </div>

            @if ($canCreate)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <form method="POST" action="{{ route('assessments.store') }}" class="p-6 space-y-6">
                        @csrf

                        <h3 class="text-lg font-medium text-gray-900">
                            {{ __('Score an assessment') }}
                        </h3>

                        <div>
                            <label for="rotation_id" class="block font-medium text-sm text-gray-700">{{ __('Student and rotation') }}</label>
                            <select id="rotation_id" name="rotation_id" required
                                    class="mt-1 block w-full border-gray-300 focus:border-teal-500 focus:ring-teal-500 rounded-lg shadow-sm">
                                @foreach ($rotations as $rotation)
                                    <option value="{{ $rotation->id }}" @selected(old('rotation_id') == $rotation->id)>
                                        {{ $rotation->student->name }} ({{ $rotation->name }})
                                    </option>
                                @endforeach
                            </select>
                            @error('rotation_id')
                                <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="skill_id" class="block font-medium text-sm text-gray-700">{{ __('Skill') }}</label>
                            <select id="skill_id" name="skill_id" required
                                    class="mt-1 block w-full border-gray-300 focus:border-teal-500 focus:ring-teal-500 rounded-lg shadow-sm">
                                @foreach ($skills as $skill)
                                    <option value="{{ $skill->id }}" @selected(old('skill_id') == $skill->id)>{{ $skill->name }}</option>
                                @endforeach
                            </select>
                            @error('skill_id')
                                <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <div>
                                <label for="score" class="block font-medium text-sm text-gray-700">{{ __('Score') }}</label>
                                <input id="score" name="score" type="number" step="0.01" min="0" value="{{ old('score') }}" required
                                       class="mt-1 block w-full border-gray-300 focus:border-teal-500 focus:ring-teal-500 rounded-lg shadow-sm">
                                @error('score')
                                    <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="max_score" class="block font-medium text-sm text-gray-700">{{ __('Maximum score') }}</label>
                                <input id="max_score" name="max_score" type="number" step="0.01" min="0" value="{{ old('max_score', 20) }}" required
                                       class="mt-1 block w-full border-gray-300 focus:border-teal-500 focus:ring-teal-500 rounded-lg shadow-sm">
                                @error('max_score')
                                    <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label for="assessed_at" class="block font-medium text-sm text-gray-700">{{ __('Assessed on') }}</label>
                            <input id="assessed_at" name="assessed_at" type="date" value="{{ old('assessed_at', now()->toDateString()) }}" required
                                   class="mt-1 block w-full border-gray-300 focus:border-teal-500 focus:ring-teal-500 rounded-lg shadow-sm">
                            @error('assessed_at')
                                <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <button type="submit" class="inline-flex items-center justify-center px-4 py-2.5 bg-teal-600 border border-transparent rounded-lg font-semibold text-sm text-white hover:bg-teal-500 focus:bg-teal-500 active:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            {{ __('Save assessment') }}
                        </button>
                    </form>
                </div>
            @endif
        </div>
    </div>
@endsection
