@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Rotations') }}
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
                                <th class="py-2">{{ __('Name') }}</th>
                                <th class="py-2">{{ __('Student') }}</th>
                                <th class="py-2">{{ __('Department') }}</th>
                                <th class="py-2">{{ __('Supervisor') }}</th>
                                <th class="py-2">{{ __('Dates') }}</th>
                                <th class="py-2">{{ __('Status') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($rotations as $rotation)
                                <tr class="border-b border-gray-100">
                                    <td class="py-2">{{ $rotation->name }}</td>
                                    <td class="py-2">{{ $rotation->student->name }}</td>
                                    <td class="py-2">{{ $rotation->department->name }}</td>
                                    <td class="py-2">{{ $rotation->supervisor->name ?? 'Not assigned' }}</td>
                                    <td class="py-2 whitespace-nowrap">
                                        {{ $rotation->start_date->format('d M Y') }}
                                        @if ($rotation->end_date)
                                            {{ __('to') }} {{ $rotation->end_date->format('d M Y') }}
                                        @endif
                                    </td>
                                    <td class="py-2">
                                        <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium {{ $rotation->status === 'active' ? 'bg-teal-50 text-teal-700' : 'bg-gray-100 text-gray-600' }}">
                                            {{ ucfirst($rotation->status) }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-4 text-gray-500">{{ __('No rotations yet.') }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($rotations->hasPages())
                    <div class="px-6 pb-6">
                        {{ $rotations->links() }}
                    </div>
                @endif
            </div>

            @if ($canCreate)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <form method="POST" action="{{ route('rotations.store') }}" class="p-6 space-y-6">
                        @csrf

                        <h3 class="text-lg font-medium text-gray-900">
                            {{ __('Add a rotation') }}
                        </h3>

                        @if ($institutions->count() > 1)
                            <div>
                                <label for="institution_id" class="block font-medium text-sm text-gray-700">{{ __('Institution') }}</label>
                                <select id="institution_id" name="institution_id" required
                                        class="mt-1 block w-full border-gray-300 focus:border-teal-500 focus:ring-teal-500 rounded-lg shadow-sm">
                                    @foreach ($institutions as $institution)
                                        <option value="{{ $institution->id }}" @selected(old('institution_id') == $institution->id)>{{ $institution->name }}</option>
                                    @endforeach
                                </select>
                                @error('institution_id')
                                    <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        @else
                            <input type="hidden" name="institution_id" value="{{ $institutions->first()?->id }}">
                        @endif

                        <div>
                            <label for="name" class="block font-medium text-sm text-gray-700">{{ __('Rotation name') }}</label>
                            <input id="name" name="name" type="text" value="{{ old('name') }}" required placeholder="e.g. Internal Medicine Clerkship"
                                   class="mt-1 block w-full border-gray-300 focus:border-teal-500 focus:ring-teal-500 rounded-lg shadow-sm">
                            @error('name')
                                <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <div>
                                <label for="department_id" class="block font-medium text-sm text-gray-700">{{ __('Department') }}</label>
                                <select id="department_id" name="department_id" required
                                        class="mt-1 block w-full border-gray-300 focus:border-teal-500 focus:ring-teal-500 rounded-lg shadow-sm">
                                    @foreach ($departments as $department)
                                        <option value="{{ $department->id }}" @selected(old('department_id') == $department->id)>{{ $department->name }}</option>
                                    @endforeach
                                </select>
                                @error('department_id')
                                    <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="status" class="block font-medium text-sm text-gray-700">{{ __('Status') }}</label>
                                <select id="status" name="status"
                                        class="mt-1 block w-full border-gray-300 focus:border-teal-500 focus:ring-teal-500 rounded-lg shadow-sm">
                                    <option value="scheduled" @selected(old('status') === 'scheduled')>{{ __('Scheduled') }}</option>
                                    <option value="active" @selected(old('status', 'active') === 'active')>{{ __('Active') }}</option>
                                    <option value="completed" @selected(old('status') === 'completed')>{{ __('Completed') }}</option>
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <div>
                                <label for="student_id" class="block font-medium text-sm text-gray-700">{{ __('Student') }}</label>
                                <select id="student_id" name="student_id" required
                                        class="mt-1 block w-full border-gray-300 focus:border-teal-500 focus:ring-teal-500 rounded-lg shadow-sm">
                                    <option value="">{{ __('Select a student') }}</option>
                                    @foreach ($students as $student)
                                        <option value="{{ $student->id }}" @selected(old('student_id') == $student->id)>{{ $student->name }}</option>
                                    @endforeach
                                </select>
                                @error('student_id')
                                    <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="supervisor_id" class="block font-medium text-sm text-gray-700">{{ __('Supervisor') }}</label>
                                <select id="supervisor_id" name="supervisor_id"
                                        class="mt-1 block w-full border-gray-300 focus:border-teal-500 focus:ring-teal-500 rounded-lg shadow-sm">
                                    <option value="">{{ __('Not assigned') }}</option>
                                    @foreach ($supervisors as $supervisor)
                                        <option value="{{ $supervisor->id }}" @selected(old('supervisor_id') == $supervisor->id)>{{ $supervisor->name }}</option>
                                    @endforeach
                                </select>
                                @error('supervisor_id')
                                    <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                                @enderror
                            </div>
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
            @endif
        </div>
    </div>
@endsection
