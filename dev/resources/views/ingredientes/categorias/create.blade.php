<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Categorias de ingredientes') }}
        </h2>
    </x-slot>

    <x-content>
        Formulario para crear nueva categoria
        <form class="flex flex-col" method="post" action="{{ route('ingredientes.categoria.store')}}" >
            @csrf
            <div class="flex flex-col">
                <label for="cat_nombre">
                    Nombre @error('cat_nombre')<span class="text-red-500">*</span>@enderror
                </label>
                <input id="cat_nombre" name="cat_nombre" type="text" />
                @error('cat_nombre')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="flex flex-col">
                <label for="cat_descripcion">Descripcion</label>
                <input id="cat_descripcion" name="cat_descripcion" type="text" />
            </div>

            <div class="flex flex-col">
                <label for="cat_parent">Categoria Superior</label>
                <select id="cat_parent" name="cat_parent">
                    <option value="" selected>Ninguna</option>
                @foreach ($categorias as $cat)
                    <option value="{{$cat->id}}">{{$cat->nombre}}</option>
                @endforeach
                </select>
            </div>

            <input class="boton boton--azul m-6" type="submit" value="Guardar"/>
        </form>
    </x-content>

</x-app-layout>