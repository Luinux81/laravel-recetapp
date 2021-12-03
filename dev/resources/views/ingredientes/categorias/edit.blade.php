<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-row justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Categorias de ingredientes') }}
            </h2>
            <div class="flex flex-row gap-5">
                <button class="boton boton--azul" onclick="document.getElementById('update_form_cat_ingrediente').submit();">Guardar</button>
                <a href="{{ route('ingredientes.categoria.index') }}" class="boton boton--rojo">Cancelar</a>
            </div>
        </div>
    </x-slot>

    <x-content>
        
        <form 
            id="update_form_cat_ingrediente" 
            class="flex flex-col" 
            method="post" 
            action="{{ route('ingredientes.categoria.update',['categoria'=>$categoria->id])}}" 
        >
            @csrf
            @method('PUT')
            
            <x-form.input-text 
                nombre="cat_nombre" 
                titulo="Nombre" 
                tipo="text"
                valor="{{ old('cat_nombre')?old('cat_nombre'):$categoria->nombre }}"
            >
            </x-form.input-text>

            <x-form.input-text 
                nombre="cat_descripcion" 
                titulo="Descripcion" 
                tipo="text"
                valor="{{ old('cat_descripcion')?old('cat_descripcion'):$categoria->descripcion }}"
            >
            </x-form.input-text>

            <x-form.select nombre="cat_parent" titulo="Categoria Superior">
                <option value="" selected>Ninguna</option>
                @foreach (\App\Models\CategoriaIngrediente::all() as $cat)
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
                <a href="{{ route('ingredientes.categoria.index') }}" class="boton boton--rojo">Cancelar</a>
            </div>

        </form>
    </x-content>

</x-app-layout>