@props(['nombre', 'titulo'])

<div class="form-component form-component--select flex flex-col">
    
    <label for="{{ $nombre }}">
        {{ ucfirst($titulo) }} @error('{{ $nombre }}')<span class="text-red-500">*</span>@enderror
    </label>
    
    <select id="{{ $nombre }}" name="{{ $nombre }}" {{ $attributes->merge(['class'=>'form-select bg-gray-200 rounded-md mb-3']) }}>                
        {{ $slot }}
    </select>

</div>