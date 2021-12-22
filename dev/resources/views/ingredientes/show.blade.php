<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-row justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Ver Ingrediente') }}
            </h2>
            <a href="{{ route('ingredientes.index') }}" class="boton boton--rojo">Volver</a>
        </div>
    </x-slot>

    <x-content>
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $ingrediente->nombre }}</h2>
        <p>{{ $ingrediente->descripcion }}</p>

        <h2 class="font-semibold text-xl text-gray-800 leading-tight mt-3">Info nutricional:</h2>
        <p> En construccion</p>
        <?php echo ""; //TODO: Terminar vista ingrediente show?>
    </x-content>

</x-app-layout>