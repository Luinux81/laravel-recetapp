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
        <table>
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Descripcion</th>
                    <th>Calorias</th>
                    <th>Imagen</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
            @foreach ($ingredientes as $i)
                <tr>
                    <td>{{$i->nombre}}</td>
                    <td>{{$i->descripcion}}</td>
                    <td>{{$i->calorias}}</td>
                    <td>{{$i->imagen}}</td>
                    <td>
                        <a href="{{ route('ingredientes.edit', ['ingrediente'=>$i->id]) }}" class="boton boton--gris">Editar</a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </x-content>
</x-app-layout>