<x-app-layout>

    <x-slot name="header">
        <div class="flex flex-row justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Editar ingrediente') }}
            </h2>
            <a href="{{ route('ingredientes') }}" class="boton boton--rojo">Cancelar</a>
        </div>
    </x-slot>

    <x-content>
        <form method="post" action="{{ route('ingredientes.update', ['ingrediente'=>$ingrediente->id])}}" class="flex flex-col">
            @csrf
            @method('PUT')

            <x-form.input-text 
                nombre="nombre" 
                titulo="Nombre" 
                tipo="text" 
                valor="{{ old('nombre')?old('nombre'):$ingrediente->nombre}}"
            >  </x-form.input-text>

            <x-form.input-text 
                nombre="descripcion" 
                titulo="Descripcion" 
                tipo="text"
                valor="{{ old('descripcion')?old('descripcion'):$ingrediente->descripcion}}"
            >
            </x-form.input-text>

            <x-form.input-text 
                nombre="marca" 
                titulo="marca" 
                tipo="text"
                valor="{{ old('marca')?old('marca'):$ingrediente->marca }}"
            >
            </x-form.input-text>

            <x-form.input-text 
                nombre="barcode" 
                titulo="barcode" 
                tipo="text"
                valor="{{ old('barcode')?old('barcode'):$ingrediente->barcode }}"
            >
            </x-form.input-text>        

            <x-form.input-text 
                nombre="imagen" 
                titulo="imagen" 
                tipo="text"
                valor="{{ old('imagen')?old('imagen'):$ingrediente->imagen }}"
            >
            </x-form.input-text>
            
            <x-form.input-text 
                nombre="url" 
                titulo="url" 
                tipo="text"
                valor="{{ old('url')?old('url'):$ingrediente->url }}"
            >
            </x-form.input-text>

            <x-form.input-text 
                nombre="calorias" 
                titulo="calorias" 
                tipo="number"
                valor="{{ old('calorias')?old('calorias'):$ingrediente->calorias }}"
            >
            </x-form.input-text>
            
            <x-form.input-text 
                nombre="fat_total" 
                titulo="Grasas (Total)" 
                tipo="number"
                valor="{{ old('fat_total')?old('fat_total'):$ingrediente->fat_total }}"
            >
            </x-form.input-text>
            
            <x-form.input-text 
                nombre="fat_saturadas" 
                titulo="Grasas saturadas" 
                tipo="number"
                valor="{{ old('fat_saturadas')?old('fat_saturadas'):$ingrediente->fat_saturadas }}"
            >
            </x-form.input-text>
            
            <x-form.input-text 
                nombre="fat_poliinsaturadas" 
                titulo="Grasas poliinsaturadas" 
                tipo="number"
                valor="{{ old('fat_poliinsaturadas')?old('fat_poliinsaturadas'):$ingrediente->fat_poliinsaturadas }}"
            >
            </x-form.input-text>
            
            <x-form.input-text 
                nombre="fat_monoinsaturadas" 
                titulo="Grasas monoisaturadas" 
                tipo="number"
                valor="{{ old('fat_monoinsaturadas')?old('fat_monoinsaturadas'):$ingrediente->fat_monoinsaturadas }}"
            >
            </x-form.input-text>
            
            <x-form.input-text 
                nombre="fat_trans" 
                titulo="Grasas Trans" 
                tipo="number"
                valor="{{ old('fat_trans')?old('fat_trans'):$ingrediente->fat_trans }}"
            >
            </x-form.input-text>
            
            <x-form.input-text 
                nombre="colesterol" 
                titulo="colesterol" 
                tipo="number"
                valor="{{ old('colesterol')?old('colesterol'):$ingrediente->colesterol }}"
            >
            </x-form.input-text>
            
            <x-form.input-text 
                nombre="sodio" 
                titulo="sodio" 
                tipo="number"
                valor="{{ old('sodio')?old('sodio'):$ingrediente->sodio }}"
            >
            </x-form.input-text>
            
            <x-form.input-text 
                nombre="potasio" 
                titulo="potasio" 
                tipo="number"
                valor="{{ old('potasio')?old('potasio'):$ingrediente->potasio }}"
            >
            </x-form.input-text>
            
            <x-form.input-text 
                nombre="fibra" 
                titulo="fibra" 
                tipo="number"
                valor="{{ old('fibra')?old('fibra'):$ingrediente->fibra }}"
            >
            </x-form.input-text>
            
            <x-form.input-text 
                nombre="carb_total" 
                titulo="Carbohidratos (Total)" 
                tipo="number"
                valor="{{ old('carb_total')?old('carb_total'):$ingrediente->carb_total }}"
            >
            </x-form.input-text>
            
            <x-form.input-text 
                nombre="carb_azucar" 
                titulo="Azúcar" 
                tipo="number"
                valor="{{ old('carb_azucar')?old('carb_azucar'):$ingrediente->carb_azucar }}"
            >
            </x-form.input-text>
            
            <x-form.input-text 
                nombre="proteina" 
                titulo="proteina" 
                tipo="number"
                valor="{{ old('proteina')?old('proteina'):$ingrediente->proteina }}"
            >
            </x-form.input-text>
            
            <div class="flex flex-col">
                <label for="categoria">Categoria</label>
                <select id="categoria" name="categoria">                
                    <option value="" @if(empty($ingrediente->cat_id)) selected @endif >Ninguna</option>
                    @foreach ($categorias as $cat)
                        <option value="{{$cat->id}}" @if($ingrediente->cat_id == $cat->id) selected @endif >{{$cat->nombre}}</option>
                    @endforeach
                </select>
            </div>

            <input class="boton boton--azul m-6" type="submit" value="Guardar"/>
        </form>
    </x-content>

</x-app-layout>