@extends('layouts.guest')

@section('content')
    <div class="mb-8">
        <h2 class="text-2xl font-semibold text-gray-900">{{ __('Create your account') }}</h2>
        <p class="mt-1 text-sm text-gray-500">{{ __('Get access to your clinical library, logbook and assessments.') }}</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-5">
        @csrf

        <div>
            <label for="name" class="block font-medium text-sm text-gray-700">{{ __('Name') }}</label>
            <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" placeholder="Full name"
                   class="block mt-1 w-full border-gray-300 focus:border-teal-500 focus:ring-teal-500 rounded-lg shadow-sm">
            @error('name')
                <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="email" class="block font-medium text-sm text-gray-700">{{ __('Email') }}</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" placeholder="you@institution.edu"
                   class="block mt-1 w-full border-gray-300 focus:border-teal-500 focus:ring-teal-500 rounded-lg shadow-sm">
            @error('email')
                <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="password" class="block font-medium text-sm text-gray-700">{{ __('Password') }}</label>
            <input id="password" type="password" name="password" required autocomplete="new-password" placeholder="••••••••"
                   class="block mt-1 w-full border-gray-300 focus:border-teal-500 focus:ring-teal-500 rounded-lg shadow-sm">
            @error('password')
                <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="password_confirmation" class="block font-medium text-sm text-gray-700">{{ __('Confirm password') }}</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="••••••••"
                   class="block mt-1 w-full border-gray-300 focus:border-teal-500 focus:ring-teal-500 rounded-lg shadow-sm">
            @error('password_confirmation')
                <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2.5 bg-teal-600 border border-transparent rounded-lg font-semibold text-sm text-white hover:bg-teal-500 focus:bg-teal-500 active:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-offset-2 transition ease-in-out duration-150">
            {{ __('Create account') }}
        </button>

        <p class="text-center text-sm text-gray-500">
            {{ __('Already registered?') }}
            <a href="{{ route('login') }}" class="font-medium text-teal-600 hover:text-teal-500">
                {{ __('Log in') }}
            </a>
        </p>
    </form>
@endsection
