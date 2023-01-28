<?php

namespace App\Http\Controllers;

use App\Traits\PokemonApi;

class MainController extends Controller
{

    use PokemonApi;

    /**
     * Get the pokemon data and returns the preview html
     * @param string $name
     * @return \Illuminate\Http\JsonResponse
     */
    public function searchPokemon(string $name)
    {
        $response = [
            "status" => 404,
            "html" => "<span class='m-auto text-center'>No se encontró el pokemón $name </span>"
        ];

        $name = strtolower(trim($name));
        if (!$name) {
            return response()->json($response);
        }

        $apiResponse = $this->getPokemon($name);
        if ($apiResponse) {
            $response["status"] = $apiResponse->status();

            if ($response["status"] === 200) {
                $response["html"] = view("components.pokemon-preview", ["preview" => $apiResponse->json()])->render();
            }
        }

        return response()->json($response, $response["status"]);
    }
}
