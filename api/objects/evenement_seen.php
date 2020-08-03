<?php
class Evenement_Seen
{

    // database connection and table name
    private $conn;
    private $table_name = "evenement_seen";

    // object properties
    public $evenement_id ;
    public $user_id;
    public $is_delete;

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
                    evenement_id=:evenement_id, 
                    user_id=:user_id";
    
            // prepare query
            $stmt = $this->conn->prepare($query);
    
            // sanitize
            $this->evenement_id = htmlspecialchars(strip_tags($this->evenement_id));
            $this->user_id = htmlspecialchars(strip_tags($this->user_id));
    
            // bind values
            $stmt->bindParam(":evenement_id", $this->evenement_id);
            $stmt->bindParam(":user_id", $this->user_id);
    
            // execute query
            if ($stmt->execute()) {
                return true;
            }
    
            return false;
        }
    
    // create product
    function checkSeenStatus()
    {

        // query to insert record
        $query = "SELECT evenement_id FROM
                " . $this->table_name . "
            WHERE
                evenement_id=:evenement_id AND
                user_id=:user_id";

        // prepare query
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->evenement_id = htmlspecialchars(strip_tags($this->evenement_id));
        $this->user_id = htmlspecialchars(strip_tags($this->user_id));

        // bind values
        $stmt->bindParam(":evenement_id", $this->evenement_id);
        $stmt->bindParam(":user_id", $this->user_id);

        // execute query
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row['evenement_id'] != null)
            return true;

        return false;
    }

    function updateSeen()
    {

        // update query
        $query = "UPDATE
                    " . $this->table_name . "
                SET
                    is_delete = :is_delete
                WHERE
                    evenement_id=:evenement_id AND
                    user_id=:user_id";

        // prepare query
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->evenement_id = htmlspecialchars(strip_tags($this->evenement_id));
        $this->user_id = htmlspecialchars(strip_tags($this->user_id));
        $this->is_delete = htmlspecialchars(strip_tags($this->is_delete));

        // bind values
        $stmt->bindParam(":evenement_id", $this->evenement_id);
        $stmt->bindParam(":user_id", $this->user_id);
        $stmt->bindParam(":is_delete", $this->is_delete);


        // execute the query
        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    function delete()
    {

        // update query
        $query = "UPDATE
                    
                    " . $this->table_name . " 
                SET
                    evenement_id=:evenement_id, 
                    user_id=:user_id,
                    is_delete=1
                WHERE
                evenement_id=:evenement_id 
                AND user_id=:user_id";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->evenement_id = htmlspecialchars(strip_tags($this->evenement_id));
        $this->user_id = htmlspecialchars(strip_tags($this->user_id));


        // bind new values

        $stmt->bindParam(":evenement_id", $this->evenement_id);
        $stmt->bindParam(":user_id", $this->user_id);


        $stmt->execute();
        return $stmt;
    
    }
}
