<?php
class SortieInscription
{

    // database connection and table name
    private $conn;
    private $table_name = "sorties_inscription";

    // object properties
    public $sorties_id;
    public $users_id;
    public $quantiteDechetsRamasses;



    // constructor with $db as database connection
    public function __construct($db)
    {
        $this->conn = $db;
    }


    // create product
    function create()
    {

        // query to insert record
        $query = "INSERT INTO
                " . $this->table_name . "
            SET
            sorties_id=:sorties_id, 
            users_id=:users_id";

        // prepare query
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->sorties_id = htmlspecialchars(strip_tags($this->sorties_id));
        $this->users_id = htmlspecialchars(strip_tags($this->users_id));

        // bind values
        $stmt->bindParam(":sorties_id", $this->sorties_id);
        $stmt->bindParam(":users_id", $this->users_id);

        // execute query
        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    // create product
    function checkSubscribedStatus()
    {

        // query to insert record
        $query = "SELECT users_id FROM
                " . $this->table_name . "
            WHERE
                sorties_id=:sorties_id AND
                users_id=:users_id";

        // prepare query
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->sorties_id = htmlspecialchars(strip_tags($this->sorties_id));
        $this->users_id = htmlspecialchars(strip_tags($this->users_id));

        // bind values
        $stmt->bindParam(":sorties_id", $this->sorties_id);
        $stmt->bindParam(":users_id", $this->users_id);


        // execute query
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row['users_id'] != null)
            return true;

        return false;
    }

    function removeSuscribtion()
    {

        // update query
        $query = "DELETE FROM
                    " . $this->table_name . "
                WHERE
                sorties_id=:sorties_id AND
                users_id=:users_id";

        // prepare query
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->sorties_id = htmlspecialchars(strip_tags($this->sorties_id));
        $this->users_id = htmlspecialchars(strip_tags($this->users_id));

        // bind values
        $stmt->bindParam(":sorties_id", $this->sorties_id);
        $stmt->bindParam(":users_id", $this->users_id);

        // execute the query
        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    function getInscriptionList()
    {

        // query to insert record
        $query = "SELECT username FROM
                " . $this->table_name . " AS S
                LEFT JOIN USERS AS U ON S.users_id = U.id
            WHERE
                sorties_id=:sorties_id";

        // prepare query
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->sorties_id = htmlspecialchars(strip_tags($this->sorties_id));

        // bind values
        $stmt->bindParam(":sorties_id", $this->sorties_id);

        // execute the query
        $stmt->execute();
        return $stmt;
    }

    function IndicationDechets()
    {

        // update query
        $query = "UPDATE
                    " . $this->table_name . "
                    SET 
                quantiteDechetsRamasses=:quantiteDechetsRamasses
                    WHERE
                sorties_id=:sorties_id
                    AND 
                users_id=:id";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->quantiteDechetsRamasses = htmlspecialchars(strip_tags($this->quantiteDechetsRamasses));
        $this->sorties_id = htmlspecialchars(strip_tags($this->sorties_id));
        $this->id = htmlspecialchars(strip_tags($this->id));


        // bind new values

        $stmt->bindParam(":quantiteDechetsRamasses", $this->quantiteDechetsRamasses);
        $stmt->bindParam(":sorties_id", $this->sorties_id);
        $stmt->bindParam(":id", $this->id);


        // execute the query
        $stmt->execute();
        return $stmt;
    }


}
