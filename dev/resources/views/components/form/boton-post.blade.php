@props(['url', 'metodo'])

<form method="post" action="{{ $url }}">
    @csrf
    
    <input type="hidden" name="_method" value="{{ $metodo }}" />

    <button type="submit" {{$attributes}}>
        {{ $slot }}
    </button>
</form>
