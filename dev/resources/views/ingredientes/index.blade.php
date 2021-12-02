<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-row justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Ingredientes') }}
            </h2>
            <a href="{{ route('ingredientes.create') }}" class="boton boton--azul">Nuevo</a>
        </div>
    </x-slot>

    <x-content>
        <livewire:ingredientes-table/>
    </x-content>

    @push('custom-scripts')

    <script>
        function filtrarPorCategoria(event){
            location.href="{{ route('ingredientes.index') }}?filtro=categoria&valor_filtro="+event.target.value;
        }
    </script>
    @endpush

</x-app-layout>