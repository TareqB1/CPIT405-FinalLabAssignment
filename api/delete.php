<?php
// Check Request Method
if ($_SERVER['REQUEST_METHOD'] != 'DELETE') {
    header('Allow: DELETE');
    http_response_code(405);
    echo json_encode('Method Not Allowed');
    return;
}

// Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: DELETE');
header('Access-Control-Allow-Headers: *');

include_once '../db/Database.php';
include_once '../models/Bookmark.php';

// Instantiate a Database object & connect
$database = new Database();
$dbConnection = $database->connect();

// Instantiate bookmark object
$bookmark = new Bookmark($dbConnection);

// Get the HTTP DELETE request JSON body
$data = json_decode(file_get_contents("php://input"));
if(!$data || !$data->id){
    http_response_code(422);
    echo json_encode(
        array('message' => 'Error: Missing required parameter id in the JSON body.')
    );
    return;
}

$bookmark->setId($data->id);

// Delete the ToDo item
if ($bookmark->delete()) {
    echo json_encode(
        array('message' => 'A bookmark was deleted.')
    );
} else {
    echo json_encode(
        array('message' => 'Error: a bookmark was not deleted.')
    );
}
