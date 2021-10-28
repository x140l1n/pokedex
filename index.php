<?php
//Import librarys. If the library is not found, throws Fatal Exception.
require_once("php_librarys/pokedex.php");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php
        //Import the links styles. 
        require("php_partials/head.php") 
    ?>
    <title>Pokédex</title>
</head>

<body class="background">  
    <?php
        //Import the navbar. 
        require("php_partials/navbar.php") 
    ?>

    <script type="text/javascript" src="UI/bootstrap-5.0.2/dist/js/bootstrap.min.js"></script>
</body>

</html>


<?php
/*
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
*/
?>