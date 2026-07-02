@extends('layouts.guest')

@section('content')
    <div class="mb-8">
        <h2 class="text-2xl font-semibold text-gray-900">{{ __('Welcome back') }}</h2>
        <p class="mt-1 text-sm text-gray-500">{{ __('Sign in to continue to your dashboard.') }}</p>
    </div>

    @if (session('status'))
        <div class="rounded-lg bg-teal-50 border border-teal-200 px-4 py-3 text-sm font-medium text-teal-700 mb-6">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <div>
            <label for="email" class="block font-medium text-sm text-gray-700">{{ __('Email') }}</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="you@institution.edu"
                   class="block mt-1 w-full border-gray-300 focus:border-teal-500 focus:ring-teal-500 rounded-lg shadow-sm">
            @error('email')
                <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <div class="flex flex-wrap items-center justify-between gap-x-2 gap-y-1">
                <label for="password" class="block font-medium text-sm text-gray-700">{{ __('Password') }}</label>

                @if (Route::has('password.request'))
                    <a class="text-sm text-teal-600 hover:text-teal-500" href="{{ route('password.request') }}">
                        {{ __('Forgot password?') }}
                    </a>
                @endif
            </div>

            <input id="password" type="password" name="password" required autocomplete="current-password" placeholder="••••••••"
                   class="block mt-1 w-full border-gray-300 focus:border-teal-500 focus:ring-teal-500 rounded-lg shadow-sm">
            @error('password')
                <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
            @enderror
        </div>

        <label for="remember_me" class="flex items-center gap-2">
            <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-teal-600 shadow-sm focus:ring-teal-500" name="remember">
            <span class="text-sm text-gray-600">{{ __('Remember me') }}</span>
        </label>

        <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2.5 bg-teal-600 border border-transparent rounded-lg font-semibold text-sm text-white hover:bg-teal-500 focus:bg-teal-500 active:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-offset-2 transition ease-in-out duration-150">
            {{ __('Log in') }}
        </button>

        @if (Route::has('register'))
            <p class="text-center text-sm text-gray-500">
                {{ __("Don't have an account?") }}
                <a href="{{ route('register') }}" class="font-medium text-teal-600 hover:text-teal-500">
                    {{ __('Register') }}
                </a>
            </p>
        @endif
    </form>
@endsection
