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
include_once '../objects/sortie_inscription.php';
include_once '../helpers/check.php';

// get database connection
$database = new Database();
$db = $database->getConnection();

// prepare product object
$sortie = new Sortie($db);
$sortie_inscription = new SortieInscription($db);
$check = new Check($db);

// get id of product to be edited
$data = json_decode(file_get_contents("php://input"));

// make sure data is not empty
if (
    !empty($data->id) &&
    !empty($data->auth_token)
) {
    // set product property values
    $userId = $check->check_auth_token($data->auth_token);

    if (is_numeric($userId)) {
        $sortie_inscription->users_id = $userId;
        $sortie_inscription->sorties_id = $data->id;

        if ($sortie_inscription->checkSubscribedStatus()) {
            if ($sortie_inscription->removeSuscribtion()) {
                http_response_code(200);
                echo json_encode(array("message" => "Subscription was cancelled."));
            } else {
                http_response_code(503);
                echo json_encode(array("message" => "Unable to delete suscribed status"));
            }
        } else {
            if ($sortie_inscription->create()) {
                http_response_code(200);
                echo json_encode(array("message" => "Subscription was created."));
            } else {
                http_response_code(503);
                echo json_encode(array("message" => "Unable to create suscribed status"));
            }
        }
    } else {
        http_response_code(503);
        echo json_encode(array("message" => "User not valid."));
    }
} else {
    http_response_code(503);
    echo json_encode(array("message" => "Unable to create sortie. Data is incomplete."));
}
