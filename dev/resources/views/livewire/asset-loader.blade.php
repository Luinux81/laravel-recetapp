<div 
    class="flex flex-wrap gap-3 p-3 rounded-md"
>            
    @foreach ($assets as $asset)
        {{-- // TODO: Detectar si el origen al que pertence el paso es publico o no y obtener prefijo para el path(hacer en el controlador) --}}
        
        @if($publico)
            <picture class="relative">
                <button 
                    class="absolute boton boton--rojo editando @if($modo != "edit") invisible @endif flex" 
                    style="width:30px; top:0; right:0; marign:2px; justify-content:center; align-items:center;" 
                    onclick="eliminar('{{ $id_modelo }}','{{ $asset->id }}')"
                >
                    X
                </button>
                <img src="/storage/{{ $asset->ruta }}" class="h-40 rounded-md" alt="imagen_paso" />
            </picture>
        @else
            <span>{{ $asset->ruta }} </span>
        @endif
    @endforeach

    {{-- //TODO: Añadir esto aqui para cargar nuevo imagen solo si esta en modo edit --}}
    @if ( $modo == "edit" )
        <div class="flex flex-wrap gap-3">
            <picture class="relative invisible editando">
                <button 
                    class="absolute boton boton--verde editando" 
                    style="width:30px; top:0; right:0; marign:2px; "
                    onclick="submit({{$id_modelo}})"
                    id="btn-ok-new-imagen-{{$id_modelo}}"
                >OK</button>

                <button 
                    class="absolute boton boton--rojo editando" 
                    style="width:30px; top:0; right:0; marign:2px; display: none;" 
                    onclick="cancelar({{$id_modelo}})"
                    id="btn-cancel-new-imagen-{{$id_modelo}}"
                >X</button>

                <img src="#" id="new-imagen-paso-{{$id_modelo}}" class="h-40 rounded-md" alt="imagen_paso" />
            </picture>

            <input
                type="file"
                id="loadImagen-{{$id_modelo}}"
                wire:model="imagen"
                class="invisible"
                onchange="loadFile('new-imagen-paso-{{$id_modelo}}',event)"
            />
            <x-form.boton-image-upload nombre="loadImagen-{{$id_modelo}}">
                <x-fas-camera style="width:20px;"></x-fas-camera>
            </x-form.boton-image-upload>

        </div>

        @push('custom-scripts')
            <script>

                function loadFile(id, event){
                    var reader = new FileReader();
                    reader.onload = function (){
                        const img = document.getElementById(id);
                        img.src = reader.result;
                        img.parentNode.classList.remove('invisible');

                        let btn = img.previousSibling;

                        while(btn != null){                    
                            if(btn.tagName == "BUTTON"){
                                btn.classList.remove("invisible");
                            }
                            btn = btn.previousSibling;
                        }
                    }
                    reader.readAsDataURL(event.target.files[0]);
                }

                function cancelar(id){
                    const imagen = document.getElementById('new-imagen-paso-'+id);
                    const btn1 = document.getElementById('btn-ok-new-imagen-'+id);
                    const btn2 = document.getElementById('btn-ok-new-imagen-'+id);
                    const input = document.getElementById("loadImagen-"+id)
                    
                    input.parentNode.reset();
                    const clon = input.cloneNode(true);
                    clon.value = "";
                    input.replaceWith(clon);

                    btn1.classList.add("invisible");
                    btn2.classList.add("invisible");
                    imagen.parentNode.classList.add("invisible");
                }

                async function submit(id){
                    const input = document.getElementById("loadImagen-"+id)
                    const archivo = input.files[0];
                    
                    const toBase64 = file => new Promise((resolve, reject) => {
                        const reader = new FileReader();
                        reader.readAsDataURL(file);
                        reader.onload = () => resolve(reader.result);
                        reader.onerror = error => reject(error);
                    });

                    Livewire.emit('uploadImagen:'+id, await toBase64(archivo));
                }

                function eliminar(idModelo, idAsset){
                    Livewire.emit('deleteAsset:'+idModelo, idAsset);
                }

            </script>
        @endpush
    @endif
</div>
