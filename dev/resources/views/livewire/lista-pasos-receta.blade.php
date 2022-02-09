<div>
    <div class="drag-list__overlay invisible" wire:loading.class.remove="invisible">
        <div class="loader">
            <span></span>
            <span></span>
            <span></span>
            <span></span>
        </div>
    </div>

    <ul class="drag-list" wire:sortable="updateOrdenPasos">
        
        @foreach ($receta->pasos()->orderBy('orden')->get() as $paso)
            <li 
                class="drag-list__item" 
                wire:sortable.item="{{ $paso->id }}" 
                wire:key="paso-{{ $paso->id }}" 
                data-key="paso-{{ $paso->id }}"
                x-data="{ isOpen : true}"
            >
                
                <button class="drag-list__item__handle" wire:sortable.handle><x-fas-ellipsis-v /></button>

                @if($paso->assets()->count() > 0)
                    <button 
                        x-show="!isOpen"
                        style = "display:none;"
                        x-on:click=" isOpen = true "
                        class="mx-2 disabled:text-gray-200"
                    >
                        <x-fas-eye style="width: 25px;cursor:pointer;"></x-fas-eye>
                    </button>
                    <button 
                        x-show="isOpen"
                        style = "display:none;"
                        x-on:click=" isOpen = false "
                        class="mx-2 disabled:text-gray-200"
                    >
                        <x-fas-eye-slash style="width: 25px;cursor:pointer;"></x-fas-eye-slash>
                    </button>
                @endif

                <h4 class="drag-list__item__texto">{{ $paso->texto }}</h4>
                
                <div class="drag-list__item__acciones flex gap-3">
                    <button class="boton boton--gris" x-on:click=" isOpen = false " onclick="showEditor({{ $paso->id }})">
                        <x-fas-edit style="width: 15px;"/>
                        <span>Editar</span>
                    </button>

                    <button 
                        class="boton boton--rojo"                  
                        wire:click="confirmacionBorrado({{ $paso->id }})"
                    >
                        <x-fas-trash-alt style="width: 15px;"/>
                        <span>Borrar</span>
                    </button>
                </div>

                @if($paso->assets()->count() > 0)
                    <div class="w-full rounded-md" style="display:none;" x-show="isOpen">
                        @livewire('asset-loader',[
                            'origen' => 'PasoReceta',
                            'id_modelo' => $paso->id, 
                            'modo' => 'show',
                            
                        ], key($paso->id))
                    </div>
                @endif

            </li>
            <li id="paso-edit-{{ $paso->id }}" class="w-full" style="display: none;">
                <div class="asset-loader w-full ml-7 rounded-md">
                    @livewire('asset-loader',[
                        'origen' => 'PasoReceta',
                        'id_modelo' => $paso->id, 
                        'modo' => 'edit',
                        
                    ], key("edit-" . $paso->id))
                </div>
            </li>
        @endforeach        

        <li id="new-paso-li" class="flex items-center my-5 invisible">
            <label for="new-paso-input" class="mx-3"><span>Nuevo paso: </span></label>
            <input id="new-paso-input" name="texto" type="text" class="form-input--text w-1/2 mr-10" style="margin-bottom: 0;">
            <button class="boton boton--verde mx-1" style="width: 50px;" disabled> <x-fas-check style="height:20px"/> </button>
            <button class="boton boton--rojo mx-1"  style="width: 50px;"> <x-fas-times style="height:20px"/> </button>
        </li>
    </ul>

    <button id="btnAddPaso" class="boton boton--azul my-10">
        <x-fas-plus style="width: 15px" />
        <span>Añadir paso</span>
    </button>
</div>

