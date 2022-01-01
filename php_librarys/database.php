<?php

define("HOST", "localhost");
define("DB", "pokedex");
define("USERNAME", "root");
define("PASSWORD", "");

class Database
{
    private $conn = null;

    /**
     * Create the database connection.
     * 
     * @throws PDOException Throws exception when error ocurred with database.
     * @return void
     */
    public function __construct()
    {
        //If the variable conn is null then create connection.
        if (!$this->conn) {
            try {
                $this->conn = new PDO("mysql:host=" . HOST . ";dbname=" . DB, USERNAME, PASSWORD);
                $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); //Allow to throws exception when error ocurred from database.
                $this->conn->exec("SET NAMES UTF8;"); //Allow accents in the database.
            } catch (PDOException $e) {
                throw $e;
            }
        }
    }

    /**
     * Get all pokemons data or one pokemon data from database ordered by number of pokemon.
     * 
     * @param  int $id (optional) The id of pokemon.
     * @throws PDOException Throws exception when error ocurred with database.
     * @return array Return array associative of all pokemons data or one pokemon data.
     */
    public function SelectPokemons($id = -1)
    {
        $pokemons = [];

        if ($this->conn) {
            try {
                if ($id !== -1) {
                    $statement = $this->conn->prepare(" SELECT pokemons.*, regiones.id as id_region, regiones.nombre as region
                                                    FROM pokemons 
                                                    INNER JOIN regiones ON regiones.id = pokemons.regiones_id 
                                                    WHERE pokemons.id = :id");

                    $statement->bindParam(":id", $id);
                } else {
                    $statement = $this->conn->prepare(" SELECT pokemons.*, regiones.id as id_region, regiones.nombre as region
                                                    FROM pokemons 
                                                    INNER JOIN regiones ON regiones.id = pokemons.regiones_id 
                                                    ORDER BY numero");
                }

                $statement->execute();

                foreach ($statement->fetchAll(PDO::FETCH_ASSOC) as $pokemon) {
                    $types = $this->SelectPokemonTypes($pokemon["id"]);
                    $pokemon = array_merge($pokemon, array("tipos" => $types)); //Merge the array associative of types and pokemon data.
                    array_push($pokemons, $pokemon);
                }
            } catch (PDOException $e) {
                throw $e;
            }
        }

        return $pokemons;
    }

    /**
     * Get all types of one pokemon from database.
     * 
     * @param  int $id The id of pokemon.
     * @throws PDOException Throws exception when error ocurred with database.
     * @return array Return array associative of types.
     */
    public function SelectPokemonTypes($id)
    {
        $types = [];

        if ($this->conn) {
            try {
                $statement = $this->conn->prepare(" SELECT tipos.*
                                                    FROM tipos 
                                                    INNER JOIN tipos_has_pokemons ON tipos_has_pokemons.tipos_id = tipos.id 
                                                    WHERE tipos_has_pokemons.pokemons_id = :id");
                $statement->bindParam(":id", $id);
                $statement->execute();

                $types = $statement->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                throw $e;
            }
        }

        return $types;
    }

    /**
     * Get all types or one type from database ordered by name of type.
     * 
     * @param  int $id (optional) The id of type.
     * @throws PDOException Throws exception when error ocurred with database.
     * @return array Return array associative of all types or one type.
     */
    public function SelectTypes($id = -1)
    {
        $types = [];

        if ($this->conn) {
            try {
                if ($id !== -1) {
                    $statement = $this->conn->prepare(" SELECT *
                                                        FROM tipos 
                                                        WHERE id = :id");

                    $statement->bindParam(":id", $id);
                } else {
                    $statement = $this->conn->prepare(" SELECT *
                                                        FROM tipos 
                                                        ORDER BY nombre");
                }

                $statement->execute();

                $types = $statement->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                throw $e;
            }
        }

        return $types;
    }

    /**
     * Get all regions or one region from database ordered by name of region.
     * 
     * @param  int $id (optional) The id of region.
     * @throws PDOException Throws exception when error ocurred with database.
     * @return array Return array associative of all regions or one region.
     */
    public function SelectRegions($id = -1)
    {
        $types = [];

        if ($this->conn) {
            try {
                if ($id !== -1) {
                    $statement = $this->conn->prepare(" SELECT *
                                                        FROM regiones 
                                                        WHERE id = :id");

                    $statement->bindParam(":id", $id);
                } else {
                    $statement = $this->conn->prepare(" SELECT *
                                                        FROM regiones 
                                                        ORDER BY nombre");
                }

                $statement->execute();

                $types = $statement->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                throw $e;
            }
        }

        return $types;
    }

    /**
     * Insert new pokemon with types to database. If an error ocurred rollback all changes.
     * 
     * @param  array $pokemon The pokemon data.
     * @throws PDOException Throws exception when error ocurred with database.
     * @return int Return the id of new pokemon inserted.
     */
    public function InsertPokemon($pokemon)
    {
        $last_id = -1;

        if ($this->conn) {
            try {
                //Start the transaction.
                $this->conn->beginTransaction();

                //Save the pokemon.
                $statement = $this->conn->prepare(" INSERT INTO pokemons (numero, nombre, altura, peso, evolucion, imagen, regiones_id) 
                                                    VALUES (:numero, :nombre, :altura, :peso, :evolucion, :imagen, :regiones_id)");

                $statement->bindParam(":numero", $pokemon["numero"]);
                $statement->bindParam(":nombre", $pokemon["nombre"]);
                $statement->bindParam(":altura", $pokemon["altura"]);
                $statement->bindParam(":peso", $pokemon["peso"]);
                $statement->bindParam(":evolucion", $pokemon["evolucion"]);
                $statement->bindParam(":imagen", $pokemon["imagen"]);
                $statement->bindParam(":regiones_id", $pokemon["region"]);

                $statement->execute();

                $new_id = $this->conn->lastInsertId();

                if ($new_id !== false) {
                    //Save the types.
                    foreach ($pokemon["tipos"] as $type) {
                        $statement = $this->conn->prepare("INSERT INTO tipos_has_pokemons VALUES (:tipos_id, :pokemons_id)");

                        $statement->bindParam(":tipos_id", $type);
                        $statement->bindParam(":pokemons_id", $new_id);

                        $statement->execute();
                    }
                    
                    //Commit all changes if the transaction is currently active.
                    if ($this->conn->inTransaction()) $this->conn->commit();

                    $last_id = $new_id;
                }
            } catch (PDOException $e) {
                //When an error ocurred and the transaction is currently active rollback all changes.
                if ($this->conn->inTransaction()) $this->conn->rollBack();

                //Throw the PDOException.
                throw $e;
            }
        }

        return $last_id;
    }

    /**
     * Delete a pokemon from the database.
     * 
     * @param  string $id The id of pokemon to delete.
     * @throws PDOException Throws exception when error ocurred with database.
     * @return int Return the id of pokemon deleted.
     */
    public function DeletePokemon($id)
    {
        $id_deleted = -1;

        if ($this->conn) {
            try {
                //Save the pokemon.
                $statement = $this->conn->prepare("DELETE FROM pokemons WHERE id = :id");

                $statement->bindParam(":id", $id);

                $statement->execute();

                if ($statement->rowCount() === 1) {
                    $id_deleted = $id;
                }
            } catch (PDOException $e) {
                //Throw the PDOException.
                throw $e;
            }
        }

        return $id_deleted;
    }

    /**
     * Update a pokemon from database. If an error ocurred rollback all changes.
     * 
     * @param  array $pokemon The pokemon data updated.
     * @param  string $id The id of pokemon to update.
     * @throws PDOException Throws exception when error ocurred with database.
     * @return int Return the id of pokemon updated.
     */
    public function UpdatePokemon($pokemon, $id)
    {
        $id_updated = -1;

        if ($this->conn) {
            try {
                //Start the transaction.
                $this->conn->beginTransaction();

                //Save the pokemon.
                $statement = $this->conn->prepare(" UPDATE pokemons SET numero = :numero, 
                                                                        nombre = :nombre, 
                                                                        altura = :altura, 
                                                                        peso = :peso, 
                                                                        evolucion = :evolucion, 
                                                                        imagen = :imagen, 
                                                                        regiones_id = :regiones_id 
                                                    WHERE id = :id");

                $statement->bindParam(":numero", $pokemon["numero"]);
                $statement->bindParam(":nombre", $pokemon["nombre"]);
                $statement->bindParam(":altura", $pokemon["altura"]);
                $statement->bindParam(":peso", $pokemon["peso"]);
                $statement->bindParam(":evolucion", $pokemon["evolucion"]);
                $statement->bindParam(":imagen", $pokemon["imagen"]);
                $statement->bindParam(":regiones_id", $pokemon["region"]);
                $statement->bindParam(":id", $id);

                $statement->execute();

                //Delete all previous types of pokemon.
                $statement = $this->conn->prepare("DELETE FROM tipos_has_pokemons WHERE pokemons_id = :id");

                $statement->bindParam(":id", $id);

                $statement->execute();

                //Save the new types.
                foreach ($pokemon["tipos"] as $type) {
                    $statement = $this->conn->prepare("INSERT INTO tipos_has_pokemons VALUES (:tipos_id, :pokemons_id)");

                    $statement->bindParam(":tipos_id", $type);
                    $statement->bindParam(":pokemons_id", $id);

                    $statement->execute();
                }

                //Commit all changes if the transaction is currently active.
                if ($this->conn->inTransaction()) $this->conn->commit();

                $id_updated = $id;
            } catch (PDOException $e) {
                //When an error ocurred and the transaction is currently active rollback all changes.
                if ($this->conn->inTransaction()) $this->conn->rollBack();

                //Throw the PDOException.
                throw $e;
            }
        }

        return $id_updated;
    }

    /**
     * Close the connection if is different than null.
     *
     * @return void
     */
    public function __destruct()
    {
        if ($this->conn) $this->conn = null;
    }
}
