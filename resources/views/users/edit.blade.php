@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Edit :name', ['name' => $targetUser->name]) }}
    </h2>
@endsection

@section('content')
    <div class="py-12">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form method="POST" action="{{ route('users.update', $targetUser) }}" class="space-y-6">
                    @csrf
                    @method('patch')

                    <div>
                        <p class="block font-medium text-sm text-gray-700">{{ __('Email') }}</p>
                        <p class="mt-1 text-sm text-gray-500">{{ $targetUser->email }}</p>
                    </div>

                    <div>
                        <label for="role" class="block font-medium text-sm text-gray-700">{{ __('Role') }}</label>
                        <select id="role" name="role" required
                                class="mt-1 block w-full border-gray-300 focus:border-teal-500 focus:ring-teal-500 rounded-lg shadow-sm">
                            @foreach (['student', 'lecturer', 'admin', 'superadmin'] as $role)
                                <option value="{{ $role }}" @selected(old('role', $targetUser->role) === $role)>{{ ucfirst($role) }}</option>
                            @endforeach
                        </select>
                        @error('role')
                            <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    @if ($institutions->isNotEmpty())
                        <div>
                            <label for="institution_id" class="block font-medium text-sm text-gray-700">{{ __('Institution') }}</label>
                            <select id="institution_id" name="institution_id"
                                    class="mt-1 block w-full border-gray-300 focus:border-teal-500 focus:ring-teal-500 rounded-lg shadow-sm">
                                @foreach ($institutions as $institution)
                                    <option value="{{ $institution->id }}" @selected(old('institution_id', $targetUser->institution_id) == $institution->id)>
                                        {{ $institution->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('institution_id')
                                <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    @else
                        <div>
                            <p class="block font-medium text-sm text-gray-700">{{ __('Institution') }}</p>
                            <p class="mt-1 text-sm text-gray-500">{{ $targetUser->institution?->name ?? 'Not set' }}</p>
                        </div>
                    @endif

                    <div>
                        <label for="department_id" class="block font-medium text-sm text-gray-700">{{ __('Department') }}</label>
                        <select id="department_id" name="department_id"
                                class="mt-1 block w-full border-gray-300 focus:border-teal-500 focus:ring-teal-500 rounded-lg shadow-sm">
                            <option value="">{{ __('Not set') }}</option>
                            @foreach ($departments as $department)
                                <option value="{{ $department->id }}" @selected(old('department_id', $targetUser->department_id) == $department->id)>
                                    {{ $department->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('department_id')
                            <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-start gap-3">
                        <input id="is_active" name="is_active" type="checkbox" value="1"
                               {{ old('is_active', $targetUser->is_active) ? 'checked' : '' }}
                               class="mt-1 rounded border-gray-300 text-teal-600 shadow-sm focus:ring-teal-500">
                        <label for="is_active" class="text-sm text-gray-700">
                            {{ __('Account is active (unchecking prevents this user from logging in)') }}
                        </label>
                    </div>

                    <div class="flex items-center gap-4">
                        <button type="submit" class="inline-flex items-center justify-center px-4 py-2.5 bg-teal-600 border border-transparent rounded-lg font-semibold text-sm text-white hover:bg-teal-500 focus:bg-teal-500 active:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            {{ __('Save') }}
                        </button>

                        <a href="{{ route('users.index') }}" class="text-sm text-gray-600 hover:text-gray-900">
                            {{ __('Cancel') }}
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
