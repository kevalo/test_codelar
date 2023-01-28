<?php

namespace App\Traits;

use Illuminate\Support\Facades\Http;

/**
 * Methods to consume the Pokeapi (https://pokeapi.co/docs/v2)
 */
trait PokemonApi
{
    /**
     * Search for a pokemon calling the /pokemon/{name} endpoint
     * @param mixed $name
     * @return \Illuminate\Http\Client\Response|null
     */
    public function getPokemon($name)
    {
        $data = null;
        if (!$name) {
            return $data;
        }

        try {
            $searchPokemonUrl = config("constants.POKEAPI_BASE_URL")
                . config("constants.POKEAPI_POKEMON_ENDPOINT") . $name;
            $data = Http::get($searchPokemonUrl);
        } catch (\Exception $e) {
            error_log($e->getMessage());
        }
        return $data;
    }
}
