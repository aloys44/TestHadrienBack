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
include_once '../objects/sortie.php';
  
$database = new Database();
$db = $database->getConnection();
  
$sortie = new Sortie($db);
  
// get posted data
$data = json_decode(file_get_contents("php://input"));
  
// make sure data is not empty
if(
    !empty($data->title) &&
    !empty($data->description) &&
    !empty($data->author) &&
    !empty($data->nbMaxWalk_participants) &&
    !empty($data->leaderWalk_participants) &&
    !empty($data->nbMaxRun_participants) &&
    !empty($data->leaderRun_participants) &&
    !empty($data->nbTotal_participants) 


){
  
    // set product property values
    $sortie->title = $data->title;
    $sortie->description = $data->description;
    $sortie->author = $data->author;
    $sortie->nbMaxWalk_participants = $data->nbMaxWalk_participants;
    $sortie->leaderWalk_participants = $data->leaderWalk_participants;
    $sortie->nbMaxRun_participants = $data->nbMaxRun_participants;
    $sortie->leaderRun_participants = $data->leaderRun_participants;
    $sortie->nbTotal_participants = $data->nbTotal_participants;


  
    // create the product
    if($sortie->create()) {
  
        // set response code - 201 created
        http_response_code(201);
  
        // tell the user
        echo json_encode(array("message" => "Une sortie vient d'être créée."));
    }
  
    // if unable to create the product, tell the user
    else{
  
        // set response code - 503 service unavailable
        http_response_code(503);
  
        // tell the user
        echo json_encode(array("message" => "Impossible de créér une sortie."));
    }
}
  
// tell the user data is incomplete
else{
  
    // set response code - 400 bad request
    http_response_code(400);
  
    // tell the user
    echo json_encode(array("message" => "Impossible de créér une sortie. Data is incomplete."));
}