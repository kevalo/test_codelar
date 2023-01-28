@if (!empty($preview))
    <strong class="text-center text-xl uppercase">{{ $preview['name'] }}</strong>
    <img src="{{ $preview['sprites']['other']['official-artwork']['front_default'] }}" alt="Imagen del pokemón"
        class="border-b-2 border-sky-500 mb-3">
    <div class="flex">
        <div class="flex flex-col">
            <span>Estatura: {{ $preview['height'] * 10 }}cm</span>
            <span>Peso: {{ $preview['weight'] / 10 }}kg</span>
        </div>
        <button class="bg-green-600 text-white ml-auto px-3 rounded-md">AGREGAR</button>
    </div>
@else
    <span class="text-center m-auto">Busca un pokemón para previsualizarlo aquí</span>
@endif
