<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
  
// database connection will be here


// include database and object files
include_once '../config/database.php';
include_once '../objects/sortie.php';
include_once '../objects/sortie_inscription.php';
  
// instantiate database and product object
$database = new Database();
$db = $database->getConnection();
  
// initialize object
$sortie = new Sortie($db);
$sortieInscription = new SortieInscription($db);
  
// read products will be here
// query products
$sortie_with_participants = $sortie->getNextSortie();

if ($sortie_with_participants != null) {
    // extract row
    // this will make $row['name'] to
    // just $name only
    extract($sortie_with_participants);

    $sortieInscription->sorties_id = $id;
    $stmt = $sortieInscription->getInscriptionList();
    $num = $stmt->rowCount();
    
    // check if more than 0 record found
    if ($num > 0) {
    
        // products array
        $participants=array();
        $participants_arr["participantList"]=array();
    
        // retrieve our table contents
        // fetch() is faster than fetchAll()
        // http://stackoverflow.com/questions/2770630/pdofetchall-vs-pdofetch-in-a-loop
        while ($participant = $stmt->fetch(PDO::FETCH_ASSOC)){
            // extract row
            // this will make $row['name'] to
            // just $name only
            extract($participant);
    
            $participant_item=array(
                "username" => $username
            );
    
            array_push($participants_arr["participantList"], $participant_item);
        }
    }

    $sortie_with_participants=array(
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
        "status" => $status,
        "participants" => $participants_arr["participantList"]
    );
    
    // set response code - 200 OK
    http_response_code(200);
  
    // show products data in json format
    echo json_encode($sortie_with_participants);
} else {
  
    // set response code - 404 Not found
    http_response_code(404);
  
    // tell the user no products found
    echo json_encode(
        array("message" => "Le tri n'a pas fonctionne !")
    );
  
}