<?php

/*
Number:         Each pokémon has a number that identifies it and is unique to each pokémon.
Name:           The name of the pokémon.
Region:         Name of the region from which the pokémon originates. A pokémon is only from one region.
Type:           Types of pokémon. A pokémon must have at least one type, but it can have more.
Height:         Height of the pokémon. We will save it in centimeters without decimals.
Weight:         Weight of the pokémon. We will store it in kilograms and it can have decimals.
Evolution:      We need to know if the pokémon is an unevolved pokémon, if it is a first evolution or if it is a later evolution.
Image url:      Route where the image of the pokemon is.
*/

function createPokemon($number, $name, $region, $type, $height, $weight, $evolution, $image_url)
{
    return  [
        "number" => $number,
        "name" => $name,
        "region" => $region,
        "type" => $type,
        "height" => $height,
        "weight" => $weight,
        "evolution" => $evolution,
        "image_url" => $image_url
    ];
}

function showPokemon($pokemon)
{
    if (isset($pokemon)) {
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
    } else {
        echo nl2br("Error show, pokemon not defined.\n");
    }
}

function addPokemon(&$pokedex, $pokemon)
{
    $index = findPokemonByNum($pokedex, $pokemon["number"]);

    if ($index === -1) {
        array_push($pokedex, $pokemon);
        echo nl2br("Pokemon added.\n");
    } else {
        echo nl2br("Error add, this pokemon is already exists.\n");
    }
}

function deletePokemon(&$pokedex, $number)
{
    $index = findPokemonByNum($pokedex, $number);

    if ($index === -1) {
        echo nl2br("Error delete, this pokemon not exists.\n");
    } else {
        array_splice($pokedex, $index, 1);
        echo nl2br("Pokemon deleted.\n");
    }
}

function updatePokemon(&$pokedex, $pokemon)
{
    $index = findPokemonByNum($pokedex, $pokemon["number"]);

    if ($index === -1) {
        echo nl2br("Error update, this pokemon not exists.\n");
    } else {
        $pokedex = array_replace($pokedex, array($index => $pokemon));
        echo nl2br("Pokemon updated.\n");
    }
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
