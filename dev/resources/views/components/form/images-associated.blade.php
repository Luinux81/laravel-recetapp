@props(['nombre', 'titulo', 'images', 'action_add', 'action_delete', 'callback'])

<div class="form-component form-component--images">
    <div>
        @foreach ($images as $key => $image)
            <div>
                <img src='/storage/{{ $image }}' alt="" style="height:250px;"/>
                <form method="post" action="" >
                    @csrf
                    @method('delete')
                    <input type="hidden" name="asset_id" value="{{ $key }}" />
                    <button type="submit">Borrar</button>
                </form>
            </div>
        @endforeach
    </div>

    <form 
        method="post"
        action="{{ $action_add }}"
        enctype="multipart/form-data"
    >
        @csrf

        <x-form.image-upload
            nombre="{{ $nombre }}"
            titulo="{{ $titulo }}"
            accept="image/*"
            imagen=""
        >
        </x-form.image-upload>
        <input type="hidden" value="{{ $callback }}" name="callback" />

        <button type="submit" class="boton boton--azul">Guardar imagen</button>
    </form>
</div>