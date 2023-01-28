<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pokemon extends Model
{
    use HasFactory;

    protected $table = "pokemons";

    /**
     * Get the stats for the pokemon.
     */
    public function stats()
    {
        return $this->belongsToMany(Stat::class, 'pokemon_stat', 'pokemon_id', 'stat_id')->withPivot('base_stat');
    }

    /**
     * Get the mvoes for the pokemon.
     */
    public function moves()
    {
        return $this->belongsToMany(Stat::class, 'pokemon_move', 'pokemon_id', 'move_id');
    }
}
