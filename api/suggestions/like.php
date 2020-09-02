<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// include database and object files
include_once '../config/database.php';
include_once '../objects/suggestion.php';
include_once '../objects/suggestion_like_count.php';
include_once '../helpers/check.php';

// get database connection
$database = new Database();
$db = $database->getConnection();

// prepare product object
$suggestion = new Suggestion($db);
$suggestionLikeCount = new SuggestionLikeCount($db);
$check = new Check($db);

// get id of product to be edited
$data = json_decode(file_get_contents("php://input"));

// make sure data is not empty
if (
    !empty($data->id) &&
    isset($data->is_liked) &&
    !empty($data->authToken)
) {
    // set product property values
    $suggestion->id = $data->id;
    $userId = $check->check_authToken($data->authToken);

    if (is_numeric($userId)) {
        $suggestionLikeCount->author_id = $userId;
        $suggestionLikeCount->suggestion_id = $suggestion->id;
        $suggestionLikeCount->is_liked = $data->is_liked;

        if ($suggestionLikeCount->checkLikedStatus()) {
            if ($suggestionLikeCount->updateLike()) {
                http_response_code(200);
                echo json_encode(array("message" => "Product was updated."));
            } else {
                http_response_code(503);
                echo json_encode(array("message" => "Unable to update like status"));
            }
        } else {
            if ($suggestionLikeCount->create()) {
                http_response_code(200);
                echo json_encode(array("message" => "Product was created."));
            } else {
                http_response_code(503);
                echo json_encode(array("message" => "Unable to create like status"));
            }
        }
    } else {
        http_response_code(503);
        echo json_encode(array("message" => "User not valid."));
    }
} else {
    http_response_code(503);
    echo json_encode(array("message" => "Unable to create suggestion. Data is incomplete."));
}
