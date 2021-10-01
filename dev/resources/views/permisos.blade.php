<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Roles y Permisos') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <input type="hidden" class="my-6 font-bold">
                <x-tabs.tabs data-id="tabs-rp" activa="roles">

                    <x-tabs.tab nombre="roles" titulo="Roles" icono="fas-user-tag">
                        @livewire('permisos-controller',["tipo"=>"rol"])
                    </x-tabs.tab>

                    <x-tabs.tab nombre="permisos" titulo="Permisos" icono="fas-key">
                        @livewire('permisos-controller',["tipo"=>"permiso"])
                    </x-tabs.tab>
                    
                </x-tabs.tabs>

            </div>
        </div>
    </div>
</x-app-layout>