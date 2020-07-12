<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
  
// database connection will be here


// include database and object files
include_once '../config/database.php';
include_once '../objects/todo.php';
  
// instantiate database and product object
$database = new Database();
$db = $database->getConnection();
  
// initialize object
$todo = new Todo($db);
  
// read products will be here
// query products
$stmt = $todo->readTodosSortedByCategory4();
$num = $stmt->rowCount();
  
// check if more than 0 record found
if($num>0){
  
    // products array
    $products_arr=array();
    $products_arr["todoSortedByCategory4"]=array();
  
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
            "category" => $category,
            "like_Count" => $like_Count,
            "creation_date" => $creation_date,
            "anticipatedEnd_realisation" => $anticipatedEnd_realisation,
            "author" => $author,

        );
  
        array_push($products_arr["todoSortedByCategory4"], $product_item);
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
        array("message" => "Le tri n'a pas fonctionne !")
    );
  
}