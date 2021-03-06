<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
  
// database connection will be here

// include database and object files
include_once '../config/database.php';
include_once '../objects/User.php';
include_once '../objects/sortie_inscription.php';
include_once '../helpers/check.php';

  
// instantiate database and product object
$database = new Database();
$db = $database->getConnection();
  
// prepare product object
$user = new User($db);
$sortie_inscription = new SortieInscription($db);
$check = new Check($db);

  
$data = json_decode(file_get_contents("php://input"));

// make sure data is not empty
    $user->authToken = $data->authToken;
// read products will be here
// query products
$userId = $check->check_authToken($data->authToken);
$user->id = $userId;


$stmt = $user->getlistPastUserSorties();
$num = $stmt->rowCount();
  
// check if more than 0 record found
if ($num > 0) {
  
    // products array
    $products_arr=array();
    $products_arr["ListPastUserSorties"]=array();
  
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
            "runimage" => $runimage,
            "walkimage" => $walkimage,
            "running_date" => $running_date,
        );
  
        array_push($products_arr["ListPastUserSorties"], $product_item);
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
        array("message" => "Aucune sortie trouvée.")
    );
}