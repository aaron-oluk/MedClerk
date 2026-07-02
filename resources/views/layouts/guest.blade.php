<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'MedClerk') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased overflow-x-hidden">
        <div class="min-h-screen flex">
            <div class="hidden lg:flex lg:w-1/2 flex-col justify-between bg-slate-900 text-white px-12 py-12 xl:px-16">
                <a href="/" class="text-xl font-semibold tracking-tight">
                    MedClerk
                </a>

                <div class="max-w-sm">
                    <h1 class="text-3xl font-semibold leading-tight">
                        Clinical education, assessed with confidence.
                    </h1>
                    <p class="mt-4 text-slate-300 leading-relaxed">
                        One shared home for the clinical signs library, the digital logbook, curriculum aligned assessments and structured feedback across your program.
                    </p>

                    <ul class="mt-8 space-y-3 text-sm text-slate-300">
                        <li class="flex items-start gap-3">
                            <span class="mt-1.5 h-1.5 w-1.5 rounded-full bg-teal-400 shrink-0"></span>
                            <span>Logbook entries recorded offline, synced once you are back online</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="mt-1.5 h-1.5 w-1.5 rounded-full bg-teal-400 shrink-0"></span>
                            <span>Scoring tied to skills, rotations and assessors</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="mt-1.5 h-1.5 w-1.5 rounded-full bg-teal-400 shrink-0"></span>
                            <span>Built for use across multiple institutions and programs</span>
                        </li>
                    </ul>
                </div>

                <p class="text-xs text-slate-500">
                    &copy; {{ date('Y') }} MedClerk. All rights reserved.
                </p>
            </div>

            <div class="flex flex-1 min-w-0 flex-col justify-center px-6 py-12 sm:px-12 lg:px-16">
                <div class="w-full max-w-sm mx-auto">
                    <a href="/" class="flex lg:hidden items-center justify-center mb-10">
                        <span class="text-xl font-semibold tracking-tight text-gray-900">MedClerk</span>
                    </a>

                    @yield('content')
                </div>
            </div>
        </div>
    </body>
</html>
