@extends('layouts.guest')

@section('content')
    <div class="mb-4 text-sm text-gray-600">
        {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
    </div>

    <!-- Session Status -->
    @if (session('status'))
        <div class="rounded-lg bg-teal-50 border border-teal-200 px-4 py-3 text-sm font-medium text-teal-700 mb-4">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <label for="email" class="block font-medium text-sm text-gray-700">{{ __('Email') }}</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                   class="block mt-1 w-full border-gray-300 focus:border-teal-500 focus:ring-teal-500 rounded-lg shadow-sm">
            @error('email')
                <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex items-center justify-end mt-4">
            <button type="submit" class="inline-flex items-center justify-center px-4 py-2.5 bg-teal-600 border border-transparent rounded-lg font-semibold text-sm text-white hover:bg-teal-500 focus:bg-teal-500 active:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-offset-2 transition ease-in-out duration-150">
                {{ __('Email Password Reset Link') }}
            </button>
        </div>
    </form>
@endsection
