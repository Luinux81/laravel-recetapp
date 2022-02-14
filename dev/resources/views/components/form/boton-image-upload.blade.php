@props(['nombre', 'icono' => ''])


<label
    for="{{ $nombre }}"
    class="botonImagen flex items-center justify-center h-40 w-40 cursor-pointer bg-gray-200 hover:bg-gray-300 border border-gray-300 rounded-md">
    @if($icono == "")
        {{-- <img src='{{ $icono }}' /> --}}
        {{ $slot }}
    @else
        <h1 class="font-bold text-4xl">+</h1>
    @endif
</label>
