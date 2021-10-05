<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between align-top">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Categorias de ingredientes') }}
            </h2>
            <a class="boton boton--azul" href='{{ route('ingredientes.categoria.create') }}'>Nuevo</a>
        </div>
    </x-slot>

    <x-content>
        <table>
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Descripcion</th>
                    <th>Categoria Superior</th>
                </tr>
            </thead>
            <tbody>
            @foreach ($categorias as $c)
                <tr>
                    <td>{{$c->nombre}}</td>
                    <td>{{$c->descripcion}}</td>
                    <td>
                        @if (!empty($c->catParent_id))
                            {{ \App\Models\CategoriaIngrediente::find($c->catParent_id)->nombre }}
                        @endif
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </x-content>
</x-app-layout>