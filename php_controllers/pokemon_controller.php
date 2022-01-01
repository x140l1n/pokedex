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
function add()
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
    if (!(empty($image) && empty($number))) {
        $img_path_tmp = $image["tmp_name"];
        $extension = pathinfo($image["name"], PATHINFO_EXTENSION);
        $img_path_relative = "../media/img/" . $number . "." . $extension;
        $img_path_absolute = "/pokedex/media/img/" . $number . "." . $extension;
    }

    //Create array associative of pokemon with values.
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
                $database->InsertPokemon($pokemon);

                //If the pokemon is added correctly.
                if ($result["status_code"] === 200) {
                    //Move the image path from temporal path to /media/img/.
                    if (!move_uploaded_file($img_path_tmp, $img_path_relative)) {
                        $result = ["status_code" => 400, "message" => "Error to upload the file."];
                    }
                }
            } catch (PDOException $e) {
                $result = ["status_code" => 400, "message" => "The number of pokemon is not valid."];
            }
        } else {
            $result = ["status_code" => 400, "message" => "The number of pokemon is not valid."];
        }
    } else {
        $result = ["status_code" => 400, "message" => "All fields is required."];
    }
    //Add message to session.
    $_SESSION["response"] = $result;

    if ($result["status_code"] === 200) {
        //Redirect to pokemon_list.php
        header("Location: ../php_views/pokemon_list.php");
    } else {
        $_SESSION["pokemon"] = $pokemon;

        //Redirect to pokemon.php        
        header("Location: ../php_views/pokemon.php");
    }
}

function redirect_update_page()
{
    $pokedex = [];
    $pokemon = [];
    $result = [];

    //Check if we have pokedex.
    if (isset($_SESSION["pokedex"])) {
        $pokedex = $_SESSION["pokedex"];
    }

    //Get all values from POST, if is not set, get empty value.
    $number = isset($_POST["number"]) ? $_POST["number"] : "";

    if (!empty($number)) {
        $index = findPokemonByNum($pokedex, $number);

        if ($index !== -1) {
            $pokemon = $pokedex[$index];

            $result = ["status_code" => 200];
        } else {
            $result = ["status_code" => 400, "message" => "This pokemon is not exists."];
        }
    } else {
        $result = ["status_code" => 400, "message" => "The number of pokemon is missing."];
    }

    //Add message to session.
    $_SESSION["response"] = $result;

    if ($result["status_code"] === 200) {
        $_SESSION["pokemon"] = $pokemon;

        //Redirect to pokemon_edit.php
        header("Location: ../php_views/pokemon_edit.php");
    } else {
        //Redirect to pokemon_list.php        
        header("Location: ../php_views/pokemon_list.php");
    }
}

function update()
{
    $pokedex = [];
    $result = [];

    //Check if we have pokedex.
    if (isset($_SESSION["pokedex"])) {
        $pokedex = $_SESSION["pokedex"];
    }

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
    if (!(empty($image) && empty($number))) {
        $img_path_tmp = $image["tmp_name"];
        $extension = pathinfo($image["name"], PATHINFO_EXTENSION);
        $img_path_relative = "../media/img/" . $number . "." . $extension;
        $img_path_absolute = "/pokedex/media/img/" . $number . "." . $extension;
    }

    //Create array associative of pokemon with values.
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
            //Get previous image path.
            $img_path_relative_previous = $pokemon["image_url"];

            //Delete previous image if exists.
            if (!file_exists($img_path_relative_previous) || unlink($img_path_relative_previous)) {
                //Add pokemon to pokedex.
                $result = updatePokemon($pokedex, $pokemon);

                //If the pokemon is added correctly.
                if ($result["status_code"] === 200) {
                    //Move the image path from temporal path to /media/img/.
                    if (!move_uploaded_file($img_path_tmp, $img_path_relative)) {
                        $result = ["status_code" => 400, "message" => "Error to upload the file."];
                    }
                }
            } else {
                $result = ["status_code" => 400, "message" => "Error to delete previous image."];
            }
        } else {
            $result = ["status_code" => 400, "message" => "The number of pokemon is not valid."];
        }
    } else {
        $result = ["status_code" => 400, "message" => "All fields is required."];
    }

    //Add pokedex to session with new values.
    $_SESSION["pokedex"] = $pokedex;

    //Add message to session.
    $_SESSION["response"] = $result;

    if ($result["status_code"] === 200) {
        //Redirect to pokemon_list.php
        header("Location: ../php_views/pokemon_list.php");
    } else {
        //Add pokemon data to session.
        $_SESSION["pokemon"] = $pokemon;

        //Redirect to pokemon_edit.php        
        header("Location: ../php_views/pokemon_edit.php");
    }
}

function delete()
{
    $pokedex = [];
    $result = [];

    //Check if we have pokedex.
    if (isset($_SESSION["pokedex"])) {
        $pokedex = $_SESSION["pokedex"];
    }

    //Get all values from POST, if is not set, get empty value.
    $number = isset($_POST["number"]) ? $_POST["number"] : "";

    if (!empty($number)) {
        $index = findPokemonByNum($pokedex, $number);

        if ($index !== -1) {
            $pokemon = $pokedex[$index];

            $extension = pathinfo($pokemon["image_url"], PATHINFO_EXTENSION);
            $img_path_relative = "../media/img/" . $pokemon["number"] . "." . $extension;

            //Delete pokemon.
            $result = deletePokemon($pokedex, $number);

            if ($result["status_code"] === 200) {
                //Delete the image of pokemon if exists.
                if (file_exists($img_path_relative) && !unlink($img_path_relative)) {
                    $result = ["status_code" => 400, "message" => "Error to delete the file."];
                }
            }
        } else {
            $result = ["status_code" => 400, "message" => "This pokemon is not exists."];
        }
    } else {
        $result = ["status_code" => 400, "message" => "The number of pokemon is missing."];
    }

    //Add pokedex to session with new values.
    $_SESSION["pokedex"] = $pokedex;

    //Add message to session.
    $_SESSION["response"] = $result;

    header("Location: ../php_views/pokemon_list.php");
}
