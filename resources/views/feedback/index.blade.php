@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Feedback') }}
    </h2>
@endsection

@section('content')
    <div class="py-12">
        <div class="mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @forelse ($feedbackEntries as $entry)
                        <div class="border-b border-gray-100 pb-4 mb-4 last:border-b-0 last:pb-0 last:mb-0">
                            <p class="text-sm text-gray-500">
                                {{ __(':student, assessed on :skill', ['student' => $entry->student->name, 'skill' => $entry->assessment?->skill?->name ?? __('an assessment')]) }}
                            </p>
                            <p class="text-xs text-gray-400">
                                {{ __('From :name on :date', ['name' => $entry->givenBy->name, 'date' => $entry->created_at->format('d M Y')]) }}
                            </p>
                            @if ($entry->strengths)
                                <p class="mt-2 text-sm text-gray-900"><span class="font-medium">{{ __('Strengths') }}:</span> {{ $entry->strengths }}</p>
                            @endif
                            @if ($entry->areas_to_improve)
                                <p class="mt-1 text-sm text-gray-900"><span class="font-medium">{{ __('Areas to improve') }}:</span> {{ $entry->areas_to_improve }}</p>
                            @endif
                            @if ($entry->follow_up_date)
                                <p class="mt-1 text-xs text-gray-500">{{ __('Follow up on :date', ['date' => $entry->follow_up_date->format('d M Y')]) }}</p>
                            @endif
                        </div>
                    @empty
                        <p class="text-sm text-gray-500">{{ __('No feedback yet.') }}</p>
                    @endforelse
                </div>

                @if ($feedbackEntries->hasPages())
                    <div class="px-6 pb-6">
                        {{ $feedbackEntries->links() }}
                    </div>
                @endif
            </div>

            @if ($canCreate)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <form method="POST" action="{{ route('feedback.store') }}" class="p-6 space-y-6">
                        @csrf

                        <h3 class="text-lg font-medium text-gray-900">
                            {{ __('Give feedback') }}
                        </h3>

                        <div>
                            <label for="assessment_id" class="block font-medium text-sm text-gray-700">{{ __('Assessment') }}</label>
                            <select id="assessment_id" name="assessment_id" required
                                    class="mt-1 block w-full border-gray-300 focus:border-teal-500 focus:ring-teal-500 rounded-lg shadow-sm">
                                @foreach ($assessments as $assessment)
                                    <option value="{{ $assessment->id }}" @selected(old('assessment_id') == $assessment->id)>
                                        {{ $assessment->student->name }}, {{ $assessment->skill->name }} ({{ $assessment->assessed_at->format('d M Y') }})
                                    </option>
                                @endforeach
                            </select>
                            @error('assessment_id')
                                <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="strengths" class="block font-medium text-sm text-gray-700">{{ __('Strengths') }}</label>
                            <textarea id="strengths" name="strengths" rows="2"
                                      class="mt-1 block w-full border-gray-300 focus:border-teal-500 focus:ring-teal-500 rounded-lg shadow-sm">{{ old('strengths') }}</textarea>
                            @error('strengths')
                                <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="areas_to_improve" class="block font-medium text-sm text-gray-700">{{ __('Areas to improve') }}</label>
                            <textarea id="areas_to_improve" name="areas_to_improve" rows="2"
                                      class="mt-1 block w-full border-gray-300 focus:border-teal-500 focus:ring-teal-500 rounded-lg shadow-sm">{{ old('areas_to_improve') }}</textarea>
                            @error('areas_to_improve')
                                <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="follow_up_date" class="block font-medium text-sm text-gray-700">{{ __('Follow up date') }}</label>
                            <input id="follow_up_date" name="follow_up_date" type="date" value="{{ old('follow_up_date') }}"
                                   class="mt-1 block w-full border-gray-300 focus:border-teal-500 focus:ring-teal-500 rounded-lg shadow-sm">
                            @error('follow_up_date')
                                <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <button type="submit" class="inline-flex items-center justify-center px-4 py-2.5 bg-teal-600 border border-transparent rounded-lg font-semibold text-sm text-white hover:bg-teal-500 focus:bg-teal-500 active:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            {{ __('Save feedback') }}
                        </button>
                    </form>
                </div>
            @endif
        </div>
    </div>
@endsection
