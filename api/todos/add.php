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
include_once '../objects/todo.php';
  
$database = new Database();
$db = $database->getConnection();
  
$todo = new Todo($db);
  
// get posted data
$data = json_decode(file_get_contents("php://input"));
  
// make sure data is not empty
if(
    !empty($data->title) &&
    !empty($data->description) &&
    !empty($data->author) &&
    !empty($data->category)

){
  
    // set product property values
    $todo->title = $data->title;
    $todo->description = $data->description;
    $todo->author = $data->author;
    $todo->category = $data->category;
  
    // create the product
    if($todo->create()) {
  
        // set response code - 201 created
        http_response_code(201);
  
        // tell the user
        echo json_encode(array("message" => "Todo bien cree."));
    }
  
    // if unable to create the product, tell the user
    else{
  
        // set response code - 503 service unavailable
        http_response_code(503);
  
        // tell the user
        echo json_encode(array("message" => "Impossible de créer le todo."));
    }
}
  
// tell the user data is incomplete
else{
  
    // set response code - 400 bad request
    http_response_code(400);
  
    // tell the user
    echo json_encode(array("message" => "Impossible de créer l'utilisateur. Data is incomplete."));
}