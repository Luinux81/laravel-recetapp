<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                Esto es la vista de ingredientes

                <table>
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Descripcion</th>
                            <th>Calorias</th>
                            <th>Imagen</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach ($ingredientes as $i)
                        <tr>
                            <td>{{$i->nombre}}</td>
                            <td>{{$i->descripcion}}</td>
                            <td>{{$i->calorias}}</td>
                            <td>{{$i->imagen}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

            </div>
        </div>
    </div>
</x-app-layout>