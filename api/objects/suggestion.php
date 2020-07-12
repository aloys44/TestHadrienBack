<?php
class Suggestion
{

    // database connection and table name
    private $conn;
    private $table_name = "suggestions";

    // object properties
    public $id;
    public $title;
    public $description;
    public $like_count;
    public $creation_date;
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
        $query = "SELECT
                    S.id,
                    title, 
                    description, 
                    U.username as author, 
                    S.`creation_date`, 
                    COALESCE(SUM(SLC.is_liked = 1), 0) AS likes, 
                    COALESCE(SUM(SLC.is_liked = 0), 0) AS dislikes 
                FROM `suggestions` AS S 
                LEFT JOIN `users` AS U ON U.id = S.author 
                LEFT JOIN `suggestion_like_count` AS SLC ON SLC.suggestion_id = S.id
                GROUP BY S.id";

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
                author=:author";

        // prepare query
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->title = htmlspecialchars(strip_tags($this->title));
        $this->description = htmlspecialchars(strip_tags($this->description));
        $this->author = htmlspecialchars(strip_tags($this->author));

        // bind values
        $stmt->bindParam(":title", $this->title);
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam(":author", $this->author);

        // execute query
        if ($stmt->execute()) {
            return true;
        }

        return false;
    }
    // read products
    function readSuggestionSortByLike()
    {

        // select all query
        $query = "SELECT
                        * 
                    FROM
                        " . $this->table_name . " 
                        ORDER BY like_count DESC
                        LIMIT 3 ";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // execute query
        $stmt->execute();

        return $stmt;
    }

    function getSuggestionId()
    {
        $query = "SELECT id
                  FROM  " . $this->table_name . "
                  WHERE title=:title AND description=:description";

        $stmt = $this->conn->prepare($query);

        $title = htmlspecialchars(strip_tags($this->title));
        $description = htmlspecialchars(strip_tags($this->description));

        $stmt->bindParam(":title", $title);
        $stmt->bindParam(":description", $description);

        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row['id'];
    }
}
