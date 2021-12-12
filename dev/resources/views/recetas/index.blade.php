<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-row justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Recetas') }}
            </h2>
            <a href="{{ route('recetas.create') }}" class="boton boton--azul">Nuevo</a>
        </div>
    </x-slot>

    <x-content>
        <table>
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Descripcion</th>
                    <th>Calorias</th>                    
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
            @foreach ($recetas as $r)
                <tr>
                    <td>{{$r->nombre}}</td>
                    <td>{{$r->descripcion}}</td>
                    <td>{{$r->calorias}}</td>
                    
                    <td class="p-3 flex flex-row flex-between gap-2">
                        @if(auth()->user()->can('seeder_save'))                            
                            <x-form.boton-post
                                url="{{ route('admin.seed.receta',['receta'=>$r->id]) }}"
                                class="boton boton--azul"
                                metodo="post"
                            >
                                Seeder
                            </x-form.boton-post>
                        @endif
                        <a href="{{ route('recetas.show', ['receta'=>$r->id]) }}" class="boton boton--gris">Ver</a>
                        <a href="{{ route('recetas.edit', ['receta'=>$r->id]) }}" class="boton boton--gris">Editar</a>
                        <x-form.boton-post
                            url="{{ route('recetas.destroy',['receta'=>$r->id]) }}"
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