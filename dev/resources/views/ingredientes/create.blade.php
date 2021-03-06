<x-app-layout>

    <x-slot name="header">
        <div class="flex flex-row justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Crear nuevo ingrediente') }}
            </h2>

            <div class="flex gap-5">
                <form method="post" action="{{ route('ingredientes.offsearch') }}" class="flex relative">
                    @csrf

                    <x-form.input-text
                        titulo=""
                        nombre="offcode"
                        style="margin:0;"
                    >
                    </x-form.input-text>
                    <button type="submit" class="boton boton--gris">Buscar barcode</button>
                </form>
                    
                <a href="{{ route('ingredientes.store') }}" class="boton boton--azul" style="display:none;">Guardar</a>
                <a href="{{ route('ingredientes.index') }}" class="boton boton--rojo" style="margin:auto;">Cancelar</a>
            </div>
        </div>
    </x-slot>

    <x-content>
        <form 
            method="post" 
            action="{{ route('ingredientes.store')}}" 
            class="flex flex-col" 
            enctype="multipart/form-data"
            id="create-ingrediente-form"
        >
            @csrf
            
            <div class="form-ingrediente">

                <div class="form-ingrediente__detalles">
                    <h2>Detalles</h2>
                    <x-form.input-text nombre="nombre" titulo="Nombre" tipo="text">  </x-form.input-text>
                    
                    <div class="flex flex-col">
                        <label for="categoria">Categoria</label>
                        <select id="categoria" name="categoria" class="form-select bg-gray-200 rounded-md mb-3">
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
                    <h2>Informaci??n nutricional (100gr)</h2>
                    <div class="form-ingrediente__info-col1">
                        <x-form.input-text nombre="calorias" titulo="calorias" tipo="number" min="0">  </x-form.input-text>
                        
                        <x-form.input-text nombre="fat_total" titulo="Grasas (Total)" tipo="number" min="0">  </x-form.input-text>
                        
                        <x-form.input-text nombre="fat_saturadas" titulo="Grasas saturadas" tipo="number" min="0">  </x-form.input-text>
                        
                        <x-form.input-text nombre="fat_poliinsaturadas" titulo="Grasas poliinsaturadas" tipo="number" min="0">  </x-form.input-text>
                        
                        <x-form.input-text nombre="fat_monoinsaturadas" titulo="Grasas monoisaturadas" tipo="number" min="0">  </x-form.input-text>
                        
                        <x-form.input-text nombre="fat_trans" titulo="Grasas Trans" tipo="number" min="0">  </x-form.input-text>
                        
                        <x-form.input-text nombre="colesterol" titulo="colesterol" tipo="number" min="0">  </x-form.input-text>
                    </div>
                    
                    <div class="form-ingrediente__info-col2">
                        <x-form.input-text nombre="sodio" titulo="sodio" tipo="number" min="0">  </x-form.input-text>
                        
                        <x-form.input-text nombre="potasio" titulo="potasio" tipo="number" min="0">  </x-form.input-text>
                        
                        <x-form.input-text nombre="fibra" titulo="fibra" tipo="number" min="0">  </x-form.input-text>
                        
                        <x-form.input-text nombre="carb_total" titulo="Carbohidratos (Total)" tipo="number" min="0">  </x-form.input-text>
                        
                        <x-form.input-text nombre="carb_azucar" titulo="Az??car" tipo="number" min="0">  </x-form.input-text>
                        
                        <x-form.input-text nombre="proteina" titulo="proteina" tipo="number" min="0">  </x-form.input-text>
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
                <button type="submit" class="boton boton--azul">Guardar</button>
                <a href="{{ route('ingredientes.index') }}" class="boton boton--rojo">Cancelar</a>
            </div>
        </form>
    </x-content>

@push('custom-scripts')

<script>
    function buscaCodigo(event)
    {
        //Obtenemos el valor del input
        let codigo = document.getElementById('offcode').value;        
        const url = `https://world.openfoodfacts.org/api/v0/product/${codigo}.json`;
        
        //Hacemos una llamada ajax a la api de off
        axios.get(url)
            .then(function(response){
                console.log(response);
            })
            .catch(function(error){
                console.log(`Error: ${error}`);
            });
    }
</script>

@endpush

</x-app-layout>

