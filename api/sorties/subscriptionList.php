<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
  
// database connection will be here

// include database and object files
include_once '../config/database.php';
include_once '../objects/sortie.php';
include_once '../objects/subscription_sortie.php';

  
// instantiate database and product object
$database = new Database();
$db = $database->getConnection();
  
// initialize object
$sorties_inscription = new Subscription_sortie($db);

// get id of product to be edited
$data = json_decode(file_get_contents("php://input"));

// make sure data is not empty
if (
    !empty($data->sorties_id)
) {
    // set product property values
    $sorties_inscription->sorties_id = $data->sorties_id;

    // read products will be here
    // query products
    $stmt = $sorties_inscription->getInscriptionList();
    $num = $stmt->rowCount();
    
    // check if more than 0 record found
    if ($num > 0) {
    
        // products array
        $products_arr=array();
        $products_arr["subscriptionList"]=array();
    
        // retrieve our table contents
        // fetch() is faster than fetchAll()
        // http://stackoverflow.com/questions/2770630/pdofetchall-vs-pdofetch-in-a-loop
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            // extract row
            // this will make $row['name'] to
            // just $name only
            extract($row);
    
            $product_item=array(
                "username" => $username,
            );
    
            array_push($products_arr["subscriptionList"], $product_item);
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
            array("message" => "Aucun utilisateur inscrit trouvée.")
        );
    }
} else {
    
    http_response_code(503);
    echo json_encode(array("message" => "Unable to get inscription list. Data is incomplete."));
}