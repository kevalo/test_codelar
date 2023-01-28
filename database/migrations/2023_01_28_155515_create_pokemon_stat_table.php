<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pokemon_stat', function (Blueprint $table) {
            $table->id();
            $table->foreignId("pokemon_id")->constrained("pokemons");
            $table->foreignId("stat_id")->constrained("stats");
            $table->integer("base_stat");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pokemon_stat');
    }
};
