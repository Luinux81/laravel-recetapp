<div class="relative">    
    <div class="flex items-center justify-between">
        <div class="w-4/12">
            <label for="id_ingrediente" class="flex items-center gap-3">
                @if($selectedModel)
                    <x-fas-check-circle class="icono--boton-md" style="color:green" />
                    {{ $selectedModel["nombre"] }}
                @else
                    <x-fas-times-circle class="icono--boton-md" style="color:red"/>
                    Ningún ingrediente seleccionado
                @endif
            </label>
        </div>

        <div wire:loading style="width:40px;">
            <div class="loader" style="width:40px; height:40px;">
                <span></span>
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>

        <div class="w-7/12">
            <input
                autocomplete="off"
                class="form-input--text w-full"
                placeholder="Buscar ingredientes"
                wire:model = "search"
                wire:keydown = "activaBusqueda"
                wire:keydown.escape = "resetEstado"
                wire:keydown.tab = "resetEstado"
                wire:keydown.arrow-up = "decrementaSelectedIndex"
                wire:keydown.arrow-down = "incrementaSelectedIndex"
                wire:keydown.enter.prevent = "seleccionaIndex"
            >
            
            <input type="hidden" id="{{ $nombre }}" name="{{ $nombre }}" wire:model="selectedModelId" />
        </div>
        
    </div>
    
    @if($search != "")
        @if($busquedaActiva)
            <div class="absolute z-10 list-group bg-white w-full rounded-t-none shadow-lg overflow-y-scroll max-h-40">
                @if(!empty($modelos))
                    @foreach ($modelos as $index => $modelo)
                        <a 
                            href="#" 
                            class="list-item {{ $selectedIndex === $index ? 'highlight':'' }}"
                            wire:click.prevent = "seleccionaIndex({{ $index }})"
                        > 
                            {{ $modelo['nombre'] }} 
                        </a>
                    @endforeach
                @else
                    <div class="list-item">No hay resultados</div>
                @endif
            </div>
        @endif
    @endif
</div>
