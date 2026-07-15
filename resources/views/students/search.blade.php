@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Find a student') }}
    </h2>
@endsection

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <form method="GET" action="{{ route('students.search') }}" class="flex gap-3">
                <input type="text" name="q" value="{{ $query }}" placeholder="{{ __('Search by registration number') }}"
                       class="block w-full max-w-sm border-gray-300 focus:border-teal-500 focus:ring-teal-500 rounded-lg shadow-sm text-sm">
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg font-medium text-sm text-gray-700 hover:bg-gray-50">
                    {{ __('Search') }}
                </button>
            </form>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @if ($students->isEmpty())
                        <p class="text-sm text-gray-500">
                            {{ $query !== '' ? __('No students match that registration number.') : __('Enter a registration number to find a student.') }}
                        </p>
                    @else
                        <table class="w-full text-left text-sm">
                            <thead>
                                <tr class="border-b border-gray-200">
                                    <th class="py-2">{{ __('Name') }}</th>
                                    <th class="py-2">{{ __('Registration number') }}</th>
                                    <th class="py-2">{{ __('University') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($students as $student)
                                    <tr class="border-b border-gray-100">
                                        <td class="py-2">
                                            <a href="{{ route('students.show', $student) }}" class="text-teal-700 hover:text-teal-900 font-medium">
                                                {{ $student->name }}
                                            </a>
                                        </td>
                                        <td class="py-2">{{ $student->student_number ?? 'Not set' }}</td>
                                        <td class="py-2">{{ $student->institution?->name ?? 'Not set' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <div class="mt-4">
                            {{ $students->links() }}
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
@endsection
