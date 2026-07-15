@php
    $navClasses = fn ($active) => 'flex items-center gap-3 rounded-md px-3 py-2 text-sm font-medium transition '
        .($active ? 'bg-teal-50 text-teal-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900');
@endphp

<div class="flex h-full flex-col">
    <div class="flex h-16 items-center px-6 border-b border-gray-100">
        <a href="{{ route('dashboard') }}" class="text-lg font-semibold tracking-tight text-gray-900">
            MedClerk
        </a>
    </div>

    <nav class="flex-1 space-y-1 px-3 py-4 overflow-y-auto">
        <a href="{{ route('dashboard') }}" class="{{ $navClasses(request()->routeIs('dashboard')) }}">
            <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
            </svg>
            {{ __('Dashboard') }}
        </a>

        <a href="{{ route('rotations.index') }}" class="{{ $navClasses(request()->routeIs('rotations.*')) }}">
            <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
            </svg>
            {{ __('Rotations') }}
        </a>

        <a href="{{ route('logbook-entries.index') }}" class="{{ $navClasses(request()->routeIs('logbook-entries.*')) }}">
            <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5a1.125 1.125 0 01-1.125-1.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
            </svg>
            {{ __('Logbook') }}
        </a>

        <a href="{{ route('clinical-systems.index') }}" class="{{ $navClasses(request()->routeIs('clinical-systems.*')) }}">
            <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
            </svg>
            {{ __('Clinical Reference') }}
        </a>

        <a href="{{ route('skills.index') }}" class="{{ $navClasses(request()->routeIs('skills.*')) }}">
            <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.627 48.627 0 0112 20.904a48.627 48.627 0 018.232-4.41 60.46 60.46 0 00-.491-6.347M4.26 10.147a50.57 50.57 0 00-2.658-.813A59.905 59.905 0 0112 3.493a59.902 59.902 0 0110.399 5.841c-.896.248-1.783.52-2.658.814M4.26 10.147a50.697 50.697 0 017.74-3.342M6.75 15a.75.75 0 100-1.5.75.75 0 000 1.5zm0 0v-3.675A55.378 55.378 0 0112 8.443" />
            </svg>
            {{ __('Skills') }}
        </a>

        <a href="{{ route('assessments.index') }}" class="{{ $navClasses(request()->routeIs('assessments.*')) }}">
            <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            {{ __('Assessments') }}
        </a>

        <a href="{{ route('feedback.index') }}" class="{{ $navClasses(request()->routeIs('feedback.*')) }}">
            <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H8.25m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H12m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 01-2.555-.337A5.972 5.972 0 015.41 20.97a5.969 5.969 0 01-.474-.065 4.48 4.48 0 00.978-2.025c.09-.457-.133-.901-.467-1.226C3.93 16.178 3 14.189 3 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25z" />
            </svg>
            {{ __('Feedback') }}
        </a>

        @if (Auth::user()->role === \App\Models\User::ROLE_LECTURER)
            <a href="{{ route('students.search') }}" class="{{ $navClasses(request()->routeIs('students.*')) }}">
                <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                </svg>
                {{ __('Find a student') }}
            </a>
        @endif

        @if (in_array(Auth::user()->role, [\App\Models\User::ROLE_ADMIN, \App\Models\User::ROLE_SUPERADMIN]))
            <a href="{{ route('users.index') }}" class="{{ $navClasses(request()->routeIs('users.*')) }}">
                <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                </svg>
                {{ __('Manage users') }}
            </a>

            <a href="{{ route('programs.index') }}" class="{{ $navClasses(request()->routeIs('programs.*')) }}">
                <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 14.15v4.25c0 1.094-.787 2.036-1.872 2.18-2.087.277-4.216.42-6.378.42s-4.291-.143-6.378-.42c-1.085-.144-1.872-1.086-1.872-2.18v-4.25m16.5 0a2.18 2.18 0 00.75-1.661V8.706c0-1.081-.768-2.015-1.837-2.175a48.114 48.114 0 00-3.413-.387m4.5 8.006c-.194.165-.42.295-.673.38A23.978 23.978 0 0112 15.75c-2.648 0-5.195-.429-7.577-1.22a2.016 2.016 0 01-.673-.38m0 0A2.18 2.18 0 013 12.489V8.706c0-1.081.768-2.015 1.837-2.175a48.111 48.111 0 013.413-.387m7.5 0V5.25A2.25 2.25 0 0013.5 3h-3a2.25 2.25 0 00-2.25 2.25v.894m7.5 0a48.667 48.667 0 00-7.5 0" />
                </svg>
                {{ __('Programs') }}
            </a>

            <a href="{{ route('institutions.index') }}" class="{{ $navClasses(request()->routeIs('institutions.*')) }}">
                <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21" />
                </svg>
                {{ __('Institutions') }}
            </a>
        @endif
    </nav>

    <div class="border-t border-gray-100 p-3">
        <div class="px-3 py-2">
            <p class="text-sm font-medium text-gray-900">{{ Auth::user()->name }}</p>
            <p class="text-xs text-gray-500">{{ Auth::user()->email }}</p>
        </div>

        <a href="{{ route('profile.edit') }}"
           class="block rounded-md px-3 py-2 text-sm text-gray-600 hover:bg-gray-50 hover:text-gray-900 transition {{ request()->routeIs('profile.edit') ? 'bg-teal-50 text-teal-700' : '' }}">
            {{ __('Profile') }}
        </a>

        <a href="{{ route('settings.edit') }}"
           class="block rounded-md px-3 py-2 text-sm text-gray-600 hover:bg-gray-50 hover:text-gray-900 transition {{ request()->routeIs('settings.edit') ? 'bg-teal-50 text-teal-700' : '' }}">
            {{ __('Settings') }}
        </a>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <a href="{{ route('logout') }}"
               onclick="event.preventDefault(); this.closest('form').submit();"
               class="block rounded-md px-3 py-2 text-sm text-gray-600 hover:bg-gray-50 hover:text-gray-900 transition cursor-pointer">
                {{ __('Log Out') }}
            </a>
        </form>
    </div>
</div>
