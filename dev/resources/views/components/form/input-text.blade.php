@props(['nombre', 'titulo', 'valor' => '', 'tipo'=>'text'])

<div class="form-component form-component--input flex flex-col">
    
    <label for="{{ $nombre }}">
        {{ ucfirst($titulo) }} @error('{{ $nombre }}')<span class="text-red-500">*</span>@enderror
    </label>

    <input 
        id="{{ $nombre }}" 
        name="{{ $nombre }}" 
        type="{{ $tipo }}" 
        @if($valor != "")
            value="{{ $valor }}"
        @endif
        {{ $attributes }}
    />
    
    @error('{{ $nombre }}')
        <div class="alert alert-danger">{{ $message }}</div>
    @enderror

</div>