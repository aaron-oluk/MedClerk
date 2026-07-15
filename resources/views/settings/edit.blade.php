@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Settings') }}
    </h2>
@endsection

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <section>
                        <header>
                            <h2 class="text-lg font-medium text-gray-900">{{ __('Notifications') }}</h2>
                            <p class="mt-1 text-sm text-gray-600">{{ __('Choose whether MedClerk can email you about your account.') }}</p>
                        </header>

                        <form method="post" action="{{ route('settings.update') }}" class="mt-6 space-y-6">
                            @csrf
                            @method('patch')

                            <div class="flex items-start gap-3">
                                <input id="email_notifications_enabled" name="email_notifications_enabled" type="checkbox" value="1"
                                       {{ old('email_notifications_enabled', $user->email_notifications_enabled) ? 'checked' : '' }}
                                       class="mt-1 rounded border-gray-300 text-teal-600 shadow-sm focus:ring-teal-500">
                                <label for="email_notifications_enabled" class="text-sm text-gray-700">
                                    {{ __('Email me about updates to my rotations, logbook and feedback') }}
                                </label>
                            </div>

                            <div class="flex items-center gap-4">
                                <button type="submit" class="inline-flex items-center justify-center px-4 py-2.5 bg-teal-600 border border-transparent rounded-lg font-semibold text-sm text-white hover:bg-teal-500 focus:bg-teal-500 active:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    {{ __('Save') }}
                                </button>

                                @if (session('status') === 'settings-updated')
                                    <p
                                        x-data="{ show: true }"
                                        x-show="show"
                                        x-transition
                                        x-init="setTimeout(() => show = false, 2000)"
                                        class="text-sm text-gray-600"
                                    >{{ __('Saved.') }}</p>
                                @endif
                            </div>
                        </form>
                    </section>
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <section>
                        <header>
                            <h2 class="text-lg font-medium text-gray-900">{{ __('Rate us') }}</h2>
                            <p class="mt-1 text-sm text-gray-600">{{ __('Coming soon.') }}</p>
                        </header>
                    </section>
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <section>
                        <header>
                            <h2 class="text-lg font-medium text-gray-900">{{ __('Send feedback') }}</h2>
                            <p class="mt-1 text-sm text-gray-600">{{ __('Tell us what\'s working, and what isn\'t.') }}</p>
                        </header>

                        <div class="mt-6">
                            <a href="mailto:{{ config('mail.from.address') }}?subject={{ urlencode('MedClerk feedback') }}"
                               class="inline-flex items-center justify-center px-4 py-2.5 bg-teal-600 border border-transparent rounded-lg font-semibold text-sm text-white hover:bg-teal-500 focus:bg-teal-500 active:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                {{ __('Send feedback') }}
                            </a>
                        </div>
                    </section>
                </div>
            </div>

        </div>
    </div>
@endsection
