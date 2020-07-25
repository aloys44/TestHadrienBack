<?php
class Evenement_Seen
{

    // database connection and table name
    private $conn;
    private $table_name = "evenement_seen";

    // object properties
    public $evenement_id ;
    public $user_id;
    public $is_seen;

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
                    user_id=:user_id, 
                    is_seen=:is_seen";
    
            // prepare query
            $stmt = $this->conn->prepare($query);
    
            // sanitize
            $this->evenement_id = htmlspecialchars(strip_tags($this->evenement_id));
            $this->user_id = htmlspecialchars(strip_tags($this->user_id));
            $this->is_seen = htmlspecialchars(strip_tags($this->is_seen));
    
            // bind values
            $stmt->bindParam(":evenement_id", $this->evenement_id);
            $stmt->bindParam(":user_id", $this->user_id);
            $stmt->bindParam(":is_seen", $this->is_seen);
    
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
                    is_seen = :is_seen
                WHERE
                    evenement_id=:evenement_id AND
                    user_id=:user_id";

        // prepare query
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->evenement_id = htmlspecialchars(strip_tags($this->evenement_id));
        $this->user_id = htmlspecialchars(strip_tags($this->user_id));
        $this->is_seen = htmlspecialchars(strip_tags($this->is_seen));

        // bind values
        $stmt->bindParam(":evenement_id", $this->evenement_id);
        $stmt->bindParam(":user_id", $this->user_id);
        $stmt->bindParam(":is_seen", $this->is_seen);


        // execute the query
        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    function getNotSeenList()
    {

        // query to insert record
        $query = "SELECT id,title,description,creation_date,author,occured_date,status FROM
                " . $this->table_name . " AS S
                LEFT JOIN EVENEMENTS AS E ON S.evenement_id = E.id
                NOT IN(user_id)
                ";

        // prepare query
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->evenement_id = htmlspecialchars(strip_tags($this->user_id));

        // bind values
        $stmt->bindParam(":user_id", $this->user_id);

        // execute the query
        $stmt->execute();
        return $stmt;
    }
}
