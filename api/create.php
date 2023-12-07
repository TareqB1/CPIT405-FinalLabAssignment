<?php
// Check Request Method
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    header('Allow: POST');
    http_response_code(405);
    echo json_encode('Method Not Allowed');
    return;
}
// Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: *');

include_once '../db/Database.php';
include_once '../models/Bookmark.php';

// Instantiate a Database object & connect
$database = new Database();
$dbConnection = $database->connect();

// Instantiate bookmark object
$bookmark = new Bookmark($dbConnection);

// Get the HTTP POST request JSON body
$data = json_decode(file_get_contents("php://input"), true);
if (!$data || !isset($data['url']) || !isset($data['title'])) {
    http_response_code(422);
    echo json_encode(
        array('message' => 'Error: Missing required parameter task in the JSON body.')
    );
    return;
}

$bookmark->setUrl($data['url']);
$bookmark->setTitle($data['title']);

// Create Bookmakr
if ($bookmark->create()) {
    echo json_encode(
        array('message' => 'A bookmakr was created')
    );
} else {
    echo json_encode(
        array('message' => 'Error: a bookmark was not created')
    );
}
