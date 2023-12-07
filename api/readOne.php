<?php
// Check Request Method
if ($_SERVER['REQUEST_METHOD'] != 'GET') {
    header('Allow: GET');
    http_response_code(405);
    echo json_encode('Method Not Allowed');
    return;
}

// Response Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: *');

include_once '../db/Database.php';
include_once '../models/Bookmark.php';

// Instantiate a Database object & connect
$database = new Database();
$dbConnection = $database->connect();

// Instantiate bookmark object
$bookmark = new Bookmark($dbConnection);

// Get the HTTP GET request query parameter
if (!isset($_GET['id'])) {
    http_response_code(422);
    echo json_encode(
        array('message' => 'Error: Missing required query parameter id.')
    );
    return;
}

$bookmark->setId($_GET['id']);

// Read bookmark
if ($bookmark->readOne()) {
    $result =  array(
        'id' => $bookmark->getId(),
        'title' => $bookmark->getTitle(),
        'url' => $bookmark->getUrl(),
        'date_added' => $bookmark->getDateAdded()
    );
    echo json_encode($result);
} else {
    echo json_encode(
        array('message' => 'Error: No bookmark was found')
    );
}
