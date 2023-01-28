<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ env('APP_NAME') }}</title>

    <!-- Fonts -->
    <link href="https://fonts.bunny.net/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

    <!-- Styles -->
    <style>
        body {
            font-family: 'Nunito', sans-serif;
        }
    </style>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    <h1 class="text-4xl my-8 text-center">Equipo Pokemón</h1>
    <div class="flex justify-center p-3">
        <x-search-form />
        <div id="preview" class="p-3 border-2 border-sky-500 rounded-lg ml-5 flex flex-col shadow-md shadow-blue-300">
            <x-pokemon-preview />
        </div>
    </div>
    <div class="text-center">
        <small>Solo se incluyen los movimientos de las versiones "Scarlet", "Violet", "Sword", "Shield" que puden
            aprenderse subiendo de
            nivel.</small>
    </div>
    <div id="teamList">
        <x-team-list :team="$team" />
    </div>

</body>

<script src="https://code.jquery.com/jquery-3.6.3.slim.min.js"
    integrity="sha256-ZwqZIVdD3iXNyGHbSYdsmWP//UBokj2FHAxKuSBKDSo=" crossorigin="anonymous"></script>

</html>
