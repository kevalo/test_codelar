<?php

namespace App\Traits;

use Illuminate\Support\Facades\DB;
use App\Models\Pokemon;
use App\Models\Stat;
use App\Models\Move;
use Carbon\Carbon;

trait DbOperations
{

    /**
     * Returns the amount of pokemons in the database
     * @return int
     */
    public function countPokemonsData()
    {
        $amount = 0;

        try {
            $amount = Pokemon::count();
        } catch (\Exception $e) {
            error_log($e->getMessage());
        }
        return $amount;
    }

    /**
     * Returns a pokemon searching by the Id
     * @param int $id
     * @return mixed
     */
    public function getPokemonById(int $id)
    {
        $data = null;

        if (!is_numeric($id)) {
            return $data;
        }

        try {
            $data = Pokemon::find($id);
        } catch (\Exception $e) {
            error_log($e->getMessage());
        }

        return $data;
    }


    /**
     * Returns the pokemons in the database
     * @return \Illuminate\Database\Eloquent\Collection|array
     */
    public function getAllPokemonsData()
    {
        $data = [];

        try {
            $data = Pokemon::with(['stats', 'moves'])->get()->toArray();
        } catch (\Exception $e) {
            error_log($e->getMessage());
        }

        return $data;
    }


    /**
     * Saves a pokemon in the database
     * @param array $data
     * @param array $moves
     * @param string $evolutionChainUrl
     * @return bool
     */
    public function savePokemonData(array $data, array $moves, string $evolutionChainUrl)
    {
        $result = false;

        try {

            DB::transaction(function () use (&$result, $data, $moves, $evolutionChainUrl) {
                $pokemon = new Pokemon();
                $pokemon->name = $data["name"];
                $pokemon->api_id = $data["id"];
                $pokemon->base_experience = $data["base_experience"];
                $pokemon->height = $data["height"];
                $pokemon->weight = $data["weight"];
                $pokemon->sprite = $data["sprites"]["other"]["official-artwork"]["front_default"];
                $pokemon->evolution_chain = $evolutionChainUrl;
                $pokemon->save();

                $stats = $this->saveStatsData($data["stats"]);
                $pokemon->stats()->sync($stats);

                $validMoves = $this->saveMovesData($moves);
                $pokemon->moves()->sync($validMoves);

                $result = true;
            });

        } catch (\Exception $e) {
            error_log($e->getMessage());
        }

        return $result;
    }

    /**
     * Updates a pokemon in the database
     * @param int $id
     * @param array $data
     * @param array $moves
     * @param string $evolutionChainUrl
     * @return bool
     */
    public function updatePokemonData(int $id, array $data, array $moves, string $evolutionChainUrl)
    {
        $result = false;

        try {
            DB::transaction(function () use (&$result, $id, $data, $moves, $evolutionChainUrl) {
                $pokemon = Pokemon::find($id);
                if ($pokemon) {
                    $pokemon->name = $data["name"];
                    $pokemon->api_id = $data["id"];
                    $pokemon->base_experience = $data["base_experience"];
                    $pokemon->height = $data["height"];
                    $pokemon->weight = $data["weight"];
                    $pokemon->sprite = $data["sprites"]["other"]["official-artwork"]["front_default"];
                    $pokemon->evolution_chain = $evolutionChainUrl;
                    $pokemon->save();

                    $stats = $this->saveStatsData($data["stats"]);
                    $pokemon->stats()->sync($stats);

                    $validMoves = $this->saveMovesData($moves);
                    $pokemon->moves()->sync($validMoves);

                    $result = true;
                }
            });

        } catch (\Exception $e) {
            error_log($e->getMessage());
        }

        return $result;
    }

    /**
     * Saves the pokemon stats
     * @param array $stats
     * @return array
     */
    public function saveStatsData(array $stats)
    {
        $result = [];

        try {
            foreach ($stats as $stat) {

                $statDb = Stat::where('name', $stat["stat"]["name"])->first();
                if ($statDb) {
                    $result[$statDb->id] = ["base_stat" => $stat["base_stat"]];
                } else {
                    $result[DB::table("stats")->insertGetId([
                        "name" => $stat["stat"]["name"],
                        "created_at" => Carbon::now(),
                        "updated_at" => Carbon::now()
                    ])] = ["base_stat" => $stat["base_stat"]];
                }
            }

        } catch (\Exception $e) {
            error_log($e->getMessage());
        }

        return $result;
    }

    /**
     * Saves the pokemon moves
     * @param array $stats
     * @return array
     */
    public function saveMovesData(array $moves)
    {
        $result = [];

        try {
            foreach ($moves as $move) {

                $moveDb = Move::where('name', $move["name"])->first();
                if ($moveDb) {
                    $result[] = $moveDb->id;
                } else {
                    $result[] = DB::table("moves")->insertGetId([
                        "name" => $move["name"],
                        "damage_class" => $move["damage_class"],
                        "type" => $move["type"],
                        "accuracy" => $move["accuracy"],
                        "power" => $move["power"],
                        "created_at" => Carbon::now(),
                        "updated_at" => Carbon::now()
                    ]);
                }
            }

        } catch (\Exception $e) {
            error_log($e->getMessage());
        }

        return $result;
    }
}
