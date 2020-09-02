<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');
  
// include database and object files
include_once '../config/database.php';
include_once '../objects/user.php';
include_once '../helpers/uuid_generator.php';
  

// get database connection
$database = new Database();
$db = $database->getConnection();

// prepare product object
$user = new User($db);

// get id of product to be edited
$data = json_decode(file_get_contents("php://input"));

$user->username = $data->username;

// read the details of product to be edited
$user->getUser();
  
if ($user->password != null) {

    if (password_verify($user->password, password_hash($user->password, PASSWORD_DEFAULT))) {
        $user->authToken = guidv4();

        if($user->updateLogin()){
  
            $user_arr = array(
                "id" =>  $user->id,
                "username" => $user->username,
                "firstName" => $user->firstName,
                "lastName" => $user->lastName,
                "email" => $user->email,
                "experience" => $user->experience,
                "photo" => $user->photo,
                "roles" => $user->roles,
                "authToken" => $user->authToken

            );
          
            // set response code - 200 OK
            http_response_code(200);
          
            // make it json format
            echo json_encode($user_arr);
        }
          
        // if unable to update the product, tell the user
        else {
          
            // set response code - 503 service unavailable
            http_response_code(503);
          
            // tell the user
            echo json_encode(array("message" => "Unable to login."));
        }
    }
    else {
          
        // set response code - 503 service unavailable
        http_response_code(503);
      
        // tell the user
        echo json_encode(array("message" => "password don't match."));
    }
}
else {
          
    // set response code - 503 service unavailable
    http_response_code(503);
  
    // tell the user
    echo json_encode(array("message" => "no user"));
}

?>