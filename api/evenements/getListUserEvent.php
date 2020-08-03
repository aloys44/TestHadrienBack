<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
  
// database connection will be here

// include database and object files
include_once '../config/database.php';
include_once '../objects/evenement.php';
include_once '../objects/evenement_seen.php';
include_once '../helpers/check.php';
  

// instantiate database and product object
$database = new Database();
$db = $database->getConnection();
  
// initialize object
$evenement = new Evenement($db);
$evenement_seen = new Evenement_Seen($db);
$check = new Check($db);

  
$data = json_decode(file_get_contents("php://input"));

// make sure data is not empty
if (
    !empty($data->auth_token)
) {
    // set product property values
    $userId = $check->check_auth_token($data->auth_token);
    $evenement->user_id = $userId;



    // read products will be here
    // query products
    $stmt = $evenement->getListUserEvent();
    $num = $stmt->rowCount();
    
    // check if more than 0 record found
    if ($num > 0) {
    
        // products array
        $products_arr=array();
        $products_arr["evenementUserList"]=array();
    
        // retrieve our table contents
        // fetch() is faster than fetchAll()
        // http://stackoverflow.com/questions/2770630/pdofetchall-vs-pdofetch-in-a-loop
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            // extract row
            // this will make $row['name'] to
            // just $name only
            extract($row);
    
            $product_item=array(
                "id" => $id,
                "title" => $title,
                "description" => $description,
                "author" => $author,
                "creation_date" => $creation_date,
                "occured_date" => $occured_date,
                "status" => $status,
            );
    
            array_push($products_arr["evenementUserList"], $product_item);
        }
    
                // set response code - 200 OK
                http_response_code(200);
            
                // show products data in json format
                echo json_encode($products_arr);
            } else {
            
                // set response code - 404 Not found
                http_response_code(404);
            
                // tell the user no products found
                echo json_encode(
                    array("message" => "Aucun évènement trouvé.")
                );
            }
} else {
    
    http_response_code(503);
    echo json_encode(array("message" => "Impossible d'avoir liste évènements. Data is incomplete."));
}