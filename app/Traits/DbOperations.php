<?php

namespace App\Traits;

use Illuminate\Support\Facades\DB;
use App\Models\Pokemon;
use App\Models\Stat;
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
     * Returns the pokemons in the database
     * @return \Illuminate\Database\Eloquent\Collection|array
     */
    public function getAllPokemonsData()
    {
        $data = [];

        try {
            $data = Pokemon::with('stats')->get()->toArray();
        } catch (\Exception $e) {
            error_log($e->getMessage());
        }

        return $data;
    }


    /**
     * Saves a pokemon in the database
     * @param array $data
     * @return bool
     */
    public function savePokemonData(array $data)
    {
        $result = false;

        try {

            DB::transaction(function () use (&$result, $data) {
                $pokemon = new Pokemon();
                $pokemon->name = $data["name"];
                $pokemon->api_id = $data["id"];
                $pokemon->base_experience = $data["base_experience"];
                $pokemon->height = $data["height"];
                $pokemon->weight = $data["weight"];
                $pokemon->sprite = $data["sprites"]["other"]["official-artwork"]["front_default"];
                $pokemon->save();

                $stats = $this->saveStatsData($data["stats"]);
                $pokemon->stats()->sync($stats);

                $result = true;
            });

        } catch (\Exception $e) {
            error_log($e->getMessage());
        }

        return $result;
    }

    /**
     * Saves the pokemon's stats
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
}
