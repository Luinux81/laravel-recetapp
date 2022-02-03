<div>
    <ul class="drag-list" wire:sortable="updateOrdenPasos">
        
        @foreach ($receta->pasos()->orderBy('orden')->get() as $paso)
            <li class="drag-list__item" wire:sortable.item="{{ $paso->id }}" wire:key="paso-{{ $paso->id }}">
                
                <button class="drag-list__item__handle" wire:sortable.handle><x-fas-ellipsis-v /></button>
                
                <h4 class="drag-list__item__texto">{{ $paso->texto }}</h4>
                
                <div class="drag-list__item__acciones flex gap-3">
                    <a href="{{ route('recetas.paso.edit',['receta'=>$receta->id, 'paso'=>$paso->id])}}" class="boton boton--gris">
                        <x-fas-edit style="width: 15px;"/>
                        <span>Editar</span>
                    </a>
                    <x-form.boton-post
                        url="{{ route('recetas.paso.destroy', ['receta'=>$receta->id, 'paso'=>$paso->id]) }}"
                        metodo="DELETE"
                        class="boton boton--rojo flex gap-1"
                        onclick="confirmarBorradoPaso(event)"    
                        style="diplay: flex;"
                    >
                        <x-fas-trash-alt style="width: 15px;"/>
                        <span>Borrar</span>
                    </x-form.boton-post>
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

                btnAddPaso.addEventListener("click", () => {
                    btnAddPaso.disabled = true;
                    li.classList.remove('invisible');
                    input.focus();
                });

                btnCancelar.addEventListener("click", () => {
                    btnAddPaso.disabled = false;
                    btnConfirmar.disabled = true;
                    input.value = "";
                    li.classList.add('invisible');
                });

                input.addEventListener("keyup", () => {
                    if(input.value.trim() != ""){
                        btnConfirmar.disabled = false;
                    }
                    else{
                        btnConfirmar.disabled = true;
                    }
                });

                btnConfirmar.addEventListener("click", () => {
                    if(input.value.trim() != ""){
                        const texto = input.value;
                        input.value = "";
                        window.livewire.emit('new-paso-receta', {texto : texto});
                    }
                    else{
                        // Ocultamos item nuevo paso, igual que el boton cancelar
                        btnAddPaso.disabled = false;
                        btnConfirmar.disabled = true;
                        input.value = "";
                        li.classList.add('invisible');
                    }
                });


            });
        })();

    </script>
</div>

