<?php

//Import all libraries.
require_once("../php_librarys/database.php");

//If there is not session, start session.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$pokedex = [];

$database = new Database();

$pokedex = $database->SelectPokemons();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php
    //Import the links styles. 
    include("../php_partials/head.php");
    ?>
    <title>Pokemon list</title>
</head>

<body>
    <?php include("../php_partials/navbar.php"); ?>
    <div class="container-fluid p-4">
        <?php
        if (isset($_SESSION["response"])) {
            if (isset($_SESSION["response"]["message"])) {
                echo "<div class=\"alert alert-" . ($_SESSION["response"]["status_code"] === 200 ? "success" : "danger") . " alert-floating fade\" role=\"alert\">
                        " . $_SESSION["response"]["message"] . "
                    </div>";
            }

            unset($_SESSION["response"]);
        }
        ?>
        <div class="row row-cols-1 row-cols-md-5 g-4 d-flex align-items-stretch">
            <?php
            foreach ($pokedex as $pokemon) {

                $type_str = "";

                foreach ($pokemon["tipos"] as $type) {
                    $type_str .= '<span class="badge bg-warning text-dark me-2">' . mb_strtoupper($type["nombre"]) . '</span>';
                }

                echo '<div class="col d-flex align-items-stretch">
                            <div class="card border border-secondary">
                                <img src="' . $pokemon["imagen"] . '" class="card-img-top" alt="' . $pokemon["nombre"] . '">
                                <div class="card-body">
                                    <h5 class="card-title">' . $pokemon["numero"] . ' - ' . $pokemon["nombre"] . '</h5>
                                    <p class="card-text">
                                        ' . $type_str . '
                                    </p>
                                </div>
                                <div class="card-footer d-grid gap-2 d-sm-flex justify-content-sm-end">
                                    <form action="/pokedex/php_controllers/pokemon_controller.php" method="POST">
                                        <button class="btn btn-outline-danger" type="submit" title="Delete pokemon" name="method" value="delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                        <button class="btn btn-outline-primary" type="submit" title="Edit pokemon" name="method" value="redirect_update_page">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <input type="hidden" name="number" value="' . $pokemon["numero"] . '"/>
                                    </form>
                                </div>
                            </div>
                        </div>';
            }
            ?>
        </div>
        <a class="btn btn-warning btn-lg rounded-circle btn-floating" title="Add new pokemon" href="pokemon.php">
            <i class="fas fa-plus"></i>
        </a>
    </div>
    <script type="text/javascript" src="/pokedex/UI/bootstrap-5.0.2/dist/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="/pokedex/js/common.js"></script>
</body>

</html>