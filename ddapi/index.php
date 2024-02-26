<?php
// Define your API keys (replace these with your actual API keys)
$validApiKeys = array(
    'your_api_key1',
    'your_api_key2',
    // Add more API keys as needed
);

// Function to handle API endpoints
function handleApiRequest($apiName)
{
    // Path to your JSON data files
    $jsonFiles = array(
        'characters' => '../data/characters.json',
        'classes' => '../data/classes.json',
        'sizedmg' => '../data/damagesize.json',
        'sizes' => '../data/sizes.json',
        // Add more API endpoints and their corresponding JSON files here
    );

    // Check if the API key is provided in the request
    if (!isset($_GET['api_key'])) {
        // If no API key is provided, return an error message
        http_response_code(401);
        echo json_encode(array('error' => 'API key is required'));
        return;
    }

    // Validate the API key
    $apiKey = $_GET['api_key'];
    if (!in_array($apiKey, $GLOBALS['validApiKeys'])) {
        // If the provided API key is not valid, return an error message
        http_response_code(401);
        echo json_encode(array('error' => 'Invalid API key'));
        return;
    }

    // Check if the requested API name exists
    if (array_key_exists($apiName, $jsonFiles)) {
        $jsonFile = $jsonFiles[$apiName];

        // Check if the file exists
        if (file_exists($jsonFile)) {
            // Read the contents of the JSON file
            $jsonData = file_get_contents($jsonFile);

            switch ($apiName) {
                case 'characters':
                    $jsonData = processCharacterData($jsonData);
                    break;
            }

            // Set the appropriate CORS headers
            header('Access-Control-Allow-Origin: *');
            header('Access-Control-Allow-Methods: GET');
            header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');

            // Set the appropriate HTTP header
            header('Content-Type: application/json');

            // Output the JSON data
            echo $jsonData;
        } else {
            // If the file doesn't exist, return an error message
            http_response_code(404);
            echo json_encode(array('error' => 'File not found'));
        }
    } else {
        // If the requested API name doesn't exist, return an error message
        http_response_code(404);
        echo json_encode(array('error' => 'API not found'));
    }
}

function processCharacterData($jsonData)
{
    // Decode the JSON data into a PHP associative array
    $data = json_decode($jsonData, true);

    // Modify the data (e.g., add or remove elements, update values)
    foreach ($data as &$character) {
        // Check if 'treasure' property is missing or empty
        if (!isset($character['treasure']) || empty($character['treasure'])) {
            // Set 'treasure' property to "None"
            $character['treasure'] = "None";
        }

        // Check if 'leveladjust' property is missing or empty
        if (!isset($character['leveladjust']) || empty($character['leveladjust'])) {
            // Set 'leveladjust' property to "—"
            $character['leveladjust'] = "—";
        }

        // Set default value for maxHD to current CR value
        $maxHD = $character['cr'];

        // Check if 'advancement' property exists and is an array with elements
        if (isset($character['advancement']) && is_array($character['advancement']) && count($character['advancement']) > 0) {
            // Get the maximum highhd value from the 'advancement' array
            $maxHD = max(array_column($character['advancement'], 'highhd'));
        }

        // Calculate CRIncrease and CRAdvancement
        $CRIncrease = floor($maxHD / 4);
        $CRAdvancement = $character['cr'] + $CRIncrease;

        // Add 'CRAdvancement' property to the character
        $character['CRAdvancement'] = $CRAdvancement;
    }

    // Encode the modified data back to JSON
    return json_encode($data);
}

// Main entry point
if (isset($_GET['api'])) {
    $apiName = $_GET['api'];
    handleApiRequest($apiName);
} else {
    // If no API name is provided, return an error message
    http_response_code(400);
    echo json_encode(array('error' => 'API name not provided'));
}
