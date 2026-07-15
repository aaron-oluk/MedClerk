@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Dashboard') }}
    </h2>
@endsection

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <p class="text-sm text-gray-500">
                {{ __('Welcome back, :name.', ['name' => Auth::user()->name]) }}
            </p>

            <!-- Student profile summary -->
            @isset($profile)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('Your profile') }}</h3>

                    <dl class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3 text-sm">
                        <div>
                            <dt class="text-gray-500">{{ __('Name') }}</dt>
                            <dd class="mt-1 font-medium text-gray-900">{{ $profile['name'] }}</dd>
                        </div>
                        <div>
                            <dt class="text-gray-500">{{ __('Registration number') }}</dt>
                            <dd class="mt-1 font-medium text-gray-900">{{ $profile['registration_number'] ?? 'Not set' }}</dd>
                        </div>
                        <div>
                            <dt class="text-gray-500">{{ __('Programme') }}</dt>
                            <dd class="mt-1 font-medium text-gray-900">{{ $profile['programme'] ?? 'Not set' }}</dd>
                        </div>
                        <div>
                            <dt class="text-gray-500">{{ __('University') }}</dt>
                            <dd class="mt-1 font-medium text-gray-900">{{ $profile['institution'] ?? 'Not set' }}</dd>
                        </div>
                        <div>
                            <dt class="text-gray-500">{{ __('Current placement') }}</dt>
                            <dd class="mt-1 font-medium text-gray-900">{{ $profile['placement'] ?? 'Not set' }}</dd>
                        </div>
                    </dl>
                </div>
            @endisset

            <!-- Stat cards -->
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ($stats as $stat)
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <p class="text-sm font-medium text-gray-500">{{ $stat['label'] }}</p>
                        <p class="mt-2 text-2xl font-semibold text-gray-900">{{ $stat['value'] }}</p>
                    </div>
                @endforeach
            </div>

            <!-- Institution breakdown (superadmin only) -->
            @if ($institutionBreakdown->isNotEmpty())
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('Institutions') }}</h3>

                        <table class="w-full text-left text-sm">
                            <thead>
                                <tr class="border-b border-gray-200">
                                    <th class="py-2">{{ __('Name') }}</th>
                                    <th class="py-2">{{ __('Students') }}</th>
                                    <th class="py-2">{{ __('Lecturers') }}</th>
                                    <th class="py-2">{{ __('Active rotations') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($institutionBreakdown as $institution)
                                    <tr class="border-b border-gray-100">
                                        <td class="py-2">{{ $institution->name }}</td>
                                        <td class="py-2">{{ $institution->student_count }}</td>
                                        <td class="py-2">{{ $institution->lecturer_count }}</td>
                                        <td class="py-2">{{ $institution->active_rotation_count }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            <!-- Recent logbook entries -->
            @if ($recentLogbookEntries->isNotEmpty())
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('Recent logbook entries') }}</h3>

                        <table class="w-full text-left text-sm">
                            <thead>
                                <tr class="border-b border-gray-200">
                                    <th class="py-2">{{ __('Date') }}</th>
                                    @unless (Auth::user()->isStudent())
                                        <th class="py-2">{{ __('Student') }}</th>
                                    @endunless
                                    <th class="py-2">{{ __('Rotation') }}</th>
                                    <th class="py-2">{{ __('Notes') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($recentLogbookEntries as $entry)
                                    <tr class="border-b border-gray-100">
                                        <td class="py-2 whitespace-nowrap">{{ $entry->encounter_date->format('d M Y') }}</td>
                                        @unless (Auth::user()->isStudent())
                                            <td class="py-2">{{ $entry->student->name }}</td>
                                        @endunless
                                        <td class="py-2">{{ $entry->rotation->name }}</td>
                                        <td class="py-2 text-gray-500">{{ $entry->notes ? \Illuminate\Support\Str::limit($entry->notes, 60) : __('No notes') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            <!-- Recent assessments -->
            @if ($recentAssessments->isNotEmpty())
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('Recent assessments') }}</h3>

                        <table class="w-full text-left text-sm">
                            <thead>
                                <tr class="border-b border-gray-200">
                                    <th class="py-2">{{ __('Date') }}</th>
                                    <th class="py-2">{{ __('Student') }}</th>
                                    <th class="py-2">{{ __('Skill') }}</th>
                                    <th class="py-2">{{ __('Score') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($recentAssessments as $assessment)
                                    <tr class="border-b border-gray-100">
                                        <td class="py-2 whitespace-nowrap">{{ $assessment->assessed_at->format('d M Y') }}</td>
                                        <td class="py-2">{{ $assessment->student->name }}</td>
                                        <td class="py-2">{{ $assessment->skill->name }}</td>
                                        <td class="py-2">{{ $assessment->score + 0 }} / {{ $assessment->max_score + 0 }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            <!-- Recent feedback -->
            @if ($recentFeedback->isNotEmpty())
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('Recent feedback') }}</h3>

                        <div class="space-y-4">
                            @foreach ($recentFeedback as $feedback)
                                <div class="border-b border-gray-100 pb-4 last:border-b-0 last:pb-0">
                                    <p class="text-sm text-gray-500">
                                        {{ __('From :name on :date', ['name' => $feedback->givenBy->name, 'date' => $feedback->created_at->format('d M Y')]) }}
                                    </p>
                                    @if ($feedback->strengths)
                                        <p class="mt-1 text-sm text-gray-900"><span class="font-medium">{{ __('Strengths') }}:</span> {{ $feedback->strengths }}</p>
                                    @endif
                                    @if ($feedback->areas_to_improve)
                                        <p class="mt-1 text-sm text-gray-900"><span class="font-medium">{{ __('Areas to improve') }}:</span> {{ $feedback->areas_to_improve }}</p>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            @if ($institutionBreakdown->isEmpty() && $recentLogbookEntries->isEmpty() && $recentAssessments->isEmpty() && $recentFeedback->isEmpty())
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 text-sm text-gray-500">
                    {{ __('Nothing to show yet. Activity will appear here once logbook entries, assessments and feedback are recorded.') }}
                </div>
            @endif
        </div>
    </div>
@endsection
