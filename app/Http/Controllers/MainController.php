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

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
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
                "<span class='text-center m-auto'>Solo puedes tener $max pokemón en tu equipo</span>";

        } else {

            $name = strtolower(trim($request->post("name")));

            if (!$name) {
                return response()->json($response);
            }

            $apiResponse = $this->getPokemon($name);

            if ($apiResponse) {

                $response["status"] = $apiResponse->status();

                $moves = $this->getLevelUpMoves($apiResponse->json()["moves"]);
                $evolutionChainUrl = $this->getEvolutionChainUrl($apiResponse["species"]["url"]);
                if (
                    $response["status"] === 200
                    && $this->savePokemonData($apiResponse->json(), $moves, $evolutionChainUrl)
                ) {
                    $response["htmlPreview"] = view("components.pokemon-preview")->render();
                }
            }
        }

        $response["html"] = view("components.team-list", ["team" => $this->getAllPokemonsData()])->render();
        return response()->json($response, $response["status"]);
    }

    public function evolvePokemon(Request $request)
    {
        $response = [
            "html" => "",
            "found" => false
        ];

        $id = $request->post("id");
        $pokemon = $this->getPokemonById($id);
        if ($pokemon) {
            $evolutionChainData = $this->getEvolutionChain($pokemon->evolution_chain);
            if ($evolutionChainData) {

                $evolution = null;
                $chain = $evolutionChainData->json()["chain"];
                if (count($chain["evolves_to"]) > 0) {
                    $evolution = $this->findEvolution($chain, $pokemon->name);
                    if ($evolution) {
                        $response["found"] = true;
                        $this->updatePokemon($id, $evolution);
                    }
                }
            }
        }

        $response["html"] = view("components.team-list", ["team" => $this->getAllPokemonsData()])->render();
        return response()->json($response);
    }

    /**
     * Returns the moves from "scarlet-violet" that can get learned by "level-up"
     * @param array $moves
     * @return array
     */
    private function getLevelUpMoves(array $moves)
    {
        $validMoves = array_filter($moves, static function ($item) {
            $validMove = false;

            $lastVersionIndex = count($item["version_group_details"]) - 1;
            if (
                $item["version_group_details"][$lastVersionIndex]["move_learn_method"]["name"] === "level-up"
                && (
                    $item["version_group_details"][$lastVersionIndex]["version_group"]["name"] === "scarlet-violet"
                    || $item["version_group_details"][$lastVersionIndex]["version_group"]["name"] === "sword-shield"
                )
            ) {
                $validMove = true;
            }

            return $validMove;
        });

        $levelUpMoves = [];

        foreach ($validMoves as $move) {

            $allMoveData = $this->getMove($move["move"]["url"]);
            if ($allMoveData) {
                $levelUpMoves[] = [
                    "name" => $allMoveData["name"],
                    "damage_class" => $allMoveData["damage_class"]["name"],
                    "type" => $allMoveData["type"]["name"],
                    "accuracy" => $allMoveData["accuracy"] ?: 0,
                    "power" => $allMoveData["power"] ?: 0,
                ];
            }
        }

        return $levelUpMoves;
    }

    /**
     * Returns the pokemon's evolution
     * @param mixed $chain
     * @param mixed $name
     * @return mixed
     */
    private function findEvolution($chain, $name)
    {
        $found = false;
        $evolution = null;

        $current = $chain;
        while (!$found && count($current["evolves_to"]) > 0) {

            if ($current["species"]["name"] === $name) {
                $found = true;
                // if there is more than one possible evolution
                $evolutionIndex = 0;
                $evolutionsCount = count($current["evolves_to"]);
                if ($evolutionsCount > 1) {
                    $evolutionIndex = random_int(0, $evolutionsCount - 1);
                }
                $evolution = $this->getPokemon($current["evolves_to"][$evolutionIndex]["species"]["name"]);
            } else {
                $current = $current["evolves_to"][0];
            }

        }

        if ($found && $evolution) {
            return $evolution->json();
        }

        return null;
    }

    private function updatePokemon($id, $newData)
    {
        $moves = $this->getLevelUpMoves($newData["moves"]);
        $evolutionChainUrl = $this->getEvolutionChainUrl($newData["species"]["url"]);
        return $this->updatePokemonData($id, $newData, $moves, $evolutionChainUrl);
    }
}
