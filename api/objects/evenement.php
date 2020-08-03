<?php
class Evenement
{

    // database connection and table name
    private $conn;
    private $table_name = "evenements";

    // object properties
    public $id;
    public $title;
    public $description;
    public $creation_date;
    public $occured_date;
    public $author;
    public $status;


    // constructor with $db as database connection
    public function __construct($db)
    {
        $this->conn = $db;
    }

    // read products
    function getList()
    {

        // select all query
        $query = "SELECT * FROM " . $this->table_name;

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // execute query
        $stmt->execute();

        return $stmt;
    }

    // create product
    function create()
    {

        // query to insert record
        $query = "INSERT INTO
                " . $this->table_name . "
            SET
                title=:title, 
                description=:description, 
                author=:author,
                occured_date=:occured_date";

        // prepare query
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->title = htmlspecialchars(strip_tags($this->title));
        $this->description = htmlspecialchars(strip_tags($this->description));
        $this->author = htmlspecialchars(strip_tags($this->author));
        $this->occured_date = htmlspecialchars(strip_tags($this->occured_date));


        // bind values
        $stmt->bindParam(":title", $this->title);
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam(":author", $this->author);
        $stmt->bindParam(":occured_date", $this->occured_date);


        // execute query
        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    function getListUserEvent()
    {

        // query to insert record
        $query = "SELECT * FROM  
                " . $this->table_name . " AS E
                LEFT JOIN EVENEMENT_SEEN S 
                ON S.evenement_id=E.id
                WHERE S.user_id=:user_id
                AND is_delete=0
                ";

        // prepare query
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->user_id = htmlspecialchars(strip_tags($this->user_id));

        // bind values
        $stmt->bindParam(":user_id", $this->user_id);

        // execute the query
        $stmt->execute();
        return $stmt;
    }


    
    function getNotSeenList()
    {

        // query to insert record
        $query = "SELECT * FROM  evenements AS E
                LEFT JOIN EVENEMENT_SEEN AS S 
                ON E.id = S.evenement_id AND S.user_id =:user_id
                WHERE  S.evenement_id IS NULL
                ";

        // prepare query
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->user_id = htmlspecialchars(strip_tags($this->user_id));

        // bind values
        $stmt->bindParam(":user_id", $this->user_id);

        // execute the query
        $stmt->execute();
        return $stmt;
    }
}
