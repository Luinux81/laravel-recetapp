@push('custom-styles')
    @livewireStyles
    <style>
        .highlight{
            background-color: lightblue;
        }
    </style>
@endpush

@push('custom-scripts')
    @livewireScripts
@endpush

<x-app-layout>
<x-slot name="header">
    <div class="flex items-center justify-between">
        <h2>Añadir ingrediente a receta {{ $receta->nombre }}</h2>
        <a href="{{ route('recetas.edit',['receta'=>$receta->id])}}" class="boton boton--rojo">
            <x-fas-arrow-alt-circle-left class="icono--boton-2" />
            <span>Cancelar</span>
        </a>
    </div>
</x-slot>
<x-content>
    <form method="post" action="{{ route('recetas.ingrediente.store',['receta'=>$receta->id]) }}" class="flex flex-col">
        @csrf
        {{-- <div class="flex flex-col">
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
        </div> --}}
        {{-- <x-form.select nombre="ingrediente" titulo="Ingrediente">
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
        </x-form.select> --}}

        <div class="mb-3">
            <label for="ingrediente"> Ingrediente </label>
            @livewire('search-box',['clase'=>'ingrediente', 'nombre'=>'ingrediente'])
            <a href="{{ route('ingredientes.create') }}" class="boton boton--gris invisible">Nuevo ingrediente</a>
        </div>
        
        <x-form.input-text
            nombre="cantidad" 
            titulo="Cantidad" 
            tipo="number" 
            min="1"            
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