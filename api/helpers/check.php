<?php

class Check
{
    // database connection and table name
    private $conn;

    // constructor with $db as database connection
    public function __construct($db)
    {
        $this->conn = $db;
    }

    function check_authToken($authToken)
    {
        $query = "SELECT id
                  FROM USERS
                  WHERE authToken=:authToken";

        $stmt = $this->conn->prepare($query);

        $authToken = htmlspecialchars(strip_tags($authToken));
        $stmt->bindParam(":authToken", $authToken);

        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row['id'];
    }
}
