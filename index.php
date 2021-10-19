<?php
//Import the pokedex library. If the library is not found, throws Fatal Exception.
require_once("php_librarys/pokedex.php");
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pokédex</title>
</head>

<body>
    <?php
        //Create empty pokédex.
        $pokedex = [];

        //Create new pokemons.
        $pokemon1 = createPokemon(1, "Bulbasaur", "Hoen", ["Plant", "Poison"], 70, 6.9, "No evolution", "1.png");
        $pokemon2 = createPokemon(2, "Ivysaur", "Hoen", ["Plant", "Poison"], 100, 13, "First evolution", "2.png");
        $pokemon3 = createPokemon(3, "Venusaur", "Hoen", ["Plant", "Poison"], 200, 100, "Second evolution", "3.png");

        //Add all pokemons.
        addPokemon($pokedex, $pokemon1);
        addPokemon($pokedex, $pokemon2);
        addPokemon($pokedex, $pokemon3);

        //Show all pokédex.
        showPokedex($pokedex);

        //Delete one pokemon.
        deletePokemon($pokedex, 2);

        //Show all pokédex.
        showPokedex($pokedex);
    ?>
</body>

</html>