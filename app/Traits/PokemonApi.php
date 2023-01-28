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

    /**
     * Get the move data
     * @param mixed $url
     * @return \Illuminate\Http\Client\Response|null
     */
    public function getMove($url)
    {
        $data = null;
        if (
            !$url || !str_contains(
                $url,
                config("constants.POKEAPI_BASE_URL") . config("constants.POKEAPI_MOVE_ENDPOINT")
            )
        ) {
            return $data;
        }

        try {
            $data = Http::get($url);
        } catch (\Exception $e) {
            error_log($e->getMessage());
        }
        return $data;
    }

    /**
     * Returns the evolution chain url
     * @param mixed $speciesUrl
     * @return mixed
     */
    public function getEvolutionChainUrl($speciesUrl)
    {
        $data = "";
        if (
            !$speciesUrl || !str_contains(
                $speciesUrl,
                config("constants.POKEAPI_BASE_URL") . config("constants.POKEAPI_SPECIES_ENDPOINT")
            )
        ) {
            return $data;
        }

        try {
            $speciesResponse = Http::get($speciesUrl);
            if ($speciesResponse) {
                $data = $speciesResponse->json()["evolution_chain"]["url"];
            }
        } catch (\Exception $e) {
            error_log($e->getMessage());
        }
        return $data;
    }

    /**
     * Get the move data
     * @param mixed $url
     * @return \Illuminate\Http\Client\Response|null
     */
    public function getEvolutionChain($url)
    {
        $data = null;
        if (
            !$url || !str_contains(
                $url,
                config("constants.POKEAPI_BASE_URL") . config("constants.POKEAPI_EVOLUTION_CHAIN_ENDPOINT")
            )
        ) {
            return $data;
        }

        try {
            $data = Http::get($url);
        } catch (\Exception $e) {
            error_log($e->getMessage());
        }
        return $data;
    }
}
