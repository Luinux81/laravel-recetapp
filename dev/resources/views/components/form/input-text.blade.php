@props(['nombre', 'titulo', 'tipo'=>'text'])

<div class="flex flex-col">
    
    <label for="{{ $nombre }}">
        {{ ucfirst($titulo) }} @error('{{ $nombre }}')<span class="text-red-500">*</span>@enderror
    </label>

    <input id="{{ $nombre }}" name="{{ $nombre }}" type="{{ $tipo }}" />
    
    @error('{{ $nombre }}')
        <div class="alert alert-danger">{{ $message }}</div>
    @enderror

</div>