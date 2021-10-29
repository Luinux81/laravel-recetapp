@props(['nombre', 'titulo', 'imagen' => ''])

<div class="form-component form-component--imageUpload flex flex-col">
    
    <label for="{{ $nombre }}">
        {{ ucfirst($titulo) }} @error('{{ $nombre }}')<span class="text-red-500">*</span>@enderror
    </label>

    @if($imagen)
        <img src="{{ '/storage/' . $imagen }}" id="{{ $nombre }}-imagen" class="col-md-8 pl-0 py-3 pr-0">
    @else
        <img src="#" id="{{ $nombre }}-imagen" class="invisible col-md-8 pl-0 py-3 pr-0">
    @endif

    <input 
        id="{{ $nombre }}" 
        name="{{ $nombre }}" 
        type="file" 
        @if($imagen != "")
            value="{{ $imagen }}"
        @endif
        onchange="loadFile('{{ $nombre }}',event)"
        {{ $attributes }}
    />
    
    @error('{{ $nombre }}')
        <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    <script>
        function loadFile(id, event){
            var reader = new FileReader();
            reader.onload = function (){
                const img = document.getElementById(id+'-imagen');
                img.src = reader.result;
                img.classList.remove('invisible');
            }
            reader.readAsDataURL(event.target.files[0]);
        }
    </script>
</div>