<x-app-layout>
<x-slot name="header">
    <h2>Añadir ingrediente a receta {{ $receta->nombre }}</h2>
    <a href="{{ route('recetas.edit',['receta'=>$receta->id])}}" class="boton boton--rojo">Cancelar</a>
</x-slot>
<x-content>
    <form method="post" action="{{ route('recetas.ingrediente.store',['receta'=>$receta->id]) }}" class="flex flex-col">
        @csrf
        <div class="flex flex-col">
            <label for="ingrediente">Ingrediente</label>
            <select id="ingrediente" name="ingrediente">                                
                @foreach ($ingredientes as $i)
                    <option 
                        value="{{$i->id}}"
                        @if($receta->ingredientes()->find($i))
                        disabled
                        @endif
                    >
                        {{$i->nombre}}
                    </option>
                @endforeach
            </select>
        </div>

        <a href="{{ route('ingredientes.create') }}" class="boton boton--gris">Nuevo ingrediente</a>
        
        <x-form.input-text
            nombre="cantidad" 
            titulo="Cantidad" 
            tipo="number" 
            valor="100"
        >
        </x-form.input-text>

        <x-form.input-text
            nombre="unidad_medida" 
            titulo="Unidad de medida" 
            tipo="text" 
            valor="gr"
        >
        </x-form.input-text>

        <button type="submit" class="boton boton--azul">Añadir</button>
    </form>
</x-content>
</x-app-layout>