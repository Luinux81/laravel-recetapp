<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-row justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Ver Receta') }}
            </h2>
            <a href="{{ route('recetas.index') }}" class="boton boton--rojo">Volver</a>
        </div>
    </x-slot>

    <x-content>
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $receta->nombre }}</h2>
        <p>{{ $receta->descripcion }}</p>

        <h2 class="font-semibold text-xl text-gray-800 leading-tight mt-3">Ingredientes:</h2>
        <ul>
            @foreach ($receta->ingredientes as $i)
                <li>{{ $i->pivot->cantidad }} {{$i->pivot->unidad_medida}} {{ $i->nombre }}</li>
            @endforeach
        </ul>

        <h2 class="font-semibold text-xl text-gray-800 leading-tight mt-3">Pasos:</h2>
        <ol>
            @foreach ($receta->pasos()->orderBy('orden')->get() as $paso)
                <li>{{ $paso->orden }} {{ $paso->texto }}</li>
            @endforeach
        </ol>
    </x-content>

</x-app-layout>