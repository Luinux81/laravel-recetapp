@push('custom-styles')
    @livewireStyles
@endpush

@push('custom-scripts')
    @livewireScripts

    <script src="https://unpkg.com/@nextapps-be/livewire-sortablejs@0.1.1/dist/livewire-sortable.js"></script>
    {{-- <script src="https://cdn.jsdelivr.net/gh/livewire/sortable@v0.x.x/dist/livewire-sortable.js"></script> --}}
@endpush

<x-app-layout>

    <x-slot name="header">
        <div class="flex flex-row justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Editar receta') }}
            </h2>
            <div class="flex gap-3">
                <div class="flex flex-row justify-between gap-5">
                    <button class="boton boton--azul" onclick="document.getElementById('update_form_receta').submit();">Guardar</button>
                    
                    <a href="{{ route('recetas.index') }}" class="boton boton--gris">Cancelar</a>
                    
                    <x-form.boton-post
                        url="{{ route('recetas.destroy',['receta'=>$receta->id]) }}"
                        metodo="DELETE"
                        onclick="confirmarBorrado(event)"
                        class="boton boton--rojo"
                    >
                    Borrar
                    </x-form.boton-post>
                </div>
            </div>
        </div>
    </x-slot>

    <x-content>
        <form 
            method="post" 
            action="{{ route('recetas.update', ['receta'=>$receta->id])}}" 
            class="flex flex-col"
            enctype="multipart/form-data"
            id="update_form_receta"
        >
            @csrf
            @method('PUT')

            <x-form.input-text 
                nombre="nombre" 
                titulo="Nombre" 
                tipo="text" 
                valor="{{ old('nombre')?old('nombre'):$receta->nombre}}"
            >  </x-form.input-text>

            <x-form.input-text 
                nombre="descripcion" 
                titulo="Descripcion" 
                tipo="text"
                valor="{{ old('descripcion')?old('descripcion'):$receta->descripcion}}"
            >
            </x-form.input-text>

            <x-form.input-text 
                nombre="calorias" 
                titulo="calorias" 
                tipo="number"
                min="0"
                valor="{{ old('calorias')?old('calorias'):$receta->calorias }}"
            >
            </x-form.input-text>
            
            <x-form.input-text 
                nombre="raciones" 
                titulo="raciones" 
                tipo="number"
                min="0"
                valor="{{ old('raciones')?old('raciones'):$receta->raciones }}"
            >
            </x-form.input-text>

            <x-form.input-text 
                nombre="tiempo" 
                titulo="tiempo" 
                tipo="text"
                valor="{{ old('tiempo')?old('tiempo'):$receta->tiempo }}"
            >
            </x-form.input-text>


            <x-form.select nombre="categoria" titulo="Categoria">
                <option 
                    value="" 
                    @if(empty($receta->cat_id)) selected @endif 
                >
                Ninguna
                </option>

                @foreach ($categorias as $cat)
                    <option 
                        value="{{$cat->id}}" 
                        @if($receta->cat_id == $cat->id) selected @endif 
                    >
                    {{$cat->nombre}}
                    </option>
                @endforeach                
            </x-form.select>

            <x-form.image-upload
                nombre="imagen"
                titulo="imagen"
                imagen="{{ old('imagen')?old('imagen'):$receta->imagen }}"
                accept="image/*"                
            >
                <x-fas-image style="width:40px;"> </x-fas-image>
                <x-fas-plus class="icono-modificador" style="margin-left:-10px; margin-bottom:-25px;"> </x-fas-plus>
            </x-form.image-upload>

        </form>


        <section class="seccion seccion-ingredientes my-14 p-8">
            <h2 class="font-bold text-2xl text-gray-800 leading-tight mb-3">Ingredientes</h2>
            
            <table class="w-full" style="border-collapse:separate;border-spacing:0 .3em;">
                <thead>
                    <tr>
                        <th class="w-1/12 text-center">Cantidad</th>
                        <th class="w-1/12 text-center">Unidades</th>
                        <th class="w-7/12 text-left">Nombre</th>
                        <th class="w-3/12 text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($receta->ingredientes as $i)
                        <tr class="bg-white py-3">
                            <td class="text-right pr-3 border-b">{{ $i->pivot->cantidad }}</td>
                            <td class="text-left pl-3 border-b">{{ $i->pivot->unidad_medida }}</td>
                            <td class="text-left border-b">{{ $i->nombre }}</td>

                            <td class="flex justify-center gap-3 p-4" style="margin-left:30px;">
                                <a href="{{ route('recetas.ingrediente.edit',['receta'=>$receta->id, 'ingrediente'=>$i->id])}}" class="boton boton--gris">
                                    <x-fas-edit style="width: 15px;"></x-fas-edit>
                                    <span>Editar</span>
                                </a>

                                <x-form.boton-post
                                    url="{{ route('recetas.ingrediente.destroy', ['receta'=>$receta->id, 'ingrediente'=>$i->id]) }}"
                                    metodo="DELETE"
                                    class="boton boton--rojo"
                                    onclick="confirmarBorrado(event)"
                                >
                                    <x-fas-trash-alt style="width: 15px;"></x-fas-trash-alt>
                                    <span>Borrar</span>
                                </x-form.boton-post>
                            </td>

                        </tr>
                    @endforeach
                    <tr id="new-ingrediente" class="invisible bg-gray-400">
                        <td>
                            <div class="flex flex-col justify-center">
                                <label for="new-ingrediente-cantidad">Cantidad</label>
                                <input id="new-ingrediente-cantidad" type="text" class="form-input bg-gray-200 rounded-md mb-3"/>
                            </div>
                        </td>
                        <td>
                            <div class="flex flex-col justify-center">
                                <label for="new-ingrediente-unidad">Unidades</label>
                                <input id="new-ingrediente-unidad" type="text" class="form-input bg-gray-200 rounded-md mb-3"/>
                            </div>
                        </td>
                        <td colspan="2">
                            <div class="flex">
                                <div class="flex flex-col grow" style="flex-grow:1;">
                                    <label for="new-ingrediente-Nombre">Nombre</label>
                                    <input id="new-ingrediente-Nombre" type="text" class="w-full form-input bg-gray-200 rounded-md mb-3"/>
                                </div>
                                <div class="flex items-center gap-3 mx-4">
                                    <button class="boton boton--icono boton--verde">
                                        <x-fas-check class="icono--boton-2"></x-fas-check>
                                    </button>
                                    <button class="boton boton--icono boton--rojo">
                                        <x-fas-times class="icono--boton-2"></x-fas-times>
                                    </button>
                                </div>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>

            <a 
                href="{{ route('recetas.ingrediente.create',['receta'=>$receta->id]) }}" 
                class="boton boton--azul my-10 w-60"
            >
                <x-fas-plus class="icono--boton-1"></x-fas-plus>
                <span>Añadir ingrediente</span>
            </a>
        </section>

        <section class="seccion seccion-pasos my-14 p-8">
            <h2 class="font-bold text-2xl text-gray-800 leading-tight mb-3">Pasos</h2>
            
            @livewire('lista-pasos-receta', ['receta' => $receta])
        </section>
        
        <div class="flex justify-center gap-5 m-6">
            <button class="boton boton--azul" onclick="document.getElementById('update_form_receta').submit();">Guardar</button>
            <a href="{{ route('recetas.index') }}" class="boton boton--rojo">Volver</a>
        </div>

    </x-content>
</x-app-layout>