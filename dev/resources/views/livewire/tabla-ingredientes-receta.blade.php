@push('custom-scripts')
    <script>
        function visibilizaNewIngrediente(visible){
            const row = document.getElementById('new-ingrediente');

            if(visible){
                row.classList.remove('invisible');
            }
            else{
                row.classList.add('invisible');
            }
        }

        function cancelarNew(){
            visibilizaNewIngrediente(false);
            document.getElementById("new-ingrediente-cantidad").value="";
            document.getElementById("new-ingrediente-unidad").value="";
            document.getElementById("new-ingrediente-nombre").value="";
            Livewire.emit('clearSearchBox');
            Livewire.emit('clearNewFormErrors');
        }

        function submitNew(){
            const cantidad = document.getElementById("new-ingrediente-cantidad").value;
            const unidad = document.getElementById("new-ingrediente-unidad").value;
            const id_ingrediente = document.getElementById("new-ingrediente-nombre").value;

            Livewire.emit('addIngredienteReceta',{cantidad:cantidad,unidad:unidad,ingrediente:id_ingrediente});
        }

    </script>
@endpush

<div class="relative">
    <div class="full__overlay invisible" wire:loading.class.remove="invisible">
        <div class="loader">
            <span></span>
            <span></span>
            <span></span>
            <span></span>
        </div>
    </div>
    <table class="w-full" style="border-collapse:separate;border-spacing:0 .3em;">
        <thead>
            <tr>
                <th class="w-1/12 text-center">Cantidad</th>
                <th class="w-2/12 text-center">Unidades</th>
                <th class="w-6/12 text-left">Nombre</th>
                <th class="w-3/12 text-center">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($receta->ingredientes->sortBy('nombre') as $i)
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
            <tr id="new-ingrediente" class="@if($creando_new == false) invisible @endif bg-gray-400">
                <td>
                    <div class="flex flex-col justify-start">
                        <label for="new-ingrediente-cantidad">
                            Cantidad @error('new_ingrediente_cantidad') <span style="color:red;">*</span> @enderror
                        </label>
                        <input id="new-ingrediente-cantidad" type="text" class="form-input bg-gray-200 rounded-md mb-3" maxlenght="6" size="6"/>
                    </div>
                </td>
                <td>
                    <div class="flex flex-col justify-center">
                        <label for="new-ingrediente-unidad">
                            Unidades @error('new_ingrediente_unidad') <span style="color:red;">*</span> @enderror
                        </label>
                        <input id="new-ingrediente-unidad" type="text" class="form-input bg-gray-200 rounded-md mb-3" maxlenght="10" size="10"/>
                    </div>
                </td>
                <td colspan="2">
                    <div class="flex">
                        <div class="flex flex-col grow" style="flex-grow:1;">
                            <label for="new-ingrediente-nombre">
                                Nombre @error('new_ingrediente_id') <span style="color:red;">*</span> @enderror
                            </label>
                            
                            @livewire('search-box',[
                                "clase"=>"ingrediente",
                                "nombre"=>"new-ingrediente-nombre",
                                "excluidos"=>$receta->ingredientes()->pluck('id')->toArray()
                                ],
                                key('new-search-box')
                            )
                            
                        </div>
                        <div class="flex items-center gap-3 mx-4">
                            <button 
                                class="boton boton--icono boton--verde"
                                wire:click="setCreandoNew(true)"
                                onclick="submitNew();"
                                >
                                <x-fas-check class="icono--boton-2"></x-fas-check>
                            </button>
                            <button 
                                class="boton boton--icono boton--rojo"
                                wire:click="setCreandoNew(false)"
                                onclick="cancelarNew();"
                            >
                                <x-fas-times class="icono--boton-2"></x-fas-times>
                            </button>
                        </div>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>

    <button 
        
        class="boton boton--azul my-10 w-60"        
        onclick="visibilizaNewIngrediente(true)"
    >
        <x-fas-plus class="icono--boton-1"></x-fas-plus>
        <span>Añadir ingrediente</span>
    </button>
</div>
