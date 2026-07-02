@extends('layouts.guest')

@section('content')
    <div class="mb-4 text-sm text-gray-600">
        {{ __('This is a secure area of the application. Please confirm your password before continuing.') }}
    </div>

    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf

        <!-- Password -->
        <div>
            <label for="password" class="block font-medium text-sm text-gray-700">{{ __('Password') }}</label>
            <input id="password" type="password" name="password" required autocomplete="current-password"
                   class="block mt-1 w-full border-gray-300 focus:border-teal-500 focus:ring-teal-500 rounded-lg shadow-sm">
            @error('password')
                <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex justify-end mt-4">
            <button type="submit" class="inline-flex items-center justify-center px-4 py-2.5 bg-teal-600 border border-transparent rounded-lg font-semibold text-sm text-white hover:bg-teal-500 focus:bg-teal-500 active:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-offset-2 transition ease-in-out duration-150">
                {{ __('Confirm') }}
            </button>
        </div>
    </form>
@endsection
