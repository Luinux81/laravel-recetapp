<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Nueva categoria de recetas') }}
        </h2>
    </x-slot>
    <x-content>
        <form class="flex flex-col" method="post" action="{{ route('recetas.categoria.store')}}" >
            @csrf
            <div class="flex flex-col">
                <label for="nombre">
                    Nombre @error('nombre')<span class="text-red-500">*</span>@enderror
                </label>
                <input id="nombre" name="nombre" type="text" />
                @error('nombre')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="flex flex-col">
                <label for="descripcion">Descripcion</label>
                <input id="descripcion" name="descripcion" type="text" />
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