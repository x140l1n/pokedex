<!DOCTYPE html>
<html lang="en">

<head>
    <?php
    //Import the links styles. 
    require("../php_partials/head.php")
    ?>
    <title>Pokémon list</title>
</head>

<body>
    <?php require("../php_partials/navbar.php") ?>
    <div class="container-fluid p-4">
        <div class="row row-cols-1 row-cols-md-5 g-4">
            <div class="col">
                <div class="card border border-secondary">
                    <img src="../users/img/001.png" class="card-img-top" alt="Bulbasaur">
                    <div class="card-body">
                        <h5 class="card-title">001 - Bulbasaur</h5>
                        <p class="card-text">
                            <span class="badge bg-warning text-dark">Warning</span>
                            <span class="badge bg-warning text-dark">Warning</span>
                        </p>
                    </div>
                    <div class="card-footer">
                        <div class="d-grid gap-2 d-sm-flex justify-content-sm-end">
                            <button class="btn btn-outline-danger" title="Delete pokémon" type="button">
                                <i class="fas fa-trash"></i>
                            </button>
                            <button class="btn btn-outline-primary" title="Edit pokémon" type="button">
                                <i class="fas fa-edit"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <a class="btn btn-warning btn-lg rounded-circle btn-floating" title="Add new pokémon" href="pokemon.php">
            <i class="fas fa-plus"></i> 
        </a>
    </div>
    <script type="text/javascript" src="../UI/bootstrap-5.0.2/dist/js/bootstrap.min.js"></script>
</body>

</html>