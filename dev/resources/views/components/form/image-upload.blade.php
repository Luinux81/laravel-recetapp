@props(['nombre', 'titulo', 'imagen' => '', 'icono' => '', 'showFilename' => false])

<div class="form-component form-component--imageUpload flex flex-col">
    
    <label for="{{ $nombre }}">
        {{ ucfirst($titulo) }} @error('{{ $nombre }}')<span class="text-red-500">*</span>@enderror
    </label>

    @if($imagen)
        <img src="{{ '/storage/' . $imagen }}" id="{{ $nombre }}-imagen" class="col-md-8 pl-0 py-3 pr-0 w-96">
    @else
        <img src="#" id="{{ $nombre }}-imagen" class="invisible col-md-8 pl-0 py-3 pr-0 w-96">
    @endif

    <x-form.boton-image-upload
        nombre="{{ $nombre }}"
        icono="{{ $icono }}"
    >
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

                const filename = document.getElementById(id).files[0].name;
                document.getElementById(id+'-filename').innerHTML = filename;
            }
            reader.readAsDataURL(event.target.files[0]);
        }
    </script>
</div>