<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

        <!-- Styles -->
        <link rel="stylesheet" data-algo="algo" href="{{ url('/') . mix('css/app.css') }}">

        <link>
        @livewireStyles
        @powerGridStyles

        <!-- Scripts -->
        <script src="{{ url('/') . mix('js/app.js') }}" defer></script>
    </head>
    <body class="font-sans antialiased">
        <x-jet-banner />

        <div class="min-h-screen bg-gray-100">
            @livewire('navigation-menu')

            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>

        @stack('modals')
        

        @livewireScripts
        @powerGridScripts
        
        @stack('custom-scripts')

        <script>
            if(Livewire){
                Livewire.on("msg-ok",(msg)=>{                    
                    if(Notyf){
                        Notyf.open({
                            duration: 2500,
                            position:{
                                x: 'right',
                                y: 'top'
                            },
                            type: 'info',
                            message: msg,
                        });
                    }
                });
                Livewire.on("msg-err",(msg)=>{
                    if(Notyf){
                        Notyf.open({
                            duration: 2500,
                            position:{
                                x: 'right',
                                y: 'top'
                            },
                            type: 'error',
                            message: msg,
                        });
                    }
                });
            }
        </script>

        <script>
            @if(session('notificacion'))
                window.addEventListener('DOMContentLoaded',(event)=>{
                    notifica('{{ session('notificacion')->tipo }}','{{ session('notificacion')->mensaje }}');
                });
            @endif

            function notifica(tipo,msg){
                if(Notyf){
                    Notyf.open({
                        duration: 3000,
                        position:{
                            x: 'right',
                            y: 'top'
                        },
                        type: tipo,
                        message: msg,
                    });
                }
                else{
                    alert(msg);
                }
            }
        </script>

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
    </body>
</html>
