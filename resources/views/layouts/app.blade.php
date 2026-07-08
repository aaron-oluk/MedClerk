<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'MedClerk') }}</title>

        <!-- PWA -->
        <link rel="manifest" href="/manifest.webmanifest">
        <link rel="apple-touch-icon" href="/apple-touch-icon.png">
        <meta name="theme-color" content="#0f172a">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
        <meta name="apple-mobile-web-app-title" content="MedClerk">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-gray-100" x-data="{ sidebarOpen: false, online: true, pendingSync: 0 }"
          x-init="
              online = navigator.onLine;
              pendingSync = window.MedClerkOfflineQueue ? window.MedClerkOfflineQueue.count() : 0;
              window.addEventListener('online', () => online = true);
              window.addEventListener('offline', () => online = false);
              window.addEventListener('medclerk:queue-updated', (e) => pendingSync = e.detail.count);
          ">
        <!-- Mobile sidebar overlay -->
        <div x-show="sidebarOpen"
             x-transition.opacity
             class="fixed inset-0 z-40 bg-gray-900/50 lg:hidden"
             @click="sidebarOpen = false"
             style="display: none;"></div>

        <!-- Mobile sidebar panel -->
        <div x-show="sidebarOpen"
             x-transition:enter="transition ease-in-out duration-300 transform"
             x-transition:enter-start="-translate-x-full"
             x-transition:enter-end="translate-x-0"
             x-transition:leave="transition ease-in-out duration-300 transform"
             x-transition:leave-start="translate-x-0"
             x-transition:leave-end="-translate-x-full"
             class="fixed inset-y-0 left-0 z-50 w-64 bg-white lg:hidden"
             style="display: none;"
             @click.outside="sidebarOpen = false">
            @include('layouts.sidebar-nav')
        </div>

        <!-- Desktop sidebar -->
        <div class="hidden lg:fixed lg:inset-y-0 lg:left-0 lg:flex lg:w-64 lg:flex-col lg:bg-white lg:border-r lg:border-gray-100">
            @include('layouts.sidebar-nav')
        </div>

        <!-- Main column -->
        <div class="lg:pl-64 flex min-h-screen flex-col">
            <!-- Mobile top bar -->
            <div class="sticky top-0 z-30 flex items-center gap-4 border-b border-gray-100 bg-white px-4 py-3 lg:hidden">
                <button @click="sidebarOpen = true" class="text-gray-500 hover:text-gray-700">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5M3.75 17.25h16.5" />
                    </svg>
                </button>
                <span class="font-semibold tracking-tight text-gray-900">MedClerk</span>
            </div>

            <!-- Offline / pending sync status -->
            <div x-show="! online || pendingSync > 0"
                 x-transition
                 class="px-4 py-2 text-center text-xs font-medium sm:px-6 lg:px-8"
                 :class="! online ? 'bg-amber-50 text-amber-800' : 'bg-teal-50 text-teal-700'"
                 style="display: none;">
                <span x-show="! online">{{ __('You are offline. Cached pages remain available and logbook entries will be saved locally.') }}</span>
                <span x-show="online && pendingSync > 0" x-text="pendingSync === 1 ? '{{ __('1 logbook entry is waiting to sync.') }}' : pendingSync + ' {{ __('logbook entries are waiting to sync.') }}'"></span>
            </div>

            <!-- Page Heading -->
            @hasSection('header')
                <header class="border-b border-gray-100 bg-white">
                    <div class="px-4 py-6 sm:px-6 lg:px-8">
                        @yield('header')
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main class="flex-1">
                @yield('content')
            </main>
        </div>
    </body>
</html>
