<?php
class Thread
{

    // database connection and table name
    private $conn;
    private $table_name = "threads";

    // object properties
    public $id;
    public $subject;
    public $author;
    public $text;
    public $creation_date;



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
                subject=:subject, 
                author=:author, 
                text=:text";

        // prepare query
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->subject = htmlspecialchars(strip_tags($this->subject));
        $this->author = htmlspecialchars(strip_tags($this->author));
        $this->text = htmlspecialchars(strip_tags($this->text));


        // bind values
        $stmt->bindParam(":subject", $this->subject);
        $stmt->bindParam(":author", $this->author);
        $stmt->bindParam(":text", $this->text);


        // execute query
        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

}
