<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Categorias de ingredientes') }}
        </h2>
    </x-slot>

    <x-content>
        Formulario para editar categoria
        <form class="flex flex-col" method="post" action="{{ route('ingredientes.categoria.update',['categoria'=>$categoria->id])}}" >
            @csrf
            @method('PUT')
            
            <div class="flex flex-col">
                <label for="cat_nombre">Nombre</label>
                <input 
                    id="cat_nombre" 
                    name="cat_nombre" 
                    type="text" 
                    value="{{old('cat_nombre')?old('cat_nombre'):$categoria->nombre}}" 
                />
            </div>

            <div class="flex flex-col">
                <label for="cat_descripcion">Descripcion</label>
                <input 
                    id="cat_descripcion" 
                    name="cat_descripcion" 
                    type="text" 
                    value="{{ old('cat_descripcion')?old('cat_descripcion'):$categoria->descripcion }}"
                />
            </div>

            <div class="flex flex-col">
                <label for="cat_parent">Categoria Superior</label>
                <select id="cat_parent" name="cat_parent">
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
                </select>
            </div>

            <input class="boton boton--azul m-6" type="submit" value="Guardar"/>
        </form>
    </x-content>

</x-app-layout>