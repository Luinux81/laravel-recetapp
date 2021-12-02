<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Categorias de ingredientes') }}
        </h2>
    </x-slot>

    <x-content>
        
        <form class="flex flex-col" method="post" action="{{ route('ingredientes.categoria.store')}}" >
            @csrf

            <x-form.input-text nombre="cat_nombre" titulo="Nombre" tipo="text">  </x-form.input-text>
            
            <x-form.input-text nombre="cat_descripcion" titulo="Descripcion" tipo="text">  </x-form.input-text>

            <x-form.select nombre="cat_parent" titulo="Categoria Superior">
                    <option value="" selected>Ninguna</option>
                    @foreach ($categorias as $cat)
                        <option value="{{$cat->id}}">{{$cat->nombre}}</option>
                    @endforeach
            </x-form.select>

            <input class="boton boton--azul m-6" type="submit" value="Guardar"/>
        </form>
    </x-content>

</x-app-layout>