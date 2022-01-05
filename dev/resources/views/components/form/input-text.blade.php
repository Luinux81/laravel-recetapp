@props(['nombre', 'titulo', 'valor' => '', 'tipo'=>'text'])

<div class="form-component form-component--input flex flex-col">
    
    <label for="{{ $nombre }}">
        {{ ucfirst($titulo) }} 
        @if($errors->has($nombre))
            <span class="text-red-500">*</span>
        @endif
    </label>

    <input 
        id="{{ $nombre }}" 
        name="{{ $nombre }}" 
        type="{{ $tipo }}" 
        @if($valor != "")
            value="{{ $valor }}"
        @else
            value="{{ old($nombre) ? old($nombre) : '' }}"
        @endif        
        @if($tipo == "number")
            @if(!$attributes->has("step"))
                step=".01"
            @endif
        @endif
        {{ $attributes->merge(['class'=>'form-input bg-gray-200 rounded-md mb-3']) }}        
    />
    
    @if($errors->has($nombre))
        <div class="alert alert-danger text-red-500">{{ $errors->first($nombre) }}</div>
    @endif


</div>