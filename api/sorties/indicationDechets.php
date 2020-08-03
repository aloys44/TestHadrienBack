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
  
// set ID property of product to be edited
$userId = $check->check_auth_token($data->auth_token);

$sortie_inscription->sorties_id = $data->sorties_id;
$sortie_inscription->id = $userId;
$sortie_inscription->quantiteDechetsRamasses = $data->quantiteDechetsRamasses;



// update the product
if($sortie_inscription->IndicationDechets()){
  

                $user_arr = array(
                    "sorties_id" => $sortie_inscription->sorties_id,
                    "id" => $sortie_inscription->id,
                    "quantiteDechetsRamasses" => $sortie_inscription->quantiteDechetsRamasses,
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