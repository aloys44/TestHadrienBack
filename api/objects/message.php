<?php
class Message
{

    // database connection and table name
    private $conn;
    private $table_name = "messages";

    // object properties
    public $id;
    public $thread;
    public $subject;
    public $creation_date;
    public $author;
    public $text;




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
                thread=:thread, 
                subject=:subject, 
                author=:author, 
                text=:text";

        // prepare query
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->thread = htmlspecialchars(strip_tags($this->thread));
        $this->subject = htmlspecialchars(strip_tags($this->subject));
        $this->author = htmlspecialchars(strip_tags($this->author));
        $this->text = htmlspecialchars(strip_tags($this->text));



        // bind values
        $stmt->bindParam(":thread", $this->thread);
        $stmt->bindParam(":subject", $this->subject);
        $stmt->bindParam(":author", $this->author);
        $stmt->bindParam(":text", $this->text);

        // execute query
        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    function getMessagesSorted()
    {
    
        // select all query
        $query = "SELECT
                    * 
                FROM
                    " . $this->table_name . " 
                    WHERE thread=:thread ";
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);

        $this->thread = htmlspecialchars(strip_tags($this->thread));

        $stmt->bindParam(":thread", $this->thread);

    
        // execute query
        $stmt->execute();
    
        return $stmt;
}


}
