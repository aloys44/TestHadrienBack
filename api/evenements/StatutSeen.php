<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// include database and object files
include_once '../config/database.php';
include_once '../objects/evenement.php';
include_once '../objects/evenement_seen.php';
include_once '../helpers/check.php';

// get database connection
$database = new Database();
$db = $database->getConnection();

// prepare product object
$evenement = new Evenement($db);
$evenement_seen = new Evenement_Seen($db);
$check = new Check($db);


// get id of product to be edited
$data = json_decode(file_get_contents("php://input"));

// make sure data is not empty
if (
    !empty($data->id) &&
    !empty($data->authToken)
) {
    // set product property values
    $evenement->id = $data->id;
    $userId = $check->check_authToken($data->authToken);

    if (is_numeric($userId)) {
        $evenement_seen->user_id = $userId;
        $evenement_seen->evenement_id = $evenement->id;

        if ($evenement_seen->checkSeenStatus()) {
            if ($evenement_seen->updateSeen()) {
                http_response_code(200);
                echo json_encode(array("message" => "Product was updated."));
            } else {
                http_response_code(503);
                echo json_encode(array("message" => "Unable to update seen status"));
            }
        } else {
            if ($evenement_seen->create()) {
                http_response_code(200);
                echo json_encode(array("message" => "Product was created."));
            } else {
                http_response_code(503);
                echo json_encode(array("message" => "Unable to create seen status"));
            }
        }
    } else {
        http_response_code(503);
        echo json_encode(array("message" => "User not valid."));
    }
} else {
    http_response_code(503);
    echo json_encode(array("message" => "Unable to create evenement. Data is incomplete."));
}
