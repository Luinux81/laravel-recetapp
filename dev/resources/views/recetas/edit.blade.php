<x-app-layout>

    <x-slot name="header">
        <div class="flex flex-row justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Editar receta') }}
            </h2>
            <a href="{{ route('recetas.index') }}" class="boton boton--rojo">Cancelar</a>
        </div>
    </x-slot>

    <x-content>
        <form method="post" action="{{ route('recetas.update', ['receta'=>$receta->id])}}" class="flex flex-col">
            @csrf
            @method('PUT')

            <x-form.input-text 
                nombre="nombre" 
                titulo="Nombre" 
                tipo="text" 
                valor="{{ old('nombre')?old('nombre'):$receta->nombre}}"
            >  </x-form.input-text>

            <x-form.input-text 
                nombre="descripcion" 
                titulo="Descripcion" 
                tipo="text"
                valor="{{ old('descripcion')?old('descripcion'):$receta->descripcion}}"
            >
            </x-form.input-text>

            <x-form.input-text 
                nombre="calorias" 
                titulo="calorias" 
                tipo="number"
                valor="{{ old('calorias')?old('calorias'):$receta->calorias }}"
            >
            </x-form.input-text>
            
            <div class="flex flex-col">
                <label for="categoria">Categoria</label>
                <select id="categoria" name="categoria">                
                    <option value="" @if(empty($receta->cat_id)) selected @endif >Ninguna</option>
                    @foreach ($categorias as $cat)
                        <option value="{{$cat->id}}" @if($receta->cat_id == $cat->id) selected @endif >{{$cat->nombre}}</option>
                    @endforeach
                </select>
            </div>

            <input class="boton boton--azul m-6" type="submit" value="Guardar"/>
        </form>
    </x-content>

</x-app-layout>