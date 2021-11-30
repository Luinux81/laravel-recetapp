<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-row justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Ingredientes') }}
            </h2>
            <a href="{{ route('ingredientes.create') }}" class="boton boton--azul">Nuevo</a>
        </div>
    </x-slot>

    <x-content>
        <div class="flex justify-between">
            <div class="flex flex-row items-center gap-3">
                <a href="{{ route('ingredientes.index')}}">TODOS</a>
                @foreach (range('A','Z') as $letra)
                    <a href="{{ route('ingredientes.index')}}?filtro=alf&valor_filtro={{$letra}}">{{$letra}}</a>    
                @endforeach
            </div>
            <div>
                <select onchange="filtrarPorCategoria(event);">
                    <option value="" selected disabled>Filtro categoria</option> 
                    <option value="">Ninguno</option> 
                    @foreach ($categorias as $cat)
                        <option value="{{$cat->id}}">{{$cat->nombre}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <table>
            <thead>
                <tr>
                    <th class="text-left w-3/5">Nombre</th>
                    <th class="text-left">Categoria</th>
                    <th>Calorias (100g)</th>                    
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
            @foreach ($ingredientes as $i)
                <tr>
                    <td>{{html_entity_decode($i->nombre)}}</td>
                    <td>{{$i->categoria->nombre ?? "" }}</td>
                    <td class="text-center">{{$i->calorias}}</td>                    
                    <td class="p-3 flex flex-row flex-between gap-2">
                        
                        @canany(['public_edit','public_destroy'])
                            @if (($i->user_id != NULL && $i->user_id == Auth::user()->id) || Auth::user()->can('public_edit'))
                                <a href="{{ route('ingredientes.edit', ['ingrediente'=>$i->id]) }}" class="boton boton--gris">Editar</a>
                            @endif

                            @if (($i->user_id != NULL && $i->user_id == Auth::user()->id) || Auth::user()->can('public_destroy'))
                                <x-form.boton-post
                                    url="{{ route('ingredientes.destroy',['ingrediente'=>$i->id]) }}"
                                    metodo="DELETE"
                                    class="boton boton--rojo"
                                    onclick="confirmarBorrado(event)"
                                >
                                    Borrar
                                </x-form.boton-post>
                            @endif
                        @endcanany
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

    <script>
        function filtrarPorCategoria(event){
            location.href="{{ route('ingredientes.index') }}?filtro=categoria&valor_filtro="+event.target.value;
        }
    </script>
    @endpush

</x-app-layout>