<?php

use App\Http\Controllers\MainController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware("auth:sanctum")->get("/user", function (Request $request) {
    return $request->user();
});

Route::prefix("pokemon")->group(function () {
    Route::get("/search/{name}", [MainController::class, "searchPokemon"])->name("search_pokemon");
    Route::post("/add", [MainController::class, "addPokemon"])->name("add_pokemon");
    Route::post("/evolve", [MainController::class, "evolvePokemon"])->name("evolve_pokemon");
    Route::post("/remove", [MainController::class, "removePokemon"])->name("remove_pokemon");
});
