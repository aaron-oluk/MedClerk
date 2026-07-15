@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ $student->name }}
    </h2>
@endsection

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <a href="{{ route('students.search') }}" class="text-sm text-teal-700 hover:text-teal-900">
                &larr; {{ __('Back to search') }}
            </a>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('Student profile') }}</h3>

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
                        <dt class="text-gray-500">{{ __('Email address') }}</dt>
                        <dd class="mt-1 font-medium text-gray-900">{{ $profile['email'] }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500">{{ __('University') }}</dt>
                        <dd class="mt-1 font-medium text-gray-900">{{ $profile['institution'] ?? 'Not set' }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500">{{ __('Course') }}</dt>
                        <dd class="mt-1 font-medium text-gray-900">{{ $profile['programme'] ?? 'Not set' }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500">{{ __('Current placement') }}</dt>
                        <dd class="mt-1 font-medium text-gray-900">{{ $profile['placement'] ?? 'Not set' }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500">{{ __('Year of study') }}</dt>
                        <dd class="mt-1 font-medium text-gray-500 italic">{{ __('Not yet available') }}</dd>
                    </div>
                </dl>
            </div>

        </div>
    </div>
@endsection
