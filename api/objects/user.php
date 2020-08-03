<?php
class User
{

    // database connection and table name
    private $conn;
    private $table_name = "users";

    // object properties
    public $id;
    public $username;
    public $password;
    public $firstName;
    public $lastName;
    public $email;
    public $experience;
    public $photo;
    public $quantiteDechets;
    public $roles;
    public $last_connection;
    public $auth_token;
    public $remember_account;


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

    function getPassword()
    {

        // query to read single record
        $query = "SELECT
                    password
                FROM
                    " . $this->table_name . " 
                WHERE
                    username = :username";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->username = htmlspecialchars(strip_tags($this->username));

        // bind values
        $stmt->bindParam(":username", $this->username);

        // execute query
        $stmt->execute();

        // get retrieved row
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // set values to object properties
        $this->password = $row['password'];
    }

    // update the product
    function updateLogin()
    {

        // update query
        $query = "UPDATE
                " . $this->table_name . "
            SET
                auth_token = :auth_token
            WHERE
                username = :username AND
                password = :password";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->auth_token = htmlspecialchars(strip_tags($this->auth_token));
        $this->username = htmlspecialchars(strip_tags($this->username));
        $this->password = htmlspecialchars(strip_tags($this->password));

        // bind new values
        $stmt->bindParam(':auth_token', $this->auth_token);
        $stmt->bindParam(':username', $this->username);
        $stmt->bindParam(':password', $this->password);

        // execute the query
        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    // create product
    function create()
    {

        // query to insert record
        $query = "INSERT INTO
                " . $this->table_name . "
            SET
            username=:username, 
            password=:password, 
            firstName=:firstName, 
            lastName=:lastName, 
            email=:email, 
            photo=:photo";

        // prepare query
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->username = htmlspecialchars(strip_tags($this->username));
        $this->password = htmlspecialchars(strip_tags($this->password));
        $this->firstName = htmlspecialchars(strip_tags($this->firstName));
        $this->lastName = htmlspecialchars(strip_tags($this->lastName));
        $this->photo = htmlspecialchars(strip_tags($this->photo));
        $this->email = htmlspecialchars(strip_tags($this->email));

        // bind values
        $stmt->bindParam(":username", $this->username);
        $stmt->bindParam(":password", $this->password);
        $stmt->bindParam(":firstName", $this->firstName);
        $stmt->bindParam(":lastName", $this->lastName);
        $stmt->bindParam(":photo", $this->photo);
        $stmt->bindParam(":email", $this->email);

        // execute query
        if ($stmt->execute()) {
            return true;
        }

        return false;
    }
    
    function promoteRank1()
    {

        // update query
        $query = "UPDATE
                    " . $this->table_name . " AS U
                LEFT JOIN SORTIES_INSCRIPTION AS S ON S.users_id =:id
                SET
                    experience = '1'
                WHERE
                ( SELECT count(*) FROM sorties_inscription
                WHERE sorties_inscription.users_id=:id) BETWEEN 4 AND 9
                AND id=:id";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->id = htmlspecialchars(strip_tags($this->id));

        // bind new values
        $stmt->bindParam(":id", $this->id);

        // execute the query
        $stmt->execute();
        return $stmt;
    }

    
    function promoteRank2()
    {

        // update query
        $query = "UPDATE
                    " . $this->table_name . " AS U
                LEFT JOIN SORTIES_INSCRIPTION AS S ON S.users_id =:id
                SET
                    experience = '3'
                WHERE
                ( SELECT count(*) FROM sorties_inscription
                WHERE sorties_inscription.users_id=:id)  BETWEEN 9 AND 14";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->id = htmlspecialchars(strip_tags($this->id));

        // bind new values
        $stmt->bindParam(":id", $this->id);

        // execute the query
        $stmt->execute();
        return $stmt;
    }

    function promoteRank3()
    {

        // update query
        $query = "UPDATE
                    
                    " . $this->table_name . " AS U
                LEFT JOIN SORTIES_INSCRIPTION AS S ON S.users_id =:id
                SET
                    experience = '4'
                WHERE
                ( SELECT count(*) FROM sorties_inscription
                WHERE sorties_inscription.users_id =:id) BETWEEN 14 AND 19";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->id = htmlspecialchars(strip_tags($this->id));

        // bind new values
        $stmt->bindParam(":id", $this->id);

        // execute the query
        $stmt->execute();
        return $stmt;
    }


    function promoteRank4()
    {

        // update query
        $query = "UPDATE
                    
                    " . $this->table_name . " AS U
                LEFT JOIN SORTIES_INSCRIPTION AS S ON S.users_id =:id
                SET
                    experience = '5'
                WHERE
                ( SELECT count(*) FROM sorties_inscription
                WHERE sorties_inscription.users_id =:id)>20";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->id = htmlspecialchars(strip_tags($this->id));

        // bind new values
        $stmt->bindParam(":id", $this->id);

        // execute the query
        $stmt->execute();
        return $stmt;
    }


    function update()
    {

        // update query
        $query = "UPDATE
                    
                    " . $this->table_name . " AS U
                    LEFT JOIN SORTIE_INSCRIPTION S 
                    ON S.user_id=U.id
                SET 
                    experience = 'Coureur confirmé'
                WHERE
                    email=:email";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->password = htmlspecialchars(strip_tags($this->password));
        $this->firstName = htmlspecialchars(strip_tags($this->firstName));
        $this->lastName = htmlspecialchars(strip_tags($this->lastName));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->username = htmlspecialchars(strip_tags($this->username));
        $this->photo = htmlspecialchars(strip_tags($this->photo));


        // bind new values

        $stmt->bindParam(":password", $this->password);
        $stmt->bindParam(":firstName", $this->firstName);
        $stmt->bindParam(":lastName", $this->lastName);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":username", $this->username);
        $stmt->bindParam(":photo", $this->photo);



        // execute the query
        if ($stmt->execute()) {
            return true;
        }

        return false;
    }


