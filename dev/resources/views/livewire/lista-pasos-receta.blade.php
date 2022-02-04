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
            <li class="drag-list__item" wire:sortable.item="{{ $paso->id }}" wire:key="paso-{{ $paso->id }}" data-key="paso-{{ $paso->id }}">
                
                <button class="drag-list__item__handle" wire:sortable.handle><x-fas-ellipsis-v /></button>
                
                <h4 class="drag-list__item__texto">{{ $paso->texto }}</h4>
                
                <div class="drag-list__item__acciones flex gap-3">
                    <a href="{{ route('recetas.paso.edit',['receta'=>$receta->id, 'paso'=>$paso->id])}}" class="boton boton--gris invisible">
                        <x-fas-edit style="width: 15px;"/>
                        <span>Editar</span>
                    </a>    

                    <button class="boton boton--gris" onclick="showEditor({{ $paso->id }})">
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
        }

        function insertAfter(newNode, referenceNode) {
            referenceNode.parentNode.insertBefore(newNode, referenceNode.nextSibling);
        }
    </script>
</div>

