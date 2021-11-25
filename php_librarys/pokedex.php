<?php

/*
Number:         Each pokemon has a number that identifies it and is unique to each pokemon.
Name:           The name of the pokemon.
Region:         Name of the region from which the pokemon originates. A pokemon is only from one region.
Type:           Types of pokemon. A pokemon must have at least one type, but it can have more.
Height:         Height of the pokemon. We will save it in centimeters without decimals.
Weight:         Weight of the pokemon. We will store it in kilograms and it can have decimals.
Evolution:      We need to know if the pokemon is an unevolved pokemon, if it is a first evolution or if it is a later evolution.
Image url:      Route where the image of the pokemon is.
*/

function createPokemon($number, $name, $region, $type, $height, $weight, $evolution, $image_url)
{
    return  [
        "numero" => $number,
        "nombre" => $name,
        "region" => $region,
        "tipos" => $type,
        "altura" => $height,
        "peso" => $weight,
        "evolucion" => $evolution,
        "imagen" => $image_url
    ];
}

function addPokemon(&$pokedex, $pokemon)
{
    $result = [];

    $index = findPokemonByNum($pokedex, $pokemon["number"]);

    if ($index === -1) {
        array_push($pokedex, $pokemon);
        $result = ["status_code" => 200, "message" => "Pokemon added."];
    } else {
        $result = ["status_code" => 409, "message" => "Error add, this pokemon is already exists."];
    }

    return $result;
}

function deletePokemon(&$pokedex, $number)
{
    $result = [];

    $index = findPokemonByNum($pokedex, $number);

    if ($index === -1) {
        $result = ["status_code" => 409, "message" => "Error delete, this pokemon not exists."];
    } else {
        array_splice($pokedex, $index, 1);
        $result = ["status_code" => 200, "message" => "Pokemon deleted."];
    }

    return $result;
}

function updatePokemon(&$pokedex, $pokemon)
{
    $result = [];

    $index = findPokemonByNum($pokedex, $pokemon["number"]);

    if ($index === -1) {
        $result = ["status_code" => 409, "message" => "Error update, this pokemon not exists."];
    } else {
        $pokedex = array_replace($pokedex, array($index => $pokemon));
        $result = ["status_code" => 200, "message" => "Pokemon updated."];
    }

    return $result;
}

function findPokemonByNum($pokedex, $number)
{
    $array_number_pokedex = array_column($pokedex, "number");

    $index = array_search($number, $array_number_pokedex, false);

    return $index === false ? -1 : $index;
}

function showPokedex($pokedex)
{
    foreach ($pokedex as $pokemon) {
        echo nl2br("-------------------------------------\n
                    Number: ${pokemon['number']}\n
                    Name: ${pokemon['name']}\n
                    Region: ${pokemon['region']}\n
                    Type: " . implode(", ", $pokemon["type"]) . "\n
                    Height: ${pokemon['height']}\n
                    Weight: ${pokemon['weight']}\n
                    Evolution: ${pokemon['evolution']}\n
                    Image url: ${pokemon['image_url']}\n
                    -------------------------------------\n");
    }
}

function showPokemon($pokemon)
{
    $result = [];

    if (isset($pokemon)) {
        $result = ["status_code" => 200, "message" => nl2br("Number: ${pokemon['number']}\n
                                                            Name: ${pokemon['name']}\n
                                                            Region: ${pokemon['region']}\n
                                                            Type: " . implode(", ", $pokemon["type"]) . "\n
                                                            Height: ${pokemon['height']}\n
                                                            Weight: ${pokemon['weight']}\n
                                                            Evolution: ${pokemon['evolution']}\n
                                                            Image url: ${pokemon['image_url']}\n")];
    } else {
        $result = ["status_code" => 409, "message" => "Error show, pokemon not defined."];
    }

    return $result;
}
