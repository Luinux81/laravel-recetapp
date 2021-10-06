<x-app-layout>

    <x-slot name="header">
        <div class="flex flex-row justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Crear nuevo ingrediente') }}
            </h2>
            <a href="{{ route('ingredientes') }}" class="boton boton--rojo">Cancelar</a>
        </div>
    </x-slot>

    <x-content>
        <form method="post" action="{{ route('ingredientes.store')}}" class="flex flex-col">
            @csrf
            
            <x-form.input-text nombre="nombre" titulo="Nombre" tipo="text">  </x-form.input-text>

            <x-form.input-text nombre="descripcion" titulo="Descripcion" tipo="text">  </x-form.input-text>

            <x-form.input-text nombre="marca" titulo="marca" tipo="text">  </x-form.input-text>

            <x-form.input-text nombre="barcode" titulo="barcode" tipo="text">  </x-form.input-text>        

            <x-form.input-text nombre="imagen" titulo="imagen" tipo="text">  </x-form.input-text>
            
            <x-form.input-text nombre="url" titulo="url" tipo="text">  </x-form.input-text>

            <x-form.input-text nombre="calorias" titulo="calorias" tipo="number">  </x-form.input-text>
            
            <x-form.input-text nombre="fat_total" titulo="Grasas (Total)" tipo="number">  </x-form.input-text>
            
            <x-form.input-text nombre="fat_saturadas" titulo="Grasas saturadas" tipo="number">  </x-form.input-text>
            
            <x-form.input-text nombre="fat_poliinsaturadas" titulo="Grasas poliinsaturadas" tipo="number">  </x-form.input-text>
            
            <x-form.input-text nombre="fat_monoinsaturadas" titulo="Grasas monoisaturadas" tipo="number">  </x-form.input-text>
            
            <x-form.input-text nombre="fat_trans" titulo="Grasas Trans" tipo="number">  </x-form.input-text>
            
            <x-form.input-text nombre="colesterol" titulo="colesterol" tipo="number">  </x-form.input-text>
            
            <x-form.input-text nombre="sodio" titulo="sodio" tipo="number">  </x-form.input-text>
            
            <x-form.input-text nombre="potasio" titulo="potasio" tipo="number">  </x-form.input-text>
            
            <x-form.input-text nombre="fibra" titulo="fibra" tipo="number">  </x-form.input-text>
            
            <x-form.input-text nombre="carb_total" titulo="Carbohidratos (Total)" tipo="number">  </x-form.input-text>
            
            <x-form.input-text nombre="carb_azucar" titulo="Azúcar" tipo="number">  </x-form.input-text>
            
            <x-form.input-text nombre="proteina" titulo="proteina" tipo="number">  </x-form.input-text>
            
            <div class="flex flex-col">
                <label for="categoria">Categoria</label>
                <select id="categoria" name="categoria">
                    <option value="" selected>Ninguna</option>
                @foreach ($categorias as $cat)
                    <option value="{{$cat->id}}">{{$cat->nombre}}</option>
                @endforeach
                </select>
            </div>

            <input class="boton boton--azul m-6" type="submit" value="Guardar"/>
        </form>
    </x-content>

</x-app-layout>