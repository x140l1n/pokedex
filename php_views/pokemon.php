<!DOCTYPE html>
<html lang="en">

<head>
    <?php
    //Import the links styles. 
    require("../php_partials/head.php")
    ?>
    <title>Pokémon</title>
</head>

<body>
    <?php require("../php_partials/navbar.php") ?>
    <div class="container-fluid p-4">
        <div class="row justify-content-center">
            <div class="col-md-12 col-lg-5">
                <div class="card">
                    <div class="card-header bg-secondary d-flex align-items-center">
                        <img src="../media/pokeball.png" width="50" height="50" />
                        <span class="fs-5 text-light ms-2">Pokémon</span>
                    </div>
                    <form>
                        <div class="card-body">
                            <div class="row mb-3">
                                <label for="input-number" class="col-sm-3 col-form-label">Number</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="input-number" maxlength="3">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="input-name" class="col-sm-3 col-form-label">Name</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="input-name">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="select-region" class="col-sm-3 col-form-label">Region</label>
                                <div class="col-sm-9">
                                    <select class="form-select" id="select-region">
                                        <option value="kanto">Kanto</option>
                                        <option value="jotho">Jotho</option>
                                        <option value="hoenn">Hoenn</option>
                                        <option value="sinnoh">Sinnoh</option>
                                        <option value="teselia">Teselia</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label">Type</label>
                                <div class="col-sm-9">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" name="type[]" id="check-plant" value="plant">
                                        <label class="form-check-label" for="check-plant">Plant</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" name="type[]" id="check-poison" value="poison">
                                        <label class="form-check-label" for="check-poison">Poison</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" name="type[]" id="check-fire" value="fire">
                                        <label class="form-check-label" for="check-fire">Fire</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" name="type[]" id="check-fly" value="fly">
                                        <label class="form-check-label" for="check-fly">Fly</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" name="type[]" id="check-water" value="water">
                                        <label class="form-check-label" for="check-water">Water</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" name="type[]" id="check-electric" value="electric">
                                        <label class="form-check-label" for="check-electric">Electric</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" name="type[]" id="check-fairy" value="fairy">
                                        <label class="form-check-label" for="check-fairy">Fairy</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" name="type[]" id="check-bug" value="bug">
                                        <label class="form-check-label" for="check-bug">Bug</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" name="type[]" id="check-fight" value="fight">
                                        <label class="form-check-label" for="check-fight">Fight</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" name="type[]" id="check-psychic" value="psychic">
                                        <label class="form-check-label" for="check-psychic">Psychic</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="input-name" class="col-sm-3 col-form-label">Height</label>
                                <div class="col-sm-9">
                                    <div class="input-group mb-3">
                                        <input type="number" class="form-control" min="1">
                                        <span class="input-group-text">cm</span>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="input-name" class="col-sm-3 col-form-label">Weight</label>
                                <div class="col-sm-9">
                                    <div class="input-group mb-3">
                                        <input type="number" class="form-control" min="0" step="0.01">
                                        <span class="input-group-text">kg</span>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="input-name" class="col-sm-3 col-form-label">Evolution</label>
                                <div class="col-sm-9">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="radio-evolution" id="radio-none-evolution" value="none-evolution" checked>
                                        <label class="form-check-label" for="radio-none-evolution">None evolution</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="radio-evolution" id="radio-first-evolution" value="first-evolution">
                                        <label class="form-check-label" for="radio-first-evolution">First evolution</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="radio-evolution" id="radio-second-evolution" value="second-evolution">
                                        <label class="form-check-label" for="radio-second-evolution">Second evolution</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="input-name" class="col-sm-3 col-form-label">Image</label>
                                <div class="col-sm-9">
                                    <input type="file" class="form-control">
                                </div>
                            </div>
                            <div class="d-grid gap-2 d-sm-flex justify-content-sm-end mb-2">
                                <a href="pokemon_list.php" class="btn btn-secondary float-end">Cancel</a>
                                <button type="submit" class="btn btn-primary float-end">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>