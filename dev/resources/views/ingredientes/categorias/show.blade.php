<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between align-top">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Ver Categoria de ingredientes') }}
            </h2>
            <a class="boton boton--azul" href='{{ route('ingredientes.categoria.create') }}'>Nuevo</a>
        </div>
    </x-slot>

    <x-content>
        En construcción
        <?php echo ""; // TODO: Hacer la vista para mostrar categoria única?>
    </x-content>
</x-app-layout>