    function getUser()
    {

        // query to read single record
        $query = "SELECT
                    *
                FROM
                    " . $this->table_name . " 
                WHERE
                    username = :username";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->username = htmlspecialchars(strip_tags($this->username));

        // bind values
        $stmt->bindParam(":username", $this->username);

        // execute query
        $stmt->execute();

        // get retrieved row
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // set values to object properties
        $this->username = $row['username'];
        $this->password = $row['password'];
        $this->firstName = $row['firstName'];
        $this->lastName = $row['lastName'];
        $this->email = $row['email'];
        $this->experience = $row['experience'];
        $this->photo = $row['photo'];
        $this->roles = $row['roles'];
    }
    function getUserByAuthToken()
    {

        // query to read single record
        $query = "SELECT
                    *
                FROM
                    " . $this->table_name . " 
                WHERE
                    auth_token = :auth_token";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->auth_token = htmlspecialchars(strip_tags($this->auth_token));

        // bind values
        $stmt->bindParam(":auth_token", $this->auth_token);

        // execute query
        $stmt->execute();

        // get retrieved row
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // set values to object properties
        $this->username = $row['username'];
        $this->firstName = $row['firstName'];
        $this->lastName = $row['lastName'];
        $this->email = $row['email'];
        $this->experience = $row['experience'];
        $this->photo = $row['photo'];
        $this->roles = $row['roles'];
    }

    function UpdateNbDechets()
    {

        // update query
        $query = "UPDATE
                    " . $this->table_name . "
                    AS U
                    LEFT JOIN SORTIES_INSCRIPTION AS I 
                    ON I.users_id=:id
                    SET 
                    quantiteDechets=SUM(I.quantiteDechetsRamasses)
                    WHERE
                    I.users_id=:id";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->id = htmlspecialchars(strip_tags($this->id));


        // bind new values

        $stmt->bindParam(":id", $this->id);


        // execute the query
        $stmt->execute();
        return $stmt;
    }
    function getlistPastUserSorties()
    {

        // update query
        $query = "SELECT S.id,S.title,S.description,S.runimage,S.walkimage,S.running_date FROM
                    " . $this->table_name . "
                    AS U
                    LEFT JOIN SORTIES_INSCRIPTION AS I
                    ON I.users_id=:id
                    LEFT JOIN SORTIES AS S
                    ON I.sorties_id=S.id
                    WHERE
                    I.users_id=:id
                    GROUP BY S.id
                    ";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->id = htmlspecialchars(strip_tags($this->id));


        // bind new values

        $stmt->bindParam(":id", $this->id);

        // execute query
        $stmt->execute();

        return $stmt;
    }
}
