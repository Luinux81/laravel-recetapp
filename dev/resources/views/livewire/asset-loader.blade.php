        
<div 
    class="flex flex-wrap gap-3 p-3 rounded-md"
>            
    @foreach ($rutas as $ruta)
        {{-- // TODO: Detectar si el origen al que pertence el paso es publico o no y obtener prefijo para el path(hacer en el controlador) --}}
        
        @if($publico)
            <picture class="relative">
                <button class="absolute boton boton--verde editando invisible" style="width:30px; top:0; left:0; marign:2px;">OK</button>
                <button class="absolute boton boton--rojo editando @if($modo != "edit") invisible @endif" style="width:30px; top:0; right:0; marign:2px;" >X</button>
                <img src="{{ $ruta }}" class="h-40 rounded-md" alt="imagen_paso" />
            </picture>
        @else
            <span>{{ $ruta }} </span>
        @endif
    @endforeach

    {{-- //TODO: Añadir esto aqui para cargar nuevo imagen solo si esta en modo edit --}}
    @if ( $modo == "edit" )
        <form wire:submit.prevent="uploadImage">

            {{-- <x-form.filepond wire:model="imagen" /> --}}

            <x-form.image-upload
                nombre="image-{{ $id_modelo }}"
                titulo=""  
                divStyle="flex-direction: row; flex-wrap: wrap;"    
                imgStyle="height: 10rem; width: auto; padding:0;"   
                modo="edit"
            >
            </x-form.image-upload>


            {{-- <button
                class="botonImagen"
                style="display:none;"
            >
                <x-fas-camera style="height:40px;">
                </x-fas-camera>
                <x-fas-plus class="icono-modificador" style="margin-left: -10px; margin-bottom: -30px;"></x-fas-plus>
            </button> --}}

        </form>
    @endif
</div>
