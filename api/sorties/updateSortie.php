<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
  
// include database and object files
include_once '../config/database.php';
include_once '../objects/sortie.php';
  
// get database connection
$database = new Database();
$db = $database->getConnection();
  
// prepare product object
$sortie = new Sortie($db);
  
// get id of product to be edited
$data = json_decode(file_get_contents("php://input"));
  
// set ID property of product to be edited
$sortie->id = $data->id;
  
// set product property values
$sortie->title = $data->title;
$sortie->description = $data->description;
$sortie->author = $data->author;
$sortie->nbMaxWalk_participants = $data->nbMaxWalk_participants;
$sortie->leaderWalk_participants = $data->leaderWalk_participants;
$sortie->nbMaxRun_participants = $data->nbMaxRun_participants;
$sortie->leaderRun_participants = $data->leaderRun_participants;
$sortie->nbTotal_participants = $data->nbTotal_participants;

// update the product
if($sortie->updateCourse()){
  
    // set response code - 200 ok
    http_response_code(200);
  
    // tell the user
    echo json_encode(array("message" => "Product was updated."));
}
  
// if unable to update the product, tell the user
else{
  
    // set response code - 503 service unavailable
    http_response_code(503);
  
    // tell the user
    echo json_encode(array("message" => "Unable to update product."));
}
?>