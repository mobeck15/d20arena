<?php

require_once 'ApiHandler.php';

// Main entry point
if (isset($_GET['api'])) {
    $apiHandler = new ApiHandler();
    $apiName = $_GET['api'];
    $apiHandler->handleApiRequest($apiName);
} else {
    // If no API name is provided, return an error message
    http_response_code(400);
    echo json_encode(array('error' => 'API name not provided'));
}
