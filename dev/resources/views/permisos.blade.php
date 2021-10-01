<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Roles y Permisos') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                
                <x-tabs.tabs data-id="tabs-rp" activa="roles">
                    <x-tabs.tab nombre="roles" titulo="Roles" icono="fas-user-tag">
                        Esto es la tab de roles
                    </x-tabs.tab>
                    <x-tabs.tab nombre="permisos" titulo="Permisos" icono="fas-key">
                        Esto es la tab de permisos
                    </x-tabs.tab>
                </x-tabs.tabs>

            </div>
        </div>
    </div>
</x-app-layout>