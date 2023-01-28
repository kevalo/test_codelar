<?php

namespace App\Http\Controllers;

use App\Traits\PokemonApi;
use App\Traits\DbOperations;
use Illuminate\Http\Request;


class MainController extends Controller
{
    use PokemonApi, DbOperations;


    public function index()
    {
        return view("welcome", ["team" => $this->getAllPokemonsData()]);
    }

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

    public function addPokemon(Request $request)
    {
        $response = [
            "status" => 200,
            "html" => "",
            "htmlPreview" => "Error al agregar el pokemón a tu equipo"
        ];

        $max = config("constants.MAX_POKEMONS");
        if ($this->countPokemonsData() >= $max) {
            $response["htmlPreview"] =
                "<span class='text-center m-auto'>Solo puedes tener $max pokemones en tu equipo</span>";

        } else {

            $name = strtolower(trim($request->post("name")));

            if (!$name) {
                return response()->json($response);
            }

            $apiResponse = $this->getPokemon($name);

            if ($apiResponse) {

                $response["status"] = $apiResponse->status();

                if ($response["status"] === 200 && $this->savePokemonData($apiResponse->json())) {
                    $response["htmlPreview"] = view("components.pokemon-preview")->render();
                }
            }
        }

        $response["html"] = view("components.team-list", ["team" => $this->getAllPokemonsData()])->render();
        return response()->json($response, $response["status"]);
    }
}
