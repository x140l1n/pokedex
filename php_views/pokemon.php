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
    <form>
        <label>Número</label>
        <input type="text" maxlength="3" placeholder="Number pokemon" />
        <br />
        <label>Nombre</label>
        <input type="text" placeholder="Name pokemon" />
        <br />
        <label>Region</label>
        <select>
            <option value="kanto">Kanto</option>
            <option value="jotho">Jotho</option>
            <option value="hoenn">Hoenn</option>
            <option value="sinnoh">Sinnoh</option>
            <option value="teselia">Teselia</option>
        </select>
        <br />
        <label>Region</label>
        <input type="checkbox" name="type[]" value="plant" />
        <label>Plant</label>
        <input type="checkbox" name="type[]" value="poison" />
        <label>Poison</label>
        <input type="checkbox" name="type[]" value="fire" />
        <label>Fire</label>
        <input type="checkbox" name="type[]" value="fly" />
        <label>Fly</label>
        <input type="checkbox" name="type[]" value="water" />
        <label>Water</label>
        <input type="checkbox" name="type[]" value="electric" />
        <label>Electric</label>
        <input type="checkbox" name="type[]" value="fairy" />
        <label>Fairy</label>
        <input type="checkbox" name="type[]" value="bug" />
        <label>Bug</label>
        <input type="checkbox" name="type[]" value="fight" />
        <label>Fight</label>
        <input type="checkbox" name="type[]" value="psychic" />
        <label>Psychic</label>
        <br />
        <label>Height</label>
        <input type="number" min="1" />
        <br />
        <label>Weight</label>
        <input type="number" min="0" step="0.01" />
        <br />
        <label>Evolution</label>
        <input type="radio" name="radioEvolution" value="no-evol" checked />
        <label>No evolution</label>
        <input type="radio" name="radioEvolution" value="first-evol" />
        <label>First evolution</label>
        <input type="radio" name="radioEvolution" value="second-evol" />
        <label>Second evolution</label>
        <br />
        <label>Image</label>
        <input type="file" />
        <br />
        <button type="submit">Submit</button><a href="#">Cancel</a>
    </form>
</body>

</html>