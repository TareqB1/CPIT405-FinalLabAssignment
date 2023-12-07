<?php
// Check Request Method
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] != 'PUT') {
    header('Allow: PUT');
    http_response_code(405);
    echo json_encode('Method Not Allowed');
    return;
}


// Response Headers
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Content-Type');
header('Access-Control-Allow-Methods: PUT, OPTIONS');


include_once '../db/Database.php';
include_once '../models/Bookmark.php';

// Instantiate a Database object & connect
$database = new Database();
$dbConnection = $database->connect();

// Instantiate bookmark object
$bookmark = new Bookmark($dbConnection);

// Get the HTTP PUT request JSON body
$data = json_decode(file_get_contents("php://input"));
if (!$data || !isset($data->id) || !isset($data->title) || !isset($data->url)) {
    http_response_code(422);
    echo json_encode(
        array('message' => 'Error: Missing required parameters id and done in the JSON body.')
    );
    return;
}

$bookmark->setId($data->id);
$bookmark->setTitle($data->title);
$bookmark->setUrl($data->url);

// Update the bookmark item
if ($bookmark->update()) {
    echo json_encode(
        array('message' => 'A bookmark item was updated.')
    );
} else {
    echo json_encode(
        array('message' => 'Error: a bookmark item was not updated.')
    );
}
