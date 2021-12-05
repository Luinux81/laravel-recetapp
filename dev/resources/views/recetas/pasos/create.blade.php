<x-app-layout>
<x-slot name="header">
    <div class="flex flex-row justify-between">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Añadir paso a receta {{ $receta->nombre }}
        </h2>
        <div class="flex gap-5">
            <button class="boton boton--azul" onclick="document.getElementById('create_form_paso').submit();">Añadir</button>
            <a href="{{ route('recetas.edit',['receta'=>$receta->id])}}" class="boton boton--rojo">Cancelar</a>
        </div>
        
    </div>
</x-slot>
<x-content>
    <form id="create_form_paso" method="post" action="{{ route('recetas.paso.store',['receta'=>$receta->id]) }}" class="flex flex-col">
        @csrf
        <x-form.input-text
            nombre="orden" 
            titulo="orden" 
            tipo="number" 
            min="1"
            max="{{ $receta->pasos()->count() + 1 }}"
            valor="{{ $receta->pasos()->count() + 1 }}"
        >
        </x-form.input-text>

        <x-form.input-text
            nombre="texto" 
            titulo="texto" 
            tipo="text" 
            valor=""
        >
        </x-form.input-text>

        <div class="flex gap-5 justify-center m-6">
            <button type="submit" class="boton boton--azul">Añadir</button>
            <a href="{{ route('recetas.edit',['receta'=>$receta->id])}}" class="boton boton--rojo">Cancelar</a>
        </div>
    </form>
</x-content>
</x-app-layout>