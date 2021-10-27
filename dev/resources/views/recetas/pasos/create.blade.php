<x-app-layout>
<x-slot name="header">
    <div class="flex flex-row justify-between">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Añadir paso a receta {{ $receta->nombre }}
        </h2>
        <a href="{{ route('recetas.edit',['receta'=>$receta->id])}}" class="boton boton--rojo">Cancelar</a>
    </div>
</x-slot>
<x-content>
    <form method="post" action="{{ route('recetas.paso.store',['receta'=>$receta->id]) }}" class="flex flex-col">
        @csrf
        <x-form.input-text
            nombre="orden" 
            titulo="orden" 
            tipo="number" 
            valor=""
        >
        </x-form.input-text>

        <x-form.input-text
            nombre="texto" 
            titulo="texto" 
            tipo="text" 
            valor=""
        >
        </x-form.input-text>

        <button type="submit" class="boton boton--azul">Añadir</button>
    </form>
</x-content>
</x-app-layout>