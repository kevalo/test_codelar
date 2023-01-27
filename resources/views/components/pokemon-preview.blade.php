@if (!empty($preview))
    <strong class="text-center text-xl uppercase">{{ $preview['name'] }}</strong>
    <img src="{{ $preview['sprites']['other']['official-artwork']['front_default'] }}" alt="Imagen del pokemón"
        class="border-b-2 border-sky-500 mb-3">
    <span>Estatura: {{ $preview['height'] * 10 }}cm</span>
    <span>Peso: {{ $preview['weight'] / 10 }}kg</span>
@else
    <span class="text-center m-auto">Busca un pokemón para previsualizarlo aquí</span>
@endif
