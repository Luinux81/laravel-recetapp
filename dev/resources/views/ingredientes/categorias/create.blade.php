<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-row justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Categorias de ingredientes') }}
            </h2>
            <div class="flex flex-row gap-5">
                <button class="boton boton--azul" onclick="document.getElementById('create_form_cat_ingrediente').submit();">Guardar</button>
                <a href="{{ route('ingredientes.categoria.index') }}" class="boton boton--rojo">Cancelar</a>                
            </div>
        </div>
    </x-slot>

    <x-content>
        
        <form id="create_form_cat_ingrediente" class="flex flex-col" method="post" action="{{ route('ingredientes.categoria.store')}}" >
            @csrf

            <x-form.input-text nombre="nombre" titulo="Nombre" tipo="text">  </x-form.input-text>
            
            <x-form.input-text nombre="descripcion" titulo="Descripcion" tipo="text">  </x-form.input-text>

            <x-form.select nombre="categoria" titulo="Categoria Superior">
                    <option 
                        value="" 
                        @if(old('categoria') == "") selected @endif
                    >Ninguna</option>

                    @foreach ($categorias as $cat)
                        <option 
                            value="{{$cat->id}}" 
                            @if(old('categoria') == $cat->id) selected @endif
                        >{{$cat->nombre}}</option>
                    @endforeach
            </x-form.select>

            <div class="flex gap-5 justify-center m-6">
                <input class="boton boton--azul" type="submit" value="Guardar"/>
                <a href="{{ route('ingredientes.categoria.index') }}" class="boton boton--rojo">Cancelar</a>
            </div>
        </form>
    </x-content>

</x-app-layout>