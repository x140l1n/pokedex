<?php

//Import all libraries.
require("../php_librarys/functions.php");
require("../php_librarys/pokedex.php");
require("../php_librarys/database.php");

//If there is not session, start session.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

//If the method is defined and is not empty.
if (isset($_POST["method"]) && !empty($_POST["method"])) {
    //Get the method name.
    $method = $_POST["method"];

    //Check if the function exists.
    if (function_exists($method)) {
        //Call the method.
        $method();
    } else {
        $_SESSION["response"] = ["status_code" => 405, "message" => "Method '$method' not found."];
        //Redirect previous page.
        header('Location: ' . $_SERVER['HTTP_REFERER']);
    }
} else {
    $_SESSION["response"] = ["status_code" => 405, "message" => "Method not defined."];
    //Redirect previous page.
    header('Location: ' . $_SERVER['HTTP_REFERER']);
}

//Functions controller.
function insert()
{
    $result = [];

    //Get all values from POST, if is not set, get empty value.
    $number = isset($_POST["number"]) ? $_POST["number"] : "";
    $name = isset($_POST["name"]) ? $_POST["name"] : "";
    $region = isset($_POST["region"]) ? $_POST["region"] : "";
    $type = isset($_POST["type"]) ? $_POST["type"] : [];
    $height = isset($_POST["height"]) ? $_POST["height"] : "";
    $weight = isset($_POST["weight"]) ? $_POST["weight"] : "";
    $evolution = isset($_POST["evolution"]) ? $_POST["evolution"] : "";
    $image = isset($_FILES["image"]) ? $_FILES["image"] : "";

    $img_path_absolute = "";

    //Check if image is submit from client.
    if (!empty($number) && !empty($image) && $image["error"] !== 4) {
        $img_path_tmp = $image["tmp_name"];
        $extension = pathinfo($image["name"], PATHINFO_EXTENSION);
        $img_path_relative = "../users/img/" . $number . "." . $extension;
        $img_path_absolute = "/pokedex/users/img/" . $number . "." . $extension;
    }

    //Create array associative of pokemon with data.
    $pokemon = createPokemon($number, $name, $region, $type, $height, $weight, $evolution, $img_path_absolute);

    //Check if all data is not empty.
    if (!(empty($number) ||
        empty($name) ||
        empty($region) ||
        empty($type) ||
        empty($pokemon) ||
        empty($height) ||
        empty($weight) ||
        empty($evolution) ||
        empty($image))) {
        //Check if the number of length is 3 and only digits.
        if (strlen($number) === 3 && checkNumbersOnly($number)) {
            try {
                //Add pokemon to database.
                $database = new Database();
                $id_pokemon_added = $database->InsertPokemon($pokemon);

                //If the pokemon is added correctly.
                if ($id_pokemon_added !== -1) {
                    //Move the image path from temporal path to /users/img/.
                    if (!move_uploaded_file($img_path_tmp, $img_path_relative)) {
                        $result = ["status_code" => 400, "message" => "Error to upload the file."];
                    } else {
                        $result = ["status_code" => 200, "message" => "Pokemon added."];
                    }
                } else {
                    $result = ["status_code" => 500, "message" => "Pokemon has not been added."];
                }
            } catch (PDOException $e) {
                $result = ["status_code" => 500, "message" => getErrorMessage($e)];
            }
        } else {
            $result = ["status_code" => 400, "message" => "The number of pokemon is not valid."];
        }
    } else {
        $result = ["status_code" => 400, "message" => "All fields is required."];
    }

    //Add response message to session.
    $_SESSION["response"] = $result;

    //If the pokemon is added without any errors.
    if (isset($id_pokemon_added) && $id_pokemon_added !== -1) {
        //Redirect to pokemon_list.php
        header("Location: ../php_views/pokemon_list.php");
    } else {
        $_SESSION["pokemon"] = $pokemon;

        //Redirect to pokemon.php        
        header("Location: ../php_views/pokemon.php");
    }
}

