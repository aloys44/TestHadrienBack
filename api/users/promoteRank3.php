<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
  
// database connection will be here

// include database and object files
include_once '../config/database.php';
include_once '../objects/user.php';
include_once '../helpers/check.php';
  

// instantiate database and product object
$database = new Database();
$db = $database->getConnection();
  
// initialize object
$user = new User($db);
$check = new Check($db);

  
$data = json_decode(file_get_contents("php://input"));

// make sure data is not empty
    $user->auth_token = $data->auth_token;

    // set product property values
    $userId = $check->check_auth_token($data->auth_token);
    $user->id = $userId;

    // read products will be here
    // query products
    
    // check if more than 0 record found
    if($user->promoteRank3()){
  

        $user_arr = array(
            "password" => $user->password,
            "username" => $user->username,
            "firstName" => $user->firstName,
            "lastName" => $user->lastName,
            "email" => $user->email,
            "photo" => $user->photo,
            "auth_token" => $user->auth_token,
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