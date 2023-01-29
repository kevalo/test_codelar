<div>
    <form action="{{ route('search_pokemon', 'name') }}" method="get" class="flex flex-col" id="searchPokemonForm">
        <label for="name">Agrega un pokemón a tu equipo: </label>
        <input type="text" name="name" id="name" class="border-solid border-2 mt-2 rounded-lg text-center"
            placeholder="Nombre del pokemón" autocomplete="off">
        <button type="submit" class="rounded-lg text-white bg-blue-500 mt-2 py-2 text-lg">BUSCAR</button>
    </form>
</div>
