<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
  
// database connection will be here

// include database and object files
include_once '../config/database.php';
include_once '../objects/thread.php';
  
// instantiate database and product object
$database = new Database();
$db = $database->getConnection();
  
// initialize object
$thread = new Thread($db);
 
$data = json_decode(file_get_contents("php://input"));

// read products will be here

if (
    !empty($data->id)
) {
// query products

    $id = $data->id;
    $thread->id=$id;
    
    $stmt = $thread->getCurrentThread();
    $num = $stmt->rowCount();
  
// check if more than 0 record found
if ($num > 0) {
  
    // products array
    $products_arr=array();
    $products_arr["threadList"]=array();
  
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
            "subject" => $subject,
            "author" => $author,
            "creation_date" => $creation_date,
            "text" => $text
        );
  
        array_push($products_arr["threadList"], $product_item);
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
        array("message" => "No Thread found.")
    );
}
} else {
    
    http_response_code(503);
    echo json_encode(array("message" => "Impossible d'avoir liste threads. Data is incomplete."));
}