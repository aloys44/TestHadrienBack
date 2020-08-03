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
include_once '../objects/thread.php';


include_once '../helpers/check.php';

$database = new Database();
$db = $database->getConnection();

$thread = new Thread($db);
$check = new Check($db);

// get posted data
$data = json_decode(file_get_contents("php://input"));

// make sure data is not empty
if (
    !empty($data->author) &&
    !empty($data->subject) &&
    !empty($data->text)
) {

    // set product property values
    $thread->author = $data->author;
    $thread->subject = $data->subject;
    $thread->text = $data->text;

        if ($thread->create()) {

        // set response code - 201 created
        http_response_code(201);
  
        // tell the user
        echo json_encode(array("message" => "Thread bien cree."));
    }
  
    // if unable to create the product, tell the user
    else{
  
        // set response code - 503 service unavailable
        http_response_code(503);
  
        // tell the user
        echo json_encode(array("message" => "Impossible de créer le Thread."));
    }
}
  
// tell the user data is incomplete
else{
  
    // set response code - 400 bad request
    http_response_code(400);
  
    // tell the user
    echo json_encode(array("message" => "Impossible de créer l'utilisateur. Data is incomplete."));
}