function update()
{
    $result = [];

    //Get all values from POST, if is not set, get empty value.
    $id = isset($_POST["id"]) ? $_POST["id"] : "";
    $number = isset($_POST["number"]) ? $_POST["number"] : "";
    $name = isset($_POST["name"]) ? $_POST["name"] : "";
    $region = isset($_POST["region"]) ? $_POST["region"] : "";
    $type = isset($_POST["type"]) ? $_POST["type"] : [];
    $height = isset($_POST["height"]) ? $_POST["height"] : "";
    $weight = isset($_POST["weight"]) ? $_POST["weight"] : "";
    $evolution = isset($_POST["evolution"]) ? $_POST["evolution"] : "";
    $image = isset($_FILES["image"]) ? $_FILES["image"] : "";

    $img_path_absolute = "";

    //Check if image is submit from client.
    if (!empty($number) && !empty($image) && $image["error"] !== 4) {
        $img_path_tmp = $image["tmp_name"];
        $extension = pathinfo($image["name"], PATHINFO_EXTENSION);
        $img_path_relative = "../users/img/" . $number . "." . $extension;
        $img_path_absolute = "/pokedex/users/img/" . $number . "." . $extension;
    }

    //Create array associative of pokemon with values.
    $pokemon = createPokemon($number, $name, $region, $type, $height, $weight, $evolution, $img_path_absolute);

    //Check if all data is not empty.
    if (!(empty($id) ||
        empty($number) ||
        empty($name) ||
        empty($region) ||
        empty($type) ||
        empty($pokemon) ||
        empty($height) ||
        empty($weight) ||
        empty($evolution))) {
        //Check if the number of length is 3 and only digits.
        if (strlen($number) === 3 && checkNumbersOnly($number)) {
            try {
                //Get previous data of pokemon.
                $database = new Database();
                $pokemons = $database->SelectPokemons($id);

                if (count($pokemons) > 0) {
                    //Get the image path previous.
                    $img_path_absolute_previous = $pokemons[0]["imagen"];
                    $extension = pathinfo($img_path_absolute_previous, PATHINFO_EXTENSION);
                    $img_path_relative_previous = "../users/img/" . $number . "." . $extension;

                    //If the pokemon image is empty, set the previous image path.
                    if (empty($img_path_absolute)) {
                        $pokemon["imagen"] = $img_path_absolute_previous;

                        //Update the pokemon data.
                        $id_pokemon_updated = $database->UpdatePokemon($pokemon, $id);

                        if ($id_pokemon_updated !== -1) {
                            $result = ["status_code" => 200, "message" => "Pokemon updated."];
                        } else {
                            $result = ["status_code" => 500, "message" => "Pokemon has not been updated."];
                        }
                    } else {
                        //Update the pokemon data.
                        $id_pokemon_updated = $database->UpdatePokemon($pokemon, $id);

                        if ($id_pokemon_updated !== -1) {
                            //Delete previous image if exists.
                            if (!file_exists($img_path_relative_previous) || unlink($img_path_relative_previous)) {
                                //Move the image path from temporal path to /users/img/.
                                if (!move_uploaded_file($img_path_tmp, $img_path_relative)) {
                                    $result = ["status_code" => 400, "message" => "Error to upload the file."];
                                } else {
                                    $result = ["status_code" => 200, "message" => "Pokemon updated."];
                                }
                            }
                        } else {
                            $result = ["status_code" => 500, "message" => "Pokemon has not been updated."];
                        }
                    }
                } else {
                    $result = ["status_code" => 404, "message" => "Pokemon not found."];
                }
            } catch (PDOException $e) {
                $result = ["status_code" => 500, "message" => getErrorMessage($e)];
            }
        } else {
            $result = ["status_code" => 400, "message" => "The number of pokemon is not valid."];
        }
    } else {
        $result = ["status_code" => 400, "message" => "All fields is required."];
    }

    //Add message to session.
    $_SESSION["response"] = $result;

    //If the pokemon is updated without any errors.
    if (isset($id_pokemon_updated) && $id_pokemon_updated !== -1) {
        //Redirect to pokemon_list.php
        header("Location: ../php_views/pokemon_list.php");
    } else {
        //Redirect to pokemon_edit.php        
        header("Location: " . $_SERVER['HTTP_REFERER']);
    }
}

function delete()
{
    $result = [];

    //Get all values from POST, if is not set, get empty value.
    $id = isset($_POST["id"]) ? $_POST["id"] : "";

    if (!empty($id)) {
        $database = new Database();
        $pokemons = $database->SelectPokemons($id);

        if (count($pokemons) > 0) {
            $pokemon = $pokemons[0];

            $extension = pathinfo($pokemon["imagen"], PATHINFO_EXTENSION);
            $img_path_relative = "../users/img/" . $pokemon["numero"] . "." . $extension;

            //Delete pokemon.
            $id_pokemon_deleted = $database->DeletePokemon($id);

            if ($id_pokemon_deleted !== -1) {
                //Delete the image of pokemon if exists.
                if (file_exists($img_path_relative) && !unlink($img_path_relative)) {
                    $result = ["status_code" => 400, "message" => "Error to delete the file."];
                } else {
                    $result = ["status_code" => 200, "message" => "Pokemon deleted."];
                }
            } else {
                $result = ["status_code" => 500, "message" => "Pokemon has not been deleted."];
            }
        } else {
            $result = ["status_code" => 400, "message" => "Pokemon not found."];
        }
    } else {
        $result = ["status_code" => 400, "message" => "The id of pokemon is missing."];
    }

    //Add response message to session.
    $_SESSION["response"] = $result;

    //Redirect to pokemon_list.php
    header("Location: ../php_views/pokemon_list.php");
}
