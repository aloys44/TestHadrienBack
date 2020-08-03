<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
  
// database connection will be here

// include database and object files
include_once '../config/database.php';
include_once '../objects/sortie.php';

  
// instantiate database and product object
$database = new Database();
$db = $database->getConnection();
  
// initialize object
$sortie = new Sortie($db);


// get id of product to be edited
$data = json_decode(file_get_contents("php://input"));

$sortie->id = $data->id;

// make sure data is not empty
// update the product
if($sortie->delete()){
  

    $user_arr = array(
        "id" => $id,
            "title" => $title,
            "description" => $description,
            "runimage" => $runimage,
            "walkimage" => $walkimage,
            "nbMaxWalk_participants" => $nbMaxWalk_participants,
            "nbMaxRun_participants" => $nbMaxRun_participants,
            "creation_date" => $creation_date,
            "running_date" => $running_date,
            "author" => $author,
            "status" => $status
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