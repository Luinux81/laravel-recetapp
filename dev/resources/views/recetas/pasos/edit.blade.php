<x-app-layout>
<x-slot name="header">
    <div class="flex flex-row justify-between">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Editar paso en receta {{ $receta->nombre }}
        </h2>
        <a href="{{ route('recetas.edit',['receta'=>$receta->id])}}" class="boton boton--rojo">Cancelar</a>
    </div>
</x-slot>
<x-content>
    <form method="post" action="{{ route('recetas.paso.update',['receta'=>$receta->id, 'paso'=>$paso->id]) }}" class="flex flex-col">
        @csrf
        @method('PUT')
        <x-form.input-text
            nombre="orden" 
            titulo="orden" 
            tipo="number" 
            valor="{{ old('orden') ? old('orden') : $paso->orden }}"
        >
        </x-form.input-text>

        <x-form.input-text
            nombre="texto" 
            titulo="texto" 
            tipo="text" 
            valor="{{ old('texto') ? old('texto') : $paso->texto }}"
        >
        </x-form.input-text>

        <button type="submit" class="boton boton--azul">Añadir</button>
    </form>
</x-content>
</x-app-layout>