@push('custom-scripts')

    <script>
        {{-- IIFE: Expresión de función ejecutada inmediatamente --}}
        (()=>{
            window.addEventListener("DOMContentLoaded",()=>{
                const li = document.getElementById('new-paso-li');
                const btnAddPaso = document.getElementById('btnAddPaso');        
                const input = document.getElementById('new-paso-input');  
                const btnConfirmar = document.querySelector('#new-paso-li .boton--verde');
                const btnCancelar = document.querySelector('#new-paso-li .boton--rojo');

                // Botón añadir paso
                btnAddPaso.addEventListener("click", () => {
                    btnAddPaso.disabled = true;
                    li.classList.remove('invisible');
                    input.focus();
                });

                // Botón cancelar nuevo paso
                btnCancelar.addEventListener("click", () => {
                    resetNuevoPaso();
                });

                // Input nuevo paso
                input.addEventListener("keyup", (event) => {
                    if(input.value.trim() != ""){
                        btnConfirmar.disabled = false;

                        // Enviamos al pulsar enter
                        if(event.key === "Enter" || event.keyCode === "13"){
                            window.livewire.emit('new-paso-receta', {texto : input.value})
                            resetNuevoPaso();
                        }
                    }
                    else{
                        btnConfirmar.disabled = true;
                    }
                });

                // Botón confirmar nuevo paso
                btnConfirmar.addEventListener("click", () => {
                    if(input.value.trim() != ""){
                        const texto = input.value;
                        input.value = "";
                        window.livewire.emit('new-paso-receta', {texto : texto});
                    }
                    
                    // Ocultamos item nuevo paso, igual que el boton cancelar
                    resetNuevoPaso();
                });

                window.disableDrag = false;

                // Confirmacion de borrado con Sweet Alert 2
                window.addEventListener('swal:confirm', (e) => {
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
                            window.livewire.emit('deletePaso', {paso : e.detail.id})
                        }
                    });
                });

            });
        })();

        function showEditor(id)
        {
            const line = document.querySelector('.drag-list__item[data-key=paso-' + id + ']');
            const texto = document.querySelector('.drag-list__item[data-key=paso-' + id + '] .drag-list__item__texto');

            const editLine = document.createElement("li");            
            const editInput = document.createElement("input");
            const btnEditar = document.createElement("button");
            const btnCancelar = document.createElement("button");

            editLine.classList.add("drag-list__item");
            
            editInput.classList.add("form-input--text","w-1/2","mx-10", "mb-0");
            editInput.value = texto.innerHTML;
            editInput.setAttribute("name","texto");
            editInput.addEventListener("keyup", (e) => {
                if(editInput.value.trim() != ""){
                    btnEditar.disabled = false;
                }
                else{
                    btnEditar.disabled = true;
                }
            })

            btnEditar.classList.add("boton", "boton--verde", "mx-1", "editando");
            btnEditar.style.width = "50px";
            btnEditar.innerHTML = `<x-fas-check style="height:20px"/>`;
            btnEditar.addEventListener("click", (event) => {
                enableButtons(true);
                window.livewire.emit('updatePaso', {'paso': id, 'texto': editInput.value})
            })

            btnCancelar.classList.add("boton", "boton--rojo", "mx-1", "editando");
            btnCancelar.style.width = "50px";
            btnCancelar.innerHTML = `<x-fas-times style="height:20px"/>`;
            btnCancelar.addEventListener("click", (event) => {                
                resetEditarPaso(btnCancelar);
            })

            editLine.appendChild(editInput);
            editLine.appendChild(btnEditar);
            editLine.appendChild(btnCancelar);

            const componenteAssets = document.getElementById("paso-edit-" + id ).cloneNode(true);
            componenteAssets.style.display = "flex";

            editLine.appendChild(componenteAssets);

            insertAfter(editLine, line);

            enableButtons(false);
        }

        function resetNuevoPaso()
        {
            const li = document.getElementById('new-paso-li');
            const btnAddPaso = document.getElementById('btnAddPaso');        
            const input = document.getElementById('new-paso-input');  
            const btnConfirmar = document.querySelector('#new-paso-li .boton--verde');

            li.classList.add('invisible');     
            btnAddPaso.disabled = false;
            btnConfirmar.disabled = true;
            input.value = "";       
        }

        function resetEditarPaso(element)
        {
            element.parentNode.remove();
            enableButtons(true);
        }

        function enableButtons(valor){
            document.querySelectorAll(".drag-list button:not(.editando)").forEach((btn) => {
                btn.disabled = !valor;
            })

            const btn = document.querySelector("#btnAddPaso");
            btn.disabled = !valor;

            document.querySelectorAll(".drag-list__item__handle").forEach((btn)=>{
                if(valor){
                    btn.style.display = "flex";
                }
                else{
                    btn.style.display = "none";
                }
            });

            window.disableDrag = !valor;
        }

        function insertAfter(newNode, referenceNode) {
            referenceNode.parentNode.insertBefore(newNode, referenceNode.nextSibling);
        }
    </script>

@endpush

