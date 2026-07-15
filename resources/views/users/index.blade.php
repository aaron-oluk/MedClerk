@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Manage users') }}
    </h2>
@endsection

@section('content')
    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <form method="GET" action="{{ route('users.index') }}" class="flex gap-3">
                <select name="role" onchange="this.form.submit()"
                        class="block w-full max-w-xs border-gray-300 focus:border-teal-500 focus:ring-teal-500 rounded-lg shadow-sm text-sm">
                    <option value="">{{ __('All roles') }}</option>
                    @foreach (['student', 'lecturer', 'admin', 'superadmin'] as $role)
                        <option value="{{ $role }}" @selected($roleFilter === $role)>{{ ucfirst($role) }}</option>
                    @endforeach
                </select>
            </form>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <table class="w-full text-left text-sm">
                        <thead>
                            <tr class="border-b border-gray-200">
                                <th class="py-2">{{ __('Name') }}</th>
                                <th class="py-2">{{ __('Email') }}</th>
                                <th class="py-2">{{ __('Role') }}</th>
                                <th class="py-2">{{ __('Institution') }}</th>
                                <th class="py-2">{{ __('Status') }}</th>
                                <th class="py-2"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($users as $user)
                                <tr class="border-b border-gray-100">
                                    <td class="py-2">{{ $user->name }}</td>
                                    <td class="py-2">{{ $user->email }}</td>
                                    <td class="py-2">{{ ucfirst($user->role) }}</td>
                                    <td class="py-2">{{ $user->institution?->name ?? 'Not set' }}</td>
                                    <td class="py-2">
                                        @if ($user->is_active)
                                            <span class="text-teal-700">{{ __('Active') }}</span>
                                        @else
                                            <span class="text-red-600">{{ __('Inactive') }}</span>
                                        @endif
                                    </td>
                                    <td class="py-2 text-right">
                                        <a href="{{ route('users.edit', $user) }}" class="text-teal-700 hover:text-teal-900 font-medium">
                                            {{ __('Edit') }}
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-4 text-gray-500">{{ __('No users found.') }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($users->hasPages())
                    <div class="px-6 pb-6">
                        {{ $users->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
@endsection
