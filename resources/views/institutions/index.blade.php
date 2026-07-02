<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Institutions') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <table class="w-full text-left text-sm">
                        <thead>
                            <tr class="border-b border-gray-200">
                                <th class="py-2">Name</th>
                                <th class="py-2">Country</th>
                                <th class="py-2">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($institutions as $institution)
                                <tr class="border-b border-gray-100">
                                    <td class="py-2">{{ $institution->name }}</td>
                                    <td class="py-2">{{ $institution->country ?? 'Not set' }}</td>
                                    <td class="py-2">{{ $institution->status }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="py-4 text-gray-500">No institutions yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <form method="POST" action="{{ route('institutions.store') }}" class="p-6 space-y-6">
                    @csrf

                    <h3 class="text-lg font-medium text-gray-900">
                        {{ __('Add an institution') }}
                    </h3>

                    <div>
                        <x-input-label for="name" :value="__('Name')" />
                        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name')" required autofocus />
                        <x-input-error class="mt-2" :messages="$errors->get('name')" />
                    </div>

                    <div>
                        <x-input-label for="country" :value="__('Country')" />
                        <x-text-input id="country" name="country" type="text" class="mt-1 block w-full" :value="old('country')" />
                        <x-input-error class="mt-2" :messages="$errors->get('country')" />
                    </div>

                    <x-primary-button>{{ __('Save') }}</x-primary-button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
