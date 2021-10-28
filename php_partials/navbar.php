<?php
    $config = require(__DIR__ . "/../data/config.php");
?>
<nav class="navbar navbar-expand-lg sticky-top navbar-dark bg-primary">
    <div class="container-fluid">
        <a class="navbar-brand" href="<?php echo $config["BASE_URL"] ?>/index.php">
            <img src="<?php echo $config["BASE_URL"] ?>/media/pokedex.png" alt="Pokédex logo" width="30" height="24" class="d-inline-block align-text-center">
            Pokédex
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavDropdown">
            <ul class="navbar-nav">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuMasterData" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Master data
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuMasterData">
                        <li><a class="dropdown-item" href="<?php echo $config["BASE_URL"] ?>/php_views/pokemon_list.php">Pokemons</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>