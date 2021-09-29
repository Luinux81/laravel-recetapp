<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                {{-- <x-jet-welcome /> --}}

                <x-tabs.tabs data-id="tabs-1" activa="tab1">
                    <x-tabs.tab nombre="tab1" titulo="La Tab 1">
                        Esto es la tab
                    </x-tabs.tab>
                    <x-tabs.tab nombre="tab2" titulo="La Tab 2">
                        Esto es la tab 2
                    </x-tabs.tab>
                </x-tabs.tabs>

            </div>
        </div>
    </div>
</x-app-layout>
