<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-row justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Editar Categoria de recetas') }}
            </h2>
            <div class="flex gap-5">
                <button class="boton boton--azul" onclick="document.getElementById('update_form_cat_receta').submit();">Guardar</button>
                <a href="{{ route('recetas.categoria.index') }}" class="boton boton--rojo">Cancelar</a>
            </div>
        </div>
    </x-slot>

    <x-content>

        <form 
            id="update_form_cat_receta" 
            class="flex flex-col" 
            method="post" 
            action="{{ route('recetas.categoria.update',['categoria'=>$categoria->id])}}" 
        >
            @csrf
            @method('PUT')
            
            <x-form.input-text 
                nombre="nombre" 
                titulo="Nombre" 
                tipo="text"
                valor="{{ old('nombre')?old('nombre'):$categoria->nombre }}"
            >
            </x-form.input-text>

            <x-form.input-text 
                nombre="descripcion" 
                titulo="Descripcion" 
                tipo="text"
                valor="{{ old('descripcion')?old('descripcion'):$categoria->descripcion }}"
            >
            </x-form.input-text>

            <x-form.select nombre="categoria" titulo="Categoria Superior">
                <option value="" selected>Ninguna</option>
                @foreach (\App\Models\CategoriaReceta::all()->sortBy('nombre') as $cat)
                    @if ($cat->id != $categoria->id)
                        <option 
                            value="{{$cat->id}}" 
                            @if ($cat->id == $categoria->catParent_id)
                                selected 
                            @endif
                            @if ($categoriasHija->contains($cat->id))
                                disabled
                            @endif                                
                        >
                            {{$cat->nombre}}
                        </option>    
                    @endif                        
                @endforeach   
            </x-form.select>

            <div class="flex gap-5 justify-center m-6">
                <input class="boton boton--azul" type="submit" value="Guardar"/>
                <a href="{{ route('recetas.categoria.index') }}" class="boton boton--rojo">Cancelar</a>
            </div>
        </form>
    </x-content>

</x-app-layout>