<x-app-layout>

    <x-slot name="header">
        <div class="flex flex-row justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Crear nuevo ingrediente') }}
            </h2>

            <div class="flex gap-5">
                <a href="{{ route('ingredientes.store') }}" class="boton boton--azul" style="display:none;">Guardar</a>
                <a href="{{ route('ingredientes.index') }}" class="boton boton--rojo">Cancelar</a>
            </div>
        </div>
    </x-slot>

    <x-content>
        <form method="post" action="{{ route('ingredientes.store')}}" class="flex flex-col" enctype="multipart/form-data">
            @csrf
            
            <div class="form-ingrediente">

                <div class="form-ingrediente__detalles">
                    <h2>Detalles</h2>
                    <x-form.input-text nombre="nombre" titulo="Nombre" tipo="text">  </x-form.input-text>
                    
                    <div class="flex flex-col">
                        <label for="categoria">Categoria</label>
                        <select id="categoria" name="categoria" class="bg-gray-200 rounded-md mb-3">
                            <option value="" @if(empty(old('categoria'))) selected @endif>Ninguna</option>
                        @foreach ($categorias as $cat)
                            <option value="{{$cat->id}}" @if(old('categoria') == $cat->id) selected @endif>{{$cat->nombre}}</option>
                        @endforeach
                        </select>
                    </div>
                    <x-form.input-text nombre="descripcion" titulo="Descripcion" tipo="text">  </x-form.input-text>
                    <x-form.input-text nombre="marca" titulo="marca" tipo="text">  </x-form.input-text>
                    <x-form.input-text nombre="barcode" titulo="barcode" tipo="text">  </x-form.input-text>
                    <x-form.input-text nombre="url" titulo="url" tipo="text">  </x-form.input-text>
                </div>
                
                <div class="form-ingrediente__info">
                    <h2>Información nutricional (100gr)</h2>
                    <div class="form-ingrediente__info-col1">
                        <x-form.input-text nombre="calorias" titulo="calorias" tipo="number">  </x-form.input-text>
                        
                        <x-form.input-text nombre="fat_total" titulo="Grasas (Total)" tipo="number">  </x-form.input-text>
                        
                        <x-form.input-text nombre="fat_saturadas" titulo="Grasas saturadas" tipo="number">  </x-form.input-text>
                        
                        <x-form.input-text nombre="fat_poliinsaturadas" titulo="Grasas poliinsaturadas" tipo="number">  </x-form.input-text>
                        
                        <x-form.input-text nombre="fat_monoinsaturadas" titulo="Grasas monoisaturadas" tipo="number">  </x-form.input-text>
                        
                        <x-form.input-text nombre="fat_trans" titulo="Grasas Trans" tipo="number">  </x-form.input-text>
                        
                        <x-form.input-text nombre="colesterol" titulo="colesterol" tipo="number">  </x-form.input-text>
                    </div>
                    
                    <div class="form-ingrediente__info-col2">
                        <x-form.input-text nombre="sodio" titulo="sodio" tipo="number">  </x-form.input-text>
                        
                        <x-form.input-text nombre="potasio" titulo="potasio" tipo="number">  </x-form.input-text>
                        
                        <x-form.input-text nombre="fibra" titulo="fibra" tipo="number">  </x-form.input-text>
                        
                        <x-form.input-text nombre="carb_total" titulo="Carbohidratos (Total)" tipo="number">  </x-form.input-text>
                        
                        <x-form.input-text nombre="carb_azucar" titulo="Azúcar" tipo="number">  </x-form.input-text>
                        
                        <x-form.input-text nombre="proteina" titulo="proteina" tipo="number">  </x-form.input-text>
                    </div>
                </div>

                <x-form.image-upload
                    nombre="imagen"
                    titulo="imagen"
                    accept="image/*"
                    imagen=""
                    icono="{{ asset('images/photo_icon.png') }}"
                    showFilename="1"
                    >
                </x-form.image-upload>
            </div>
            

            <div class="flex gap-5 justify-center m-6">
                <a href="{{ route('ingredientes.store') }}" class="boton boton--azul">Guardar</a>
                <a href="{{ route('ingredientes.index') }}" class="boton boton--rojo">Cancelar</a>
            </div>
        </form>
    </x-content>

</x-app-layout>