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
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-4">
                        {{ __('Pending encounters to score') }}
                    </h3>

                    <div class="space-y-6">
                        @foreach ($pendingLogs as $log)
                            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                                <div class="p-6 space-y-4">
                                    <div>
                                        <h4 class="font-medium text-gray-900">{{ $log->student->name }} — {{ $log->skill->name }}</h4>
                                        <p class="text-sm text-gray-500">{{ $log->encounter_date->format('d M Y') }} · {{ $log->rotation->name }}</p>
                                    </div>

                                    <div class="space-y-3 text-sm border-t border-gray-100 pt-4">
                                        @if (! empty($log->findings['chief_complaint'] ?? null))
                                            <div>
                                                <p class="font-medium text-gray-700">{{ __('Chief complaint') }}</p>
                                                <p class="text-gray-600 mt-1">{{ $log->findings['chief_complaint'] }}</p>
                                            </div>
                                        @endif
                                        @if (! empty($log->findings['examination_findings'] ?? null))
                                            <div>
                                                <p class="font-medium text-gray-700">{{ __('Examination findings') }}</p>
                                                <p class="text-gray-600 mt-1">{{ $log->findings['examination_findings'] }}</p>
                                            </div>
                                        @endif
                                        @if (! empty($log->findings['impression'] ?? null))
                                            <div>
                                                <p class="font-medium text-gray-700">{{ __('Impression') }}</p>
                                                <p class="text-gray-600 mt-1">{{ $log->findings['impression'] }}</p>
                                            </div>
                                        @endif
                                        @if (! empty($log->findings['plan'] ?? null))
                                            <div>
                                                <p class="font-medium text-gray-700">{{ __('Plan') }}</p>
                                                <p class="text-gray-600 mt-1">{{ $log->findings['plan'] }}</p>
                                            </div>
                                        @endif
                                        @if ($log->notes)
                                            <div>
                                                <p class="font-medium text-gray-700">{{ __('Notes') }}</p>
                                                <p class="text-gray-600 mt-1">{{ $log->notes }}</p>
                                            </div>
                                        @endif
                                        @if (empty($log->findings) && ! $log->notes)
                                            <p class="text-gray-500 italic">{{ __('No structured findings recorded for this encounter.') }}</p>
                                        @endif
                                    </div>

                                    <form method="POST" action="{{ route('assessments.store') }}" class="border-t border-gray-100 pt-4 space-y-4">
                                        @csrf
                                        <input type="hidden" name="logbook_entry_id" value="{{ $log->id }}">

                                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-3 sm:items-end">
                                            <div>
                                                <label for="score-{{ $log->id }}" class="block font-medium text-sm text-gray-700">{{ __('Score') }}</label>
                                                <input id="score-{{ $log->id }}" name="score" type="number" step="0.01" min="0" required
                                                       class="mt-1 block w-full border-gray-300 focus:border-teal-500 focus:ring-teal-500 rounded-lg shadow-sm">
                                            </div>

                                            <div>
                                                <label for="max_score-{{ $log->id }}" class="block font-medium text-sm text-gray-700">{{ __('Out of') }}</label>
                                                <input id="max_score-{{ $log->id }}" name="max_score" type="number" step="0.01" min="0" value="20" required
                                                       class="mt-1 block w-full border-gray-300 focus:border-teal-500 focus:ring-teal-500 rounded-lg shadow-sm">
                                            </div>

                                            <div>
                                                <label for="assessed_at-{{ $log->id }}" class="block font-medium text-sm text-gray-700">{{ __('Assessed on') }}</label>
                                                <input id="assessed_at-{{ $log->id }}" name="assessed_at" type="date" value="{{ now()->toDateString() }}" required
                                                       class="mt-1 block w-full border-gray-300 focus:border-teal-500 focus:ring-teal-500 rounded-lg shadow-sm">
                                            </div>
                                        </div>

                                        <button type="submit" class="inline-flex items-center justify-center px-4 py-2.5 bg-teal-600 border border-transparent rounded-lg font-semibold text-sm text-white hover:bg-teal-500 focus:bg-teal-500 active:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                            {{ __('Save assessment') }}
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
