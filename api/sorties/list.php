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
  
// read products will be here
// query products
$stmt = $sortie->getList();
$num = $stmt->rowCount();
  
// check if more than 0 record found
if ($num > 0) {
  
    // products array
    $products_arr=array();
    $products_arr["sortieList"]=array();
  
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
            "nbMaxWalk_participants" => $nbMaxWalk_participants,
            "nbMaxRun_participants" => $nbMaxRun_participants,
            "creation_date" => $creation_date,
            "running_date" => $running_date,
            "author" => $author,
        );
  
        array_push($products_arr["sortieList"], $product_item);
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