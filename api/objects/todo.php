<?php
class Todo
{

    // database connection and table name
    private $conn;
    private $table_name = "todos";

    // object properties
    public $id;
    public $title;
    public $description;
    public $category;
    public $like_Count;
    public $creation_date;
    public $anticipatedEnd_realisation;
    public $author;


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
                category=:category,
                author=:author"
                ;

        // prepare query
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->title = htmlspecialchars(strip_tags($this->title));
        $this->description = htmlspecialchars(strip_tags($this->description));
        $this->category = htmlspecialchars(strip_tags($this->category));
        $this->author = htmlspecialchars(strip_tags($this->author));


        // bind values
        $stmt->bindParam(":title", $this->title);
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam(":category", $this->category);
        $stmt->bindParam(":author", $this->author);


        // execute query
        if ($stmt->execute()) {
            return true;
        }

        return false;
    }
    // read products
    
        function readTodosSortedByCategory1()
        {
        
            // select all query
            $query = "SELECT
                        * 
                    FROM
                        " . $this->table_name . " 
                        WHERE category=1 ";
        
            // prepare query statement
            $stmt = $this->conn->prepare($query);
        
            // execute query
            $stmt->execute();
        
            return $stmt;
    }
    function readTodosSortedByCategory2()
        {
        
            // select all query
            $query = "SELECT
                        * 
                    FROM
                        " . $this->table_name . " 
                        WHERE category=2 ";
        
            // prepare query statement
            $stmt = $this->conn->prepare($query);
        
            // execute query
            $stmt->execute();
        
            return $stmt;
    }
    function readTodosSortedByCategory3()
    {

        // select all query
        $query = "SELECT
                    * 
                FROM
                    " . $this->table_name . " 
                    WHERE category=3 ";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // execute query
        $stmt->execute();

        return $stmt;
    }
    function readTodosSortedByCategory4()
    {

        // select all query
        $query = "SELECT
                    * 
                FROM
                    " . $this->table_name . " 
                    WHERE category=4 ";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // execute query
        $stmt->execute();

        return $stmt;
    }
    function readTodosSortedByCategoryUser()
    {

        // select all query
        $query = "SELECT
                    * 
                FROM
                    " . $this->table_name . " 
                    WHERE category='test'";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // execute query
        $stmt->execute();

        return $stmt;
    }
    
}
