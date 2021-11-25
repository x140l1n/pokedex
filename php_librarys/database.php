<?php

define("HOST", "localhost");
define("DB", "pokedex");
define("USERNAME", "root");
define("PASSWORD", "");

class Database
{
    private $conn = null;

    public function __construct()
    {
        //If the variable conn is null then create connection.
        if (!$this->conn) {
            try {
                $this->conn = new PDO("mysql:host=" . HOST . ";dbname=" . DB, USERNAME, PASSWORD);
                $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); //Allow to throws exception when error ocurred from database.
                $this->conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false); //Avoid the integers values convert to string from database when prepares queries.
                $this->conn->exec("SET NAMES UTF8;"); //Allow accents in the database.
            } catch (PDOException $e) {
                throw $e;
            }
        }
    }

    /**
     * Get all pokemons or one pokemon from database ordered by number of pokemon.
     * @param  int $id (optional) The id of pokemon.
     * @throws PDOException Throws exception when error ocurred with database.
     * @return array Return array associative of all pokemons or one pokemon.
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
     * Insert new pokemon with types to database. If an error ocurred, rollback all changes.
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
                $statement->bindParam(":regiones_id", $pokemon["regiones_id"]);

                $statement->execute();

                $last_id = $this->conn->lastInsertId();
                
                //Save the types.
                foreach ($pokemon["tipos"] as $type) {
                    $statement = $this->conn->prepare("INSERT INTO tipos_has_pokemons VALUES (:tipos_id, :pokemons_id)");

                    $statement->bindParam(":tipos_id", $type["id"]);
                    $statement->bindParam(":pokemons_id", $last_id);

                    $statement->execute();
                }                

                //Commit all changes.
                $this->conn->commit();
            } catch (PDOException $e) {
                throw $e;

                //When an error ocurred, rollback all changes.
                $this->conn->rollBack();
            }
        }

        return $last_id;
    }

    public function __destruct()
    {
        if ($this->conn) $this->conn = null;
    }
}
