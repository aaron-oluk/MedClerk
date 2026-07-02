@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ $program->name }} <span class="text-gray-400 font-normal">({{ $program->code }})</span>
    </h2>
@endsection

@section('content')
    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if ($program->description)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 text-sm text-gray-700">
                    {{ $program->description }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('Cohorts') }}</h3>

                    @forelse ($program->cohorts as $cohort)
                        <div class="border-b border-gray-100 py-4 first:pt-0 last:border-b-0 last:pb-0">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="font-medium text-gray-900">{{ $cohort->name }}</p>
                                    <p class="text-sm text-gray-500">
                                        {{ $cohort->start_date->format('d M Y') }}
                                        @if ($cohort->end_date)
                                            {{ __('to') }} {{ $cohort->end_date->format('d M Y') }}
                                        @endif
                                        &middot; {{ __(':count students', ['count' => $cohort->students_count]) }}
                                    </p>
                                </div>
                            </div>

                            @if ($students->isNotEmpty())
                                <form method="POST" action="{{ route('cohorts.enrollments.store', $cohort) }}" class="mt-3 flex flex-wrap items-end gap-3">
                                    @csrf
                                    <div class="flex-1 min-w-[10rem]">
                                        <label for="user_id_{{ $cohort->id }}" class="block text-xs font-medium text-gray-500">{{ __('Enroll a student') }}</label>
                                        <select id="user_id_{{ $cohort->id }}" name="user_id" required
                                                class="mt-1 block w-full border-gray-300 focus:border-teal-500 focus:ring-teal-500 rounded-lg shadow-sm text-sm">
                                            @foreach ($students as $student)
                                                <option value="{{ $student->id }}">{{ $student->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <label for="enrolled_at_{{ $cohort->id }}" class="block text-xs font-medium text-gray-500">{{ __('Enrolled on') }}</label>
                                        <input id="enrolled_at_{{ $cohort->id }}" name="enrolled_at" type="date" value="{{ now()->toDateString() }}" required
                                               class="mt-1 block border-gray-300 focus:border-teal-500 focus:ring-teal-500 rounded-lg shadow-sm text-sm">
                                    </div>
                                    <button type="submit" class="inline-flex items-center justify-center px-3 py-2 bg-teal-600 border border-transparent rounded-lg font-semibold text-xs text-white hover:bg-teal-500 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                        {{ __('Enroll') }}
                                    </button>
                                </form>
                            @endif
                        </div>
                    @empty
                        <p class="text-sm text-gray-500">{{ __('No cohorts yet.') }}</p>
                    @endforelse
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <form method="POST" action="{{ route('programs.cohorts.store', $program) }}" class="p-6 space-y-6">
                    @csrf

                    <h3 class="text-lg font-medium text-gray-900">
                        {{ __('Add a cohort') }}
                    </h3>

                    <div>
                        <label for="name" class="block font-medium text-sm text-gray-700">{{ __('Name') }}</label>
                        <input id="name" name="name" type="text" value="{{ old('name') }}" required placeholder="e.g. 2026 Intake"
                               class="mt-1 block w-full border-gray-300 focus:border-teal-500 focus:ring-teal-500 rounded-lg shadow-sm">
                        @error('name')
                            <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <div>
                            <label for="start_date" class="block font-medium text-sm text-gray-700">{{ __('Start date') }}</label>
                            <input id="start_date" name="start_date" type="date" value="{{ old('start_date') }}" required
                                   class="mt-1 block w-full border-gray-300 focus:border-teal-500 focus:ring-teal-500 rounded-lg shadow-sm">
                            @error('start_date')
                                <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="end_date" class="block font-medium text-sm text-gray-700">{{ __('End date') }}</label>
                            <input id="end_date" name="end_date" type="date" value="{{ old('end_date') }}"
                                   class="mt-1 block w-full border-gray-300 focus:border-teal-500 focus:ring-teal-500 rounded-lg shadow-sm">
                            @error('end_date')
                                <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <button type="submit" class="inline-flex items-center justify-center px-4 py-2.5 bg-teal-600 border border-transparent rounded-lg font-semibold text-sm text-white hover:bg-teal-500 focus:bg-teal-500 active:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        {{ __('Save') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
