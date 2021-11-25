<?php
//If there is not session, start session.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

//unset($_SESSION["pokedex"]);

//Check if we have pokemon data.
if (isset($_SESSION["pokemon"])) {
    $pokemon = $_SESSION["pokemon"];
}

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

            unset($_SESSION["response"]);
        }
        ?>
        <div class="row justify-content-center">
            <div class="col-md-12 col-lg-5">
                <div class="card">
                    <div class="card-header bg-secondary d-flex align-items-center">
                        <img src="/pokedex/media/img/pokeball.png" width="50" height="50" />
                        <span class="fs-5 text-light ms-2">Pokemon</span>
                    </div>
                    <form action="/pokedex/php_controllers/pokemon_controller.php" method="POST" enctype="multipart/form-data" name="form-update">
                        <div class="card-body">
                            <div class="row mb-3">
                                <label for="input-number" class="col-sm-3 col-form-label">Number * </label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="number" id="input-number" maxlength="3" placeholder="000" value="<?php echo (isset($pokemon) ? $pokemon["number"] : "") ?>" required readonly>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="input-name" class="col-sm-3 col-form-label">Name * </label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="name" id="input-name" value="<?php echo (isset($pokemon) ? $pokemon["name"] : "") ?>" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="select-region" class="col-sm-3 col-form-label">Region * </label>
                                <div class="col-sm-9">
                                    <select class="form-select" name="region" id="select-region" required>
                                        <option value="kanto" <?php echo (isset($pokemon) ? ($pokemon["region"] === "kanto" ? "selected" : "") : "") ?>>Kanto</option>
                                        <option value="jotho" <?php echo (isset($pokemon) ? ($pokemon["region"] === "jotho" ? "selected" : "") : "") ?>>Jotho</option>
                                        <option value="hoenn" <?php echo (isset($pokemon) ? ($pokemon["region"] === "hoenn" ? "selected" : "") : "") ?>>Hoenn</option>
                                        <option value="sinnoh" <?php echo (isset($pokemon) ? ($pokemon["region"] === "sinnoh" ? "selected" : "") : "") ?>>Sinnoh</option>
                                        <option value="teselia" <?php echo (isset($pokemon) ? ($pokemon["region"] === "teselia" ? "selected" : "") : "") ?>>Teselia</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label">Type * </label>
                                <div class="col-sm-9">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" name="type[]" id="check-plant" value="plant" <?php echo (isset($pokemon) ? (in_array("plant", $pokemon["type"]) ? "checked" : "") : "") ?>>
                                        <label class="form-check-label" for="check-plant">Plant</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" name="type[]" id="check-poison" value="poison" <?php echo (isset($pokemon) ? (in_array("poison", $pokemon["type"]) ? "checked" : "") : "") ?>>
                                        <label class="form-check-label" for="check-poison">Poison</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" name="type[]" id="check-fire" value="fire" <?php echo (isset($pokemon) ? (in_array("fire", $pokemon["type"]) ? "checked" : "") : "") ?>>
                                        <label class="form-check-label" for="check-fire">Fire</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" name="type[]" id="check-fly" value="fly" <?php echo (isset($pokemon) ? (in_array("fly", $pokemon["type"]) ? "checked" : "") : "") ?>>
                                        <label class="form-check-label" for="check-fly">Fly</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" name="type[]" id="check-water" value="water" <?php echo (isset($pokemon) ? (in_array("water", $pokemon["type"]) ? "checked" : "") : "") ?>>
                                        <label class="form-check-label" for="check-water">Water</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" name="type[]" id="check-electric" value="electric" <?php echo (isset($pokemon) ? (in_array("electric", $pokemon["type"]) ? "checked" : "") : "") ?>>
                                        <label class="form-check-label" for="check-electric">Electric</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" name="type[]" id="check-fairy" value="fairy" <?php echo (isset($pokemon) ? (in_array("fairy", $pokemon["type"]) ? "checked" : "") : "") ?>>
                                        <label class="form-check-label" for="check-fairy">Fairy</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" name="type[]" id="check-bug" value="bug" <?php echo (isset($pokemon) ? (in_array("bug", $pokemon["type"]) ? "checked" : "") : "") ?>>
                                        <label class="form-check-label" for="check-bug">Bug</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" name="type[]" id="check-fight" value="fight" <?php echo (isset($pokemon) ? (in_array("fight", $pokemon["type"]) ? "checked" : "") : "") ?>>
                                        <label class="form-check-label" for="check-fight">Fight</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" name="type[]" id="check-psychic" value="psychic" <?php echo (isset($pokemon) ? (in_array("psychic", $pokemon["type"]) ? "checked" : "") : "") ?>>
                                        <label class="form-check-label" for="check-psychic">Psychic</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="input-name" class="col-sm-3 col-form-label">Height * </label>
                                <div class="col-sm-9">
                                    <div class="input-group mb-3">
                                        <input type="number" class="form-control" min="1" name="height" id="input-height" placeholder="0" value="<?php echo (isset($pokemon) ? $pokemon["height"] : "") ?>" required>
                                        <span class="input-group-text">cm</span>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="input-name" class="col-sm-3 col-form-label">Weight * </label>
                                <div class="col-sm-9">
                                    <div class="input-group mb-3">
                                        <input type="number" class="form-control" min="0" step="0.01" name="weight" id="input-weight" placeholder="0,0" value="<?php echo (isset($pokemon) ? $pokemon["weight"] : "") ?>" required>
                                        <span class="input-group-text">kg</span>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="input-name" class="col-sm-3 col-form-label">Evolution * </label>
                                <div class="col-sm-9">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="evolution" id="radio-none-evolution" value="none-evolution" <?php echo (isset($pokemon) ? ($pokemon["evolution"] === "none-evolution" ? "checked" : "") : "checked") ?>>
                                        <label class="form-check-label" for="radio-none-evolution">None evolution</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="evolution" id="radio-first-evolution" value="first-evolution" <?php echo (isset($pokemon) ? ($pokemon["evolution"] === "first-evolution" ? "checked" : "") : "") ?>>
                                        <label class="form-check-label" for="radio-first-evolution">First evolution</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="evolution" id="radio-second-evolution" value="second-evolution" <?php echo (isset($pokemon) ? ($pokemon["evolution"] === "second-evolution" ? "checked" : "") : "") ?>>
                                        <label class="form-check-label" for="radio-second-evolution">Second evolution</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="input-name" class="col-sm-3 col-form-label">Image * </label>
                                <div class="col-sm-9">
                                    <input type="file" class="form-control" name="image" id="input-image" required>
                                </div>
                            </div>
                            <div class="d-grid gap-2 d-sm-flex justify-content-sm-end mb-2">
                                <a href="pokemon_list.php" class="btn btn-secondary float-end">Cancel</a>
                                <button type="submit" class="btn btn-primary float-end" name="method" value="update">Submit</button>
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