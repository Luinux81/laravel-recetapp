@push('custom-styles')
    @livewireStyles
    @powerGridStyles
@endpush

@push('custom-scripts')
    
    @livewireScripts
    @powerGridScripts

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
        <livewire:recetas-table/>
    </x-content>
</x-app-layout>


