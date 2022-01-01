<?php

//Import all libraries.
require_once("../php_librarys/database.php");

//If there is not session, start session.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$database = new Database();

if (isset($_GET["id"])) {
    $pokemons = $database->SelectPokemons($_GET["id"]);

    if (count($pokemons) > 0) {
        $pokemon = $pokemons[0];
    }
} 

if (!isset($pokemon)) {
    $_SESSION["response"] = ["status_code" => 404, "message" => "Pokemon not found."];

    header("Location: pokemon_list.php");
}

//Get all regions and types.
$regions = $database->SelectRegions();
$types = $database->SelectTypes();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php
    //Import the links styles. 
    include("../php_partials/head.php");
    ?>
    <title>Pokemon</title>
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
        }
        ?>
        <div class="row justify-content-center">
            <div class="col-md-12 col-lg-5">
                <div class="card">
                    <div class="card-header bg-secondary d-flex align-items-center">
                        <img src="/pokedex/webApp/pokeball.png" width="50" height="50" />
                        <span class="fs-5 text-light ms-2">Pokemon</span>
                    </div>
                    <form action="/pokedex/php_controllers/pokemon_controller.php" method="POST" enctype="multipart/form-data">
                        <div class="card-body">
                            <div class="row mb-3">
                                <label for="input-number" class="col-sm-3 col-form-label">Number * </label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="number" id="input-number" maxlength="3" placeholder="000" value="<?php echo (isset($pokemon) ? $pokemon["numero"] : "") ?>" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="input-name" class="col-sm-3 col-form-label">Name * </label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="name" id="input-name" value="<?php echo (isset($pokemon) ? $pokemon["nombre"] : "") ?>" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="select-region" class="col-sm-3 col-form-label">Region * </label>
                                <div class="col-sm-9">
                                    <select class="form-select" name="region" id="select-region" required>
                                        <?php
                                        foreach ($regions as $region) {
                                            echo "<option value='" . $region["id"] . "' " . (isset($pokemon["regiones_id"]) ? ($pokemon["regiones_id"] === $region["id"] ? "selected" : "") : "") . ">" . $region["nombre"] . "</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label">Type * </label>
                                <div class="col-sm-9">
                                    <?php
                                    $ids_types_pokemon = [];
                                    
                                    if (isset($pokemon["tipos"])) {
                                        $ids_types_pokemon = array_column($pokemon["tipos"], "id");
                                    }

                                    foreach ($types as $type) {
                                        echo    "<div class='form-check form-check-inline'>
                                                        <input class='form-check-input' type='checkbox' name='type[]' id='type-" . $type["id"] . "' value='" . $type["id"] . "' " . (in_array($type["id"], $ids_types_pokemon) ? "checked" : "") . ">
                                                        <label class='form-check-label' for='type-" . $type["id"] . "'>" . $type["nombre"] . "</label>
                                                    </div>";
                                    }
                                    ?>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="input-name" class="col-sm-3 col-form-label">Height * </label>
                                <div class="col-sm-9">
                                    <div class="input-group mb-3">
                                        <input type="number" class="form-control" min="1" name="height" id="input-height" placeholder="0" value="<?php echo (isset($pokemon) ? $pokemon["altura"] : "") ?>" required>
                                        <span class="input-group-text">cm</span>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="input-name" class="col-sm-3 col-form-label">Weight * </label>
                                <div class="col-sm-9">
                                    <div class="input-group mb-3">
                                        <input type="number" class="form-control" min="0" step="0.01" name="weight" id="input-weight" placeholder="0,0" value="<?php echo (isset($pokemon) ? $pokemon["peso"] : "") ?>" required>
                                        <span class="input-group-text">kg</span>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="input-name" class="col-sm-3 col-form-label">Evolution * </label>
                                <div class="col-sm-9">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="evolution" id="radio-none-evolution" value="Sin evolucionar" <?php echo (isset($pokemon) ? ($pokemon["evolucion"] === "Sin evolucionar" ? "checked" : "") : "checked") ?>>
                                        <label class="form-check-label" for="radio-none-evolution">None evolution</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="evolution" id="radio-first-evolution" value="Primera evolución" <?php echo (isset($pokemon) ? ($pokemon["evolucion"] === "Primera evolución" ? "checked" : "") : "") ?>>
                                        <label class="form-check-label" for="radio-first-evolution">First evolution</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="evolution" id="radio-second-evolution" value="Segunda evolución" <?php echo (isset($pokemon) ? ($pokemon["evolucion"] === "Segunda evolución" ? "checked" : "") : "") ?>>
                                        <label class="form-check-label" for="radio-second-evolution">Second evolution</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="input-name" class="col-sm-3 col-form-label">Image</label>
                                <div class="col-sm-9">
                                    <input type="file" class="form-control" name="image" id="input-image">
                                </div>
                            </div>
                            <div class="d-grid gap-2 d-sm-flex justify-content-sm-end mb-2">
                                <a href="pokemon_list.php" class="btn btn-secondary float-end">Cancel</a>
                                <button type="submit" class="btn btn-primary float-end" name="method" value="update">Submit</button>
                                <input type="hidden" name="id" value="<?php echo $pokemon["id"]; ?>"/>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript" src="/pokedex/UI/bootstrap-5.0.2/dist/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="/pokedex/js/common.js"></script>
</body>

</html>