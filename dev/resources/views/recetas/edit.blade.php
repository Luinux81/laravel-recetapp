<x-app-layout>

    <x-slot name="header">
        <div class="flex flex-row justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Editar receta') }}
            </h2>
            <a href="{{ route('recetas.index') }}" class="boton boton--rojo">Cancelar</a>
        </div>
    </x-slot>

    <x-content>
        <form method="post" action="{{ route('recetas.update', ['receta'=>$receta->id])}}" class="flex flex-col">
            @csrf
            @method('PUT')

            <x-form.input-text 
                nombre="nombre" 
                titulo="Nombre" 
                tipo="text" 
                valor="{{ old('nombre')?old('nombre'):$receta->nombre}}"
            >  </x-form.input-text>

            <x-form.input-text 
                nombre="descripcion" 
                titulo="Descripcion" 
                tipo="text"
                valor="{{ old('descripcion')?old('descripcion'):$receta->descripcion}}"
            >
            </x-form.input-text>

            <x-form.input-text 
                nombre="calorias" 
                titulo="calorias" 
                tipo="number"
                valor="{{ old('calorias')?old('calorias'):$receta->calorias }}"
            >
            </x-form.input-text>
            
            <div class="flex flex-col">
                <label for="categoria">Categoria</label>
                <select id="categoria" name="categoria">                
                    <option value="" @if(empty($receta->cat_id)) selected @endif >Ninguna</option>
                    @foreach ($categorias as $cat)
                        <option value="{{$cat->id}}" @if($receta->cat_id == $cat->id) selected @endif >{{$cat->nombre}}</option>
                    @endforeach
                </select>
            </div>

            <input class="boton boton--azul m-6" type="submit" value="Guardar"/>
        </form>


        <section class="seccion-ingredientes my-2">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Ingredientes</h2>
            
            <table>
                <thead>
                    <tr>
                        <th>Nombre</th><th>Cantidad</th><th>Unidades</th><th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($receta->ingredientes as $i)
                        <tr>
                            <td>{{ $i->nombre }}</td>
                            <td class="text-center">{{ $i->pivot->cantidad }}</td>
                            <td class="text-center">{{ $i->pivot->unidad_medida }}</td>
                            <td class="flex gap-3">
                                <a href="{{ route('recetas.ingrediente.edit',['receta'=>$receta->id, 'ingrediente'=>$i->id])}}" class="boton boton--gris">Editar</a>
                                <form method="post" action="{{ route('recetas.ingrediente.destroy', ['receta'=>$receta->id, 'ingrediente'=>$i->id]) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="boton boton--rojo">Borrar</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <a href="{{ route('recetas.ingrediente.create',['receta'=>$receta->id]) }}" class="boton boton--gris my-3">Añadir</a>

        </section>

        <section class="seccion-pasos my-2">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Pasos</h2>
            
            <table>
                <thead>
                    <tr>
                        <th>Orden</th><th>Texto</th><th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($receta->pasos as $p)
                        <tr>
                            <td class="text-center">{{ $p->orden }}</td>
                            <td>{{ $p->texto }}</td>                            
                            <td class="flex gap-3" style=>
                                <a href="{{ route('recetas.paso.edit',['receta'=>$receta->id, 'paso'=>$p->id])}}" class="boton boton--gris">Editar</a>
                                <form method="post" action="{{ route('recetas.paso.destroy', ['receta'=>$receta->id, 'paso'=>$p->id]) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="boton boton--rojo">Borrar</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <a href="{{ route('recetas.paso.create',['receta'=>$receta->id]) }}" class="boton boton--gris">Añadir</a>

        </section>
    </x-content>

</x-app-layout>