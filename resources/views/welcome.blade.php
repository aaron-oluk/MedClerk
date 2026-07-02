<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'MedClerk') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-gray-50 text-gray-900">
        <div class="min-h-screen flex flex-col items-center justify-center px-6">
            <h1 class="text-3xl font-semibold">{{ config('app.name', 'MedClerk') }}</h1>
            <p class="mt-2 text-gray-600 text-center max-w-xl">
                A clinical education and competency assessment platform for medical students.
            </p>

            <div class="mt-8 flex gap-4">
                @auth
                    <a href="{{ route('dashboard') }}" class="px-4 py-2 rounded-md bg-gray-900 text-white text-sm font-medium">
                        {{ __('Dashboard') }}
                    </a>
                @else
                    <a href="{{ route('login') }}" class="px-4 py-2 rounded-md bg-gray-900 text-white text-sm font-medium">
                        {{ __('Log in') }}
                    </a>

                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="px-4 py-2 rounded-md border border-gray-300 text-sm font-medium">
                            {{ __('Register') }}
                        </a>
                    @endif
                @endauth
            </div>
        </div>
    </body>
</html>
