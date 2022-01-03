<x-app-layout>

    <x-slot name="header">
        <div class="flex flex-row justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Editar receta') }}
            </h2>
            <div class="flex gap-3">
                <div class="flex flex-row justify-between gap-5">
                    <button class="boton boton--azul" onclick="document.getElementById('update_form_receta').submit();">Guardar</button>
                    
                    <a href="{{ route('recetas.index') }}" class="boton boton--gris">Cancelar</a>
                    
                    <x-form.boton-post
                        url="{{ route('recetas.destroy',['receta'=>$receta->id]) }}"
                        metodo="DELETE"
                        onclick="confirmarBorrado(event)"
                        class="boton boton--rojo"
                    >
                    Borrar
                    </x-form.boton-post>
                </div>
            </div>
        </div>
    </x-slot>

    <x-content>
        <form 
            method="post" 
            action="{{ route('recetas.update', ['receta'=>$receta->id])}}" 
            class="flex flex-col"
            enctype="multipart/form-data"
            id="update_form_receta"
        >
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
                min="0"
                valor="{{ old('calorias')?old('calorias'):$receta->calorias }}"
            >
            </x-form.input-text>
            
            <x-form.input-text 
                nombre="raciones" 
                titulo="raciones" 
                tipo="number"
                min="0"
                valor="{{ old('raciones')?old('raciones'):$receta->raciones }}"
            >
            </x-form.input-text>

            <x-form.input-text 
                nombre="tiempo" 
                titulo="tiempo" 
                tipo="text"
                valor="{{ old('tiempo')?old('tiempo'):$receta->tiempo }}"
            >
            </x-form.input-text>


            <x-form.select nombre="categoria" titulo="Categoria">
                <option 
                    value="" 
                    @if(empty($receta->cat_id)) selected @endif 
                >
                Ninguna
                </option>

                @foreach ($categorias as $cat)
                    <option 
                        value="{{$cat->id}}" 
                        @if($receta->cat_id == $cat->id) selected @endif 
                    >
                    {{$cat->nombre}}
                    </option>
                @endforeach                
            </x-form.select>

            <x-form.image-upload
                nombre="imagen"
                titulo="imagen"
                imagen="{{ old('imagen')?old('imagen'):$receta->imagen }}"
                accept="image/*"                
            >

            </x-form.image-upload>

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

                                <x-form.boton-post
                                    url="{{ route('recetas.ingrediente.destroy', ['receta'=>$receta->id, 'ingrediente'=>$i->id]) }}"
                                    metodo="DELETE"
                                    class="boton boton--rojo"
                                    onclick="confirmarBorrado(event)"
                                >
                                    Borrar
                                </x-form.boton-post>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <a href="{{ route('recetas.ingrediente.create',['receta'=>$receta->id]) }}" class="boton boton--gris">Añadir</a>

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
                    @foreach($receta->pasos()->get() as $p)
                        <tr>
                            <td class="text-center">{{ $p->orden }}</td>
                            <td>{{ $p->texto }}</td>                            
                            <td class="flex gap-3" style=>
                                <a href="{{ route('recetas.paso.edit',['receta'=>$receta->id, 'paso'=>$p->id])}}" class="boton boton--gris">Editar</a>
                                <x-form.boton-post
                                    url="{{ route('recetas.paso.destroy', ['receta'=>$receta->id, 'paso'=>$p->id]) }}"
                                    metodo="DELETE"
                                    class="boton boton--rojo"
                                    onclick="confirmarBorrado(event)"    
                                >
                                    Borrar
                                </x-form.boton-post>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <a href="{{ route('recetas.paso.create',['receta'=>$receta->id]) }}" class="boton boton--gris">Añadir</a>

        </section>

        <div class="flex justify-center gap-5 m-6">
            <button class="boton boton--azul" onclick="document.getElementById('update_form_receta').submit();">Guardar</button>
            <a href="{{ route('recetas.index') }}" class="boton boton--rojo">Volver</a>
        </div>

    </x-content>

    @push('custom-scripts')
        <script>
            function confirmarBorrado(event)
            {
                event.preventDefault();

                if(typeof window.Swal !== "undefined"){
                    window.Swal.fire({
                        title: 'Confirmar borrado',
                        text: '¿Estás seguro/a de borrar el registro?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText:'Si',
                        confirmButtonAriaLabel: 'Yes',
                        cancelButtonText:'No',
                        cancelButtonAriaLabel: 'No'
                    }).then(function(value){                    
                        if(value.isConfirmed){
                            event.target.parentNode.submit();
                        }                    
                    });
                }
                else{
                    if(confirm("Seguro que quieres borrar el registro?")){
                        event.target.parentNode.submit();
                    }
                }      
            }
        </script>
    @endpush
</x-app-layout>