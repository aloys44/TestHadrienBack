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
include_once '../objects/evenement.php';
  
$database = new Database();
$db = $database->getConnection();
  
$evenement = new Evenement($db);
  
// get posted data
$data = json_decode(file_get_contents("php://input"));
  
// make sure data is not empty
if(
    
    !empty($data->title) &&
    !empty($data->description) &&
    !empty($data->author) &&
    !empty($data->occured_date)

){
  
    // set product property values
    $evenement->title = $data->title;
    $evenement->description = $data->description;
    $evenement->author = $data->author;
    $evenement->occured_date = $data->occured_date;


    // create the product
    if($evenement->create()) {
  
        // set response code - 201 created
        http_response_code(201);
  
        // tell the user
        echo json_encode(array("message" => "Un évènement vient d'être créée."));
    }
  
    // if unable to create the product, tell the user
    else{
  
        // set response code - 503 service unavailable
        http_response_code(503);
  
        // tell the user
        echo json_encode(array("message" => "Impossible de créér un évènement."));
    }
}
  
// tell the user data is incomplete
else{
  
    // set response code - 400 bad request
    http_response_code(400);
  
    // tell the user
    echo json_encode(array("message" => "Impossible de créér un évènement.. Data is incomplete."));
}