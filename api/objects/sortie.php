<?php
class Sortie
{

    // database connection and table name
    private $conn;
    private $table_name = "sorties";

    // object properties
    public $id;
    public $title;
    public $description;
    public $runimage;
    public $walkimage;
    public $nbMaxWalk_participants;
    public $nbMaxRun_participants;
    public $creation_date;
    public $running_date;
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
                author=:author,
                nbMaxWalk_participants=:nbMaxWalk_participants,
                nbMaxRun_participants=:nbMaxRun_participants";

        // prepare query
        $stmt = $this->conn->prepare($query);



        // sanitize
        $this->title = htmlspecialchars(strip_tags($this->title));
        $this->description = htmlspecialchars(strip_tags($this->description));
        $this->author = htmlspecialchars(strip_tags($this->author));
        $this->nbMaxWalk_participants = htmlspecialchars(strip_tags($this->nbMaxWalk_participants));
        $this->nbMaxRun_participants = htmlspecialchars(strip_tags($this->nbMaxRun_participants));


        // bind values
        $stmt->bindParam(":title", $this->title);
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam(":author", $this->author);
        $stmt->bindParam(":nbMaxWalk_participants", $this->nbMaxWalk_participants);
        $stmt->bindParam(":nbMaxRun_participants", $this->nbMaxRun_participants);


        // execute query
        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    function getNextSortie()
    {
        // select all query
        $query = "SELECT * 
                  FROM " . $this->table_name . " 
                  ORDER BY running_date DESC
                  LIMIT 1 ";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // execute query
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
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
                    nbMaxWalk_participants=:nbMaxWalk_participants, 
                    nbMaxRun_participants=:nbMaxRun_participants, 

                WHERE
                    id = :id";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // sanitize
        $stmt->bindParam(":title", $this->title);
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam(":author", $this->author);
        $stmt->bindParam(":nbMaxWalk_participants", $this->nbMaxWalk_participants);
        $stmt->bindParam(":nbMaxRun_participants", $this->nbMaxRun_participants);

        // bind new values
        $stmt->bindParam(":title", $this->title);
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam(":author", $this->author);
        $stmt->bindParam(":nbMaxWalk_participants", $this->nbMaxWalk_participants);
        $stmt->bindParam(":nbMaxRun_participants", $this->nbMaxRun_participants);

        // execute the query
        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    function getSortieId()
    {
        $query = "SELECT id
                  FROM  " . $this->table_name . "
                  WHERE                     
                  title=:title AND
                  description=:description AND
                  author=:author AND 
                  nbMaxWalk_participants=:nbMaxWalk_participants AND 
                  nbMaxRun_participants=:nbMaxRun_participants";

        $stmt = $this->conn->prepare($query);

        $title = htmlspecialchars(strip_tags($this->title));
        $description = htmlspecialchars(strip_tags($this->description));
        $author = htmlspecialchars(strip_tags($this->author));
        $nbMaxWalk_participants = htmlspecialchars(strip_tags($this->nbMaxWalk_participants));
        $nbMaxRun_participants = htmlspecialchars(strip_tags($this->nbMaxRun_participants));


        $stmt->bindParam(":title", $title);
        $stmt->bindParam(":description", $description);
        $stmt->bindParam(":author", $author);
        $stmt->bindParam(":nbMaxWalk_participants", $nbMaxWalk_participants);
        $stmt->bindParam(":nbMaxRun_participants", $nbMaxRun_participants);

        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row['id'];
    }
}
