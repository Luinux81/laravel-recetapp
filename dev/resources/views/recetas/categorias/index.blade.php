<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between align-top">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Categorias de recetas') }}
            </h2>
            <a class="boton boton--azul" href='{{ route('recetas.categoria.create') }}'>Nuevo</a>
        </div>
    </x-slot>

    <x-content>
        <table>
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Descripcion</th>
                    <th>Categoria Superior</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
            @foreach ($categorias as $c)
                <tr>
                    <td>{{$c->nombre}}</td>
                    <td>{{$c->descripcion}}</td>
                    <td>
                        @if (!empty($c->catParent_id))
                            {{ \App\Models\CategoriaReceta::find($c->catParent_id)->nombre }}
                        @endif
                    </td>
                    <td class="p-3 flex flex-row flex-between gap-2">
                        <a href="{{ route('recetas.categoria.edit',['categoria'=>$c->id])}}" class="boton boton--gris">Editar</a>
                        <form method="post" action="{{ route('recetas.categoria.destroy',['categoria'=>$c->id]) }}">
                            @csrf
                            @method('DELETE')
                            {{-- <input type="submit" class="boton boton--rojo" value="Borrar" onsumbit="confirmarBorrado(event)" /> --}}
                            <button class="boton boton--rojo" onclick="confirmarBorrado(event)">Borrar</button>
                        </form>
                        
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
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

