<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
  
// get database connection
include_once '../config/database.php';
  
// instantiate product object
include_once '../objects/user.php';
  
$database = new Database();
$db = $database->getConnection();
  
$user = new User($db);
  
// get posted data
$data = json_decode(file_get_contents("php://input"));
  
// make sure data is not empty
if(
    !empty($data->username) &&
    !empty($data->password) &&
    !empty($data->firstName) &&
    !empty($data->lastName) &&
    !empty($data->photo) &&
    !empty($data->email)
){
  
    // set product property values
    $user->username = $data->username;

    $user->password =  password_hash($data->password, PASSWORD_DEFAULT);

    $user->firstName = $data->firstName;
    $user->lastName = $data->lastName;
    $user->photo = $data->photo;
    $user->email = $data->email;
  
    // create the product
    if($user->create()) {
  
        // set response code - 201 created
        http_response_code(201);
  
        // tell the user
        echo json_encode(array("message" => "Utilisateur a été créé."));
    }
  
    // if unable to create the product, tell the user
    else{
  
        // set response code - 503 service unavailable
        http_response_code(503);
  
        // tell the user
        echo json_encode(array("message" => "Impossible de créer l'utilisateur."));
    }
}
  
// tell the user data is incomplete
else{
  
    // set response code - 400 bad request
    http_response_code(400);
  
    // tell the user
    echo json_encode(array("message" => "Impossible de créer l'utilisateur. Data is incomplete."));
}