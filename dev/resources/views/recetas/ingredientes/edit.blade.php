<x-app-layout>
<x-slot name="header">
    <h2>Editar ingrediente en receta {{ $receta->nombre }}</h2>
    <a href="" class="boton boton--rojo">Cancelar</a>
</x-slot>
<x-content>
    <form method="post" action="{{ route('recetas.ingrediente.update',['receta'=>$receta->id,'ingrediente'=>$ingrediente->id]) }}" class="flex flex-col">
        @csrf
        @method('PUT')

            <div class="flex flex-col">
                <label for="nombre">Ingrediente</label>
                <select id="nombre" name="nombre" disabled>                
                    <option value="{{ $ingrediente->id }}">{{$ingrediente->nombre}}</option>
                </select>
            </div>

        <input type="hidden" name="ingrediente" value="{{ $ingrediente->id }}" />

        <x-form.input-text
            nombre="cantidad" 
            titulo="Cantidad" 
            tipo="number" 
            valor="{{ old('cantidad')?old('cantidad'):$receta->ingredientes()->find($ingrediente)->pivot->cantidad }}"
        >
        </x-form.input-text>

        <x-form.input-text
            nombre="unidad_medida" 
            titulo="Unidad de medida" 
            tipo="text" 
            valor="{{ old('unidad_medida')?old('unidad_medida'):$receta->ingredientes()->find($ingrediente)->pivot->unidad_medida }}"
        >
        </x-form.input-text>

        <button type="submit" class="boton boton--azul">Añadir</button>
    </form>
</x-content>
</x-app-layout>