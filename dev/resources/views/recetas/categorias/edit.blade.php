<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Editar Categoria de recetas') }}
        </h2>
    </x-slot>

    <x-content>
        Formulario para editar categoria
        <form class="flex flex-col" method="post" action="{{ route('recetas.categoria.update',['categoria'=>$categoria->id])}}" >
            @csrf
            @method('PUT')
            
            <div class="flex flex-col">
                <label for="nombre">
                    Nombre @error('nombre')<span class="text-red-500">*</span>@enderror
                </label>
                <input 
                    id="nombre" 
                    name="nombre" 
                    type="text" 
                    value="{{old('nombre')?old('nombre'):$categoria->nombre}}" 
                />
                @error('cat_nombre')
                    <div class="text-red-500" >{{ $message }}</div>
                @enderror
            </div>

            <div class="flex flex-col">
                <label for="descripcion">Descripcion</label>
                <input 
                    id="descripcion" 
                    name="descripcion" 
                    type="text" 
                    value="{{ old('descripcion')?old('descripcion'):$categoria->descripcion }}"
                />
            </div>

            <div class="flex flex-col">
                <label for="cat_parent">Categoria Superior</label>
                <select id="cat_parent" name="cat_parent">
                    <option value="" selected>Ninguna</option>
                    @foreach (\App\Models\CategoriaReceta::all() as $cat)
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