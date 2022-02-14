@props([
    'nombre', 
    'titulo', 
    'imagen' => '', 
    'icono' => '', 
    'showFilename' => false, 
    'imgStyle' => '', 
    'divStyle' => '',
    'modo' => 'edit'

])

<div class="form-component form-component--imageUpload flex flex-col" style="{{ $divStyle }}">
    
    <label for="{{ $nombre }}">
        {{ ucfirst($titulo) }} @error('{{ $nombre }}')<span class="text-red-500">*</span>@enderror
    </label>

    @if($imagen)
        <img src="{{ '/storage/' . $imagen }}" id="{{ $nombre }}-imagen" class="col-md-8 pl-0 py-3 pr-0 w-96">
        {{-- <picture class="relative">
            <button 
                class="absolute boton boton--verde editando" 
                style="width:30px; top:0; left:0; marign:2px;"
            >
                OK
            </button>
            <button 
                class="absolute boton boton--rojo editando @if($modo != "edit") invisible @endif" 
                style="width:30px; top:0; right:0; marign:2px;" 
                data-function="cancelar"
                {{-- onclick="cancelar(event,'{{ $nombre }}')" -/-}}
            >
                X
            </button>
            <img src="{{ $imagen }}" id="{{ $nombre }}-imagen" class="col-md-8 pl-0 py-3 pr-0 w-96" style="{{ $imgStyle }}">
        </picture> --}}
    @else
        <img src="#" id="{{ $nombre }}-imagen" class="invisible col-md-8 pl-0 py-3 pr-0 w-96" style="{{ $imgStyle }}">
        {{-- <picture class="relative">
            <button 
                class="absolute boton boton--verde editando invisible" 
                style="width:30px; top:0; left:0; marign:2px;"
            >
                OK
            </button>
            <button 
                class="absolute boton boton--rojo editando invisible" 
                style="width:30px; top:0; right:0; marign:2px;" 
                data-function="cancelar"
                {{-- onclick="cancelar(event, '{{ $nombre }}')" -/-}}
            >
                X
            </button>
            <img src="#" id="{{ $nombre }}-imagen" class="invisible col-md-8 pl-0 py-3 pr-0 w-96" style="{{ $imgStyle }}">
        </picture> --}}
    @endif

    <x-form.boton-image-upload
        nombre="{{ $nombre }}"
        icono="{{ $icono }}"
    >
        {{ $slot }}
    </x-form.boton-image-upload>

    <span id="{{ $nombre }}-filename" class="text-gray-400 @if(!$showFilename) hidden @endif"></span>

    <input 
        id="{{ $nombre }}" 
        name="{{ $nombre }}" 
        type="file" 
        @if($imagen != "")
            value="{{ $imagen }}"
        @endif
        onchange="loadFile('{{ $nombre }}',event)"
        class="hidden"
        {{ $attributes }}
    />
    
    @error('{{ $nombre }}')
        <div class="alert alert-danger text-red-500">{{ $message }}</div>
    @enderror

    <script>
        function loadFile(id, event){
            var reader = new FileReader();
            reader.onload = function (){
                const img = document.getElementById(id+'-imagen');
                img.src = reader.result;
                img.classList.remove('invisible');

                let btn = img.previousSibling;

                while(btn != null){                    
                    if(btn.tagName == "BUTTON"){
                        btn.classList.remove("invisible");
                        
                        if(btn.getAttribute('data-function') == "cancelar"){
                            btn.addEventListener("click",(event) => {
                                event.preventDefault();
                                cancelar(img);
                            });
                        }
                    }

                    btn = btn.previousSibling;
                }

                const filename = document.getElementById(id).files[0].name;
                document.getElementById(id+'-filename').innerHTML = filename;
            }
            reader.readAsDataURL(event.target.files[0]);
        }

        
        function cancelar(img){
            img.classList.add("invisible");

            let btn = img.previousSibling;
            while(btn != null){
                if(btn.tagName == "BUTTON"){
                    btn.classList.add("invisible");
                }
                btn = btn.previousSibling;
            }
        }
    </script>
</div>