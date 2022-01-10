<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Pendientes de publicación') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                Review de cola de publicación
                <table class="w-100">
                @foreach ($modelos as $key => $modelo)
                    <tr>
                        @php
                            $clase = str_replace('App\Models\\', '', get_class($modelo));
                        @endphp

                        <td>{{ $clase }}</td>
                        <td>{{ $modelo->nombre }}</td>
                        <td>{{ $modelo->descripcion }}</td>
                        <td class="flex gap-5">
                            <a class="boton boton--gris" target="_blank" href="{{ route('admin.review-publish', ['id' => $key]) }}">Revisar</a>
                            <x-form.boton-post
                                url="{{ route('admin.confirm-publish', ['id' => $key]) }}"
                                metodo="post"
                                class="boton boton--azul"
                            >
                                Confirmar
                            </x-form.boton-post>
                            <x-form.boton-post
                                url="{{ route('admin.deny-publish', ['id' => $key]) }}"
                                metodo="post"
                                class="boton boton--rojo"
                            >
                                Denegar
                            </x-form.boton-post>
                        </td>
                    </tr>
                @endforeach
                </table>
            </div>
        </div>
    </div>
</x-app-layout>