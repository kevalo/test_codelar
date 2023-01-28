@if (!empty($preview))
    <strong class="text-center text-xl uppercase">{{ $preview['name'] }}</strong>
    <img src="{{ $preview['sprites']['other']['official-artwork']['front_default'] }}" alt="Imagen del pokemón"
        class="border-b-2 border-sky-500 mb-3">
    <div class="flex">
        <div class="flex flex-col">
            <span>height: {{ $preview['height'] * 10 }}cm</span>
            <span>weight: {{ $preview['weight'] / 10 }}kg</span>
        </div>
        <form action="{{ route('add_pokemon') }}" method="POST" id="addPokemonForm" class="flex ml-auto">
            @csrf
            <input type="hidden" name="name" value="{{ $preview['name'] }}">
            <button type="submit" class="bg-green-600 text-white rounded-md  px-3">AGREGAR</button>
    </div>
@else
    <span class="text-center m-auto">Busca un pokemón para previsualizarlo aquí</span>
@endif
