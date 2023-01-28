<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;

class ApiController extends Controller
{
    /**
     * Search for a pokemon calling the /pokemon/{name} endpoint of https://pokeapi.co/
     * @param string $name
     * @return \Illuminate\Http\JsonResponse
     */
    public function searchPokemon(string $name)
    {
        $response = [
            "status" => 404,
            "html" => ""
        ];

        $name = strtolower(trim($name));
        if (!$name) {
            return response()->json($response);
        }

        $searchPokemonUrl = config("constants.POKEAPI_BASE_URL") . config("constants.POKEAPI_POKEMON_ENDPOINT") . $name;

        try {
            $apiResponse = Http::get($searchPokemonUrl);
            $response["status"] = $apiResponse->status();

            $html = "";
            if ($response["status"] === 404) {
                $html = "<span class='m-auto text-center'>No se encontró el pokemón $name </span>";
            } else {
                $html = view("components.pokemon-preview", ["preview" => $apiResponse->json()])->render();
            }
            $response["html"] = $html;

        } catch (\Exception $e) {
            error_log($e->getMessage());
        }

        return response()->json($response, $response["status"]);
    }
}
