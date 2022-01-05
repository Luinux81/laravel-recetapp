<x-app-layout>
<x-slot name="header">
    <div class="flex flex-row justify-between">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Editar paso en receta {{ $receta->nombre }}
        </h2>

        <div class="flex gap-5">
            <button class="boton boton--azul" onclick="document.getElementById('update_form_paso').submit();">Guardar</button>
            <a href="{{ route('recetas.edit',['receta'=>$receta->id])}}" class="boton boton--rojo">Cancelar</a>
        </div>
    </div>
</x-slot>
<x-content>
    <form 
        id="update_form_paso"
        method="post" 
        action="{{ route('recetas.paso.update',['receta'=>$receta->id, 'paso'=>$paso->id]) }}" 
        class="flex flex-col"
    >
        @csrf
        @method('PUT')
        <x-form.input-text
            nombre="orden" 
            titulo="orden" 
            tipo="number" 
            min="1"
            max="{{ $receta->pasos()->count() }}"
            valor="{{ old('orden') ? old('orden') : $paso->orden }}"
        >
        </x-form.input-text>

        <x-form.input-text
            nombre="texto" 
            titulo="texto" 
            tipo="text" 
            valor="{{ old('texto') ? old('texto') : $paso->texto }}"
        >
        </x-form.input-text>        
    </form>

    <div>
        @foreach ($assets as $asset)
            <div class="relative">
                <img src="{{ \App\Helpers\Tools::getImagen64($asset->ruta) }}" alt="" />
                <form method="post" action="{{ route('recetas.pasos.asset.destroy', compact(['receta', 'paso', 'asset'])) }}">
                    @csrf
                    @method('delete')
                    <button type="submit" class="boton boton--rojo absolute top-0 left-0">X</button>
                </form>
            </div>
        @endforeach
    </div>
    <form method="post" action="{{ route('recetas.pasos.asset.store', compact(['receta', 'paso'])) }}" enctype="multipart/form-data">
        @csrf
        <x-form.image-upload
            nombre="imagen"
            titulo="imagen"
            imagen=""
            accept="image/*"
        >
        </x-form.image-upload>
        <button type="submit" class="boton boton--azul">Añadir imagen</button>
    </form>

    <div class="flex justify-center gap-5 m-6">
        <button class="boton boton--azul" onclick="document.getElementById('update_form_paso').submit();">Guardar</button>
        <a href="{{ route('recetas.edit',['receta'=>$receta->id])}}" class="boton boton--rojo">Cancelar</a>
    </div>
</x-content>
</x-app-layout>