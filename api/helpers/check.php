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

    function check_auth_token($auth_token)
    {
        $query = "SELECT id
                  FROM USERS
                  WHERE auth_token=:auth_token";

        $stmt = $this->conn->prepare($query);

        $auth_token = htmlspecialchars(strip_tags($auth_token));
        $stmt->bindParam(":auth_token", $auth_token);

        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row['id'];
    }
}
