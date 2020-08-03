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
include_once '../objects/message.php';


include_once '../helpers/check.php';

$database = new Database();
$db = $database->getConnection();

$message = new Message($db);
$check = new Check($db);

// get posted data
$data = json_decode(file_get_contents("php://input"));

// make sure data is not empty
if (
    !empty($data->text) &&
    !empty($data->subject) &&
    !empty($data->thread) &&
    !empty($data->author) 

) {

    // set product property values
    $message->text = $data->text;
    $message->subject = $data->subject;
    $message->thread = $data->thread;
    $message->author = $data->author;



        if ($message->create()) {

        // set response code - 201 created
        http_response_code(201);
  
        // tell the user
        echo json_encode(array("message" => "Message bien cree."));
    }
  
    // if unable to create the product, tell the user
    else{
  
        // set response code - 503 service unavailable
        http_response_code(503);
  
        // tell the user
        echo json_encode(array("message" => "Impossible de créer le Message."));
    }
}
  
// tell the user data is incomplete
else{
  
    // set response code - 400 bad request
    http_response_code(400);
  
    // tell the user
    echo json_encode(array("message" => "Impossible de créer l'utilisateur. Data is incomplete."));
}