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

    function getNextEvenement()
    {
        // select all query
        $query = "SELECT * 
                  FROM " . $this->table_name . " 
                  ORDER BY creation_date DESC
                  LIMIT 1";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // execute query
        $stmt->execute();

        return $stmt;
    }


    function updateCourse()
    {

        // update query
        $query = "UPDATE
                    " . $this->table_name . "
                SET
                title=:title, 
                description=:description, 
                author=:author,
                occured_date=:occured_date, 

                WHERE
                    id = :id";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // sanitize
        $stmt->bindParam(":title", $this->title);
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam(":author", $this->author);
        $stmt->bindParam(":occured_date", $this->occured_date);
        // bind new values
        $stmt->bindParam(":title", $this->title);
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam(":author", $this->author);
        $stmt->bindParam(":occured_date", $this->occured_date);

        // execute the query
        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    function getEvenementId()
    {
        $query = "SELECT id
                  FROM  " . $this->table_name . "
                  WHERE                     
                  title=:title AND
                  description=:description AND
                  author=:author AND 
                  occured_date=:occured_date";

        $stmt = $this->conn->prepare($query);

        $title = htmlspecialchars(strip_tags($this->title));
        $description = htmlspecialchars(strip_tags($this->description));
        $author = htmlspecialchars(strip_tags($this->author));
        $occured_date = htmlspecialchars(strip_tags($this->occured_date));


        $stmt->bindParam(":title", $title);
        $stmt->bindParam(":description", $description);
        $stmt->bindParam(":author", $author);
        $stmt->bindParam(":occured_date", $occured_date);

        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row['id'];
    }
}
