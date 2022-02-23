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

        function showEditorTablaIngredientesReceta(id){
            const rowEdit = document.getElementById("rowEdit").content.cloneNode(true);
            const row = document.querySelector("[data-ingrediente='" + id + "']");
            
            const cantidad = document.querySelector("[data-ingrediente='" + id + "'] [data-entry='cantidad']").innerHTML;
            const unidades = document.querySelector("[data-ingrediente='" + id + "'] [data-entry='unidad']").innerHTML;
            const nombre = document.querySelector("[data-ingrediente='" + id + "'] [data-entry='nombre']").innerHTML;

            insertAfterRow(rowEdit, row);

            const inputCantidad = document.getElementById("edit-ingrediente-receta-cantidad");
            const inputUnidad = document.getElementById("edit-ingrediente-receta-unidad");
            
            inputCantidad.value = cantidad;
            inputUnidad.value = unidades;
            document.getElementById("edit-ingrediente-receta-ingrediente").innerHTML = nombre;

            enableButtonsTablaIngredientes(false);

            document.getElementById("edit-ingrediente-receta-btn-cancel").addEventListener("click",(e)=>{
                document.getElementById('edit-ingrediente-receta-row').remove();
                enableButtonsTablaIngredientes(true);
            });

            document.getElementById("edit-ingrediente-receta-btn-ok").addEventListener("click",(e)=>{
                Livewire.emit('updateIngredienteReceta',{ingrediente:id, cantidad:inputCantidad.value, unidad:inputUnidad.value});
            });
        }


        function insertAfterRow(newNode, referenceNode) {
            referenceNode.parentNode.insertBefore(newNode, referenceNode.nextSibling);
        }

        function enableButtonsTablaIngredientes(valor){
            const buttons = document.querySelectorAll(".table-ingredientes-receta button:not(.editando)").forEach((btn)=>{
                btn.disabled = !valor;
            });

            document.getElementById('new-ingrediente-boton').disabled = !valor;
        }

        window.addEventListener('swalIngrediente:confirm', (e) => {
            Swal.fire({
                title: e.detail.title,
                text: e.detail.text,
                icon: e.detail.type,
                showCancelButton: true,
                confirmButtonText:'Si',
                confirmButtonAriaLabel: 'Yes',
                cancelButtonText:'No',
                cancelButtonAriaLabel: 'No'
            })
            .then((willDelete) => {
                if(willDelete.isConfirmed){
                    window.livewire.emit('deleteIngredienteReceta', {ingrediente : e.detail.id})
                }
            });
        });
    </script>
@endpush

<div>
    <div class="full__overlay invisible" wire:loading.class.remove="invisible">
        <div class="loader">
            <span></span>
            <span></span>
            <span></span>
            <span></span>
        </div>
    </div>
    <table class="w-full table-ingredientes-receta" style="border-collapse:separate;border-spacing:0 .3em;">
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
                <tr class="bg-white py-3" data-ingrediente="{{$i->id}}">

                    <td class="text-right pr-3 border-b" data-entry="cantidad">{{ $i->pivot->cantidad }}</td>

                    <td class="text-left pl-3 border-b" data-entry="unidad">{{ $i->pivot->unidad_medida }}</td>

                    <td class="text-left border-b" data-entry="nombre">{{ $i->nombre }}</td>

                    <td class="flex justify-center gap-3 p-4" style="margin-left:30px;">
                        <button
                            class="boton boton--gris"
                            onclick="showEditorTablaIngredientesReceta({{$i->id}})"
                        >
                            <x-fas-edit style="width: 15px;"></x-fas-edit>
                            <span>Editar</span>
                        </button>

                        <button 
                            class="boton boton--rojo"
                            wire:click="confirmacionBorrado({{ $i->id }})"
                        >
                            <x-fas-trash-alt style="width: 15px;"></x-fas-trash-alt>
                            <span>Borrar</span>
                        </button>
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
        id="new-ingrediente-boton"
        class="boton boton--azul my-10 w-60"        
        onclick="visibilizaNewIngrediente(true)"
    >
        <x-fas-plus class="icono--boton-1"></x-fas-plus>
        <span>Añadir ingrediente</span>
    </button>

    <template id="rowEdit">
        <tr id="edit-ingrediente-receta-row">
            <td>
                <div class="flex flex-col justify-start">
                    <label for="edit-ingrediente-receta-cantidad">Cantidad</label>
                    <input 
                        id="edit-ingrediente-receta-cantidad" 
                        wire:model="edit_ingrediente_cantidad" 
                        class="form-input--text" 
                        maxlength="6" size="6"/>
                </div>
            </td>
            <td>
                <div class="flex flex-col justify-start">
                    <label for="edit-ingrediente-receta-unidad">Unidades</label>
                    <input 
                        id="edit-ingrediente-receta-unidad" 
                        wire:model="edit_ingrediente_cantidad" 
                        class="form-input--text" 
                        maxlength="10" size="10"/>
                </div>
            </td>
            <td>
                <div class="flex flex-col justify-start">
                    <label for="edit-ingrediente-receta-ingrediente">Nombre</label>
                    <span id="edit-ingrediente-receta-ingrediente"> </span>
                    <input 
                        type="hidden" 
                        id="edit-ingrediente-receta-id" 
                        wire:model="edit_ingrediente_id"
                        />
                </div>
                
            </td>
            <td>
                <div class="flex gap-3">
                    <button id="edit-ingrediente-receta-btn-ok" class="boton boton--verde editando" style="width:50px;">
                        <x-fas-check style="height:20px"/>
                    </button>
                    <button id="edit-ingrediente-receta-btn-cancel" class="boton boton--rojo editando" style="width:50px;">
                        <x-fas-times style="height:20px"/>
                    </button>
                </div>
            </td>
        </tr>
    </template>
</div>
