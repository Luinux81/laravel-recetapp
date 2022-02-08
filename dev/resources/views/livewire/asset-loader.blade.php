<div>
    @if ($modelo == null)
        Error: El modelo no está soportado por el cargador de assets
    @else
        
        <div class="flex flex-wrap gap-3 p-3 rounded-md">            
            @foreach ($rutas as $ruta)
                {{-- // TODO: Detectar si el origen al que pertence el paso es publico o no y obtener prefijo para el path(hacer en el controlador) --}}
                
                @if($publico)
                    <img src="{{ $ruta }}" class="h-40 rounded-md" alt="imagen_paso" />
                @else
                    <span>{{ $ruta }} </span>
                @endif
            @endforeach

            {{-- //TODO: Añadir esto aqui para cargar nuevo imagen solo si esta en modo edit --}}
            @if ( $modo == "edit" )
                <button
                    class="botonImagen"
                >
                    <x-fas-camera style="height:40px;">                    
                    </x-fas-camera>
                    <x-fas-plus class="icono-modificador" style="margin-left: -10px; margin-bottom: -30px;"></x-fas-plus>
                </button>
            @endif
        </div>
        
    @endif
</div>
