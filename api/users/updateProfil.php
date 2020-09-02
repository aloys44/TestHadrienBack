<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
  
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
  
// set ID property of product to be edited
$user->username = $data->username;

// set product property values
$user->getUser();

$user->password =  password_hash($data->password, PASSWORD_DEFAULT);
$user->firstName = $data->firstName;
$user->lastName = $data->lastName;
$user->username = $data->username;
$user->email = $data->email;
$user->photo = $data->photo;


// update the product
if($user->update()){
  

                $user_arr = array(
                    "password" => $user->password,
                    "username" => $user->username,
                    "firstName" => $user->firstName,
                    "lastName" => $user->lastName,
                    "email" => $user->email,
                    "photo" => $user->photo,
                    "authToken" => $user->authToken,
                );
                // set response code - 200 ok
                http_response_code(200);
            
                // tell the user
                echo json_encode($user_arr);


        }
  
// if unable to update the product, tell the user
else{
  
    // set response code - 503 service unavailable
    http_response_code(503);
  
    // tell the user
    echo json_encode(array("message" => "Unable to update product."));

}
?>