@if (!empty($preview))
    <strong class="text-center text-xl uppercase">{{ $preview['name'] }}</strong>
    <img src="{{ $preview['sprites']['other']['official-artwork']['front_default'] }}" alt="Imagen del pokemón"
        class="border-b-2 border-sky-500 mb-3">
    <div class="flex">
        <form action="{{ route('add_pokemon') }}" method="POST" id="addPokemonForm" class="flex mx-auto">
            @csrf
            <input type="hidden" name="name" value="{{ $preview['name'] }}">
            <button type="submit" class="bg-green-600 text-white rounded-md px-3">AGREGAR</button>
    </div>
@else
    <span class="text-center m-auto">Busca un pokemón para previsualizarlo aquí</span>
@endif
