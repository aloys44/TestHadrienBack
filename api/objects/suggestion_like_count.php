<?php
class SuggestionLikeCount
{

    // database connection and table name
    private $conn;
    private $table_name = "suggestion_like_count";

    // object properties
    public $suggestion_id;
    public $author_id;
    public $is_liked;

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
                suggestion_id=:suggestion_id, 
                author_id=:author_id, 
                is_liked=:is_liked";

        // prepare query
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->suggestion_id = htmlspecialchars(strip_tags($this->suggestion_id));
        $this->author_id = htmlspecialchars(strip_tags($this->author_id));
        $this->is_liked = htmlspecialchars(strip_tags($this->is_liked));

        // bind values
        $stmt->bindParam(":suggestion_id", $this->suggestion_id);
        $stmt->bindParam(":author_id", $this->author_id);
        $stmt->bindParam(":is_liked", $this->is_liked);

        // execute query
        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    // create product
    function checkLikedStatus()
    {

        // query to insert record
        $query = "SELECT suggestion_id FROM
                " . $this->table_name . "
            WHERE
                suggestion_id=:suggestion_id AND
                author_id=:author_id";

        // prepare query
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->suggestion_id = htmlspecialchars(strip_tags($this->suggestion_id));
        $this->author_id = htmlspecialchars(strip_tags($this->author_id));

        // bind values
        $stmt->bindParam(":suggestion_id", $this->suggestion_id);
        $stmt->bindParam(":author_id", $this->author_id);

        // execute query
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row['suggestion_id'] != null)
            return true;

        return false;
    }

    function updateLike()
    {

        // update query
        $query = "UPDATE
                    " . $this->table_name . "
                SET
                    is_liked = :is_liked
                WHERE
                    suggestion_id=:suggestion_id AND
                    author_id=:author_id";

        // prepare query
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->suggestion_id = htmlspecialchars(strip_tags($this->suggestion_id));
        $this->author_id = htmlspecialchars(strip_tags($this->author_id));
        $this->is_liked = htmlspecialchars(strip_tags($this->is_liked));

        // bind values
        $stmt->bindParam(":suggestion_id", $this->suggestion_id);
        $stmt->bindParam(":author_id", $this->author_id);
        $stmt->bindParam(":is_liked", $this->is_liked);


        // execute the query
        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    function getLikeList()
    {

        // query to insert record
        $query = "SELECT username FROM
                " . $this->table_name . " AS S
                LEFT JOIN USERS AS U ON S.author_id = U.id
            WHERE
                suggestion_id=:suggestion_id";

        // prepare query
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->suggestion_id = htmlspecialchars(strip_tags($this->suggestion_id));

        // bind values
        $stmt->bindParam(":suggestion_id", $this->suggestion_id);

        // execute the query
        $stmt->execute();
        return $stmt;
    }
}
