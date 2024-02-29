<?php
require_once 'Characterapi.php';
class ApiHandler
{
    private $validApiKeys;
    private $jsonFiles;

    public function __construct()
    {
        // Define your API keys (replace these with your actual API keys)
        $this->validApiKeys = array(
            'your_api_key1',
            'your_api_key2',
            // Add more API keys as needed
        );

        // Path to your JSON data files
        $this->jsonFiles = array(
            'characters' => '../data/characters.json',
            'classes' => '../data/classes.json',
            'sizedmg' => '../data/damagesize.json',
            'sizes' => '../data/sizes.json',
            // Add more API endpoints and their corresponding JSON files here
        );
    }

    public function handleApiRequest($apiName)
    {
        // Check if the API key is provided in the request
        if (!isset($_GET['api_key'])) {
            $this->sendResponse(401, array('error' => 'API key is required'));
            return;
        }

        // Validate the API key
        $apiKey = $_GET['api_key'];
        if (!in_array($apiKey, $this->validApiKeys)) {
            $this->sendResponse(401, array('error' => 'Invalid API key'));
            return;
        }

        $jsonData = $this->getApiData($apiName);

        if ($jsonData == null) {
            $this->sendResponse(404, array('error' => 'API not found'));
            return;
        }

        // Set the headers
        $this->setHeaders();

        // Output the JSON data
        echo $jsonData;
    }

    private function getApiData($apiName)
    {
        $jsonData = null;

        // Check if the requested API name exists
        if (array_key_exists($apiName, $this->jsonFiles)) {
            $jsonFile = $this->jsonFiles[$apiName];

            // Check if the file exists
            if (file_exists($jsonFile)) {
                switch ($apiName) {
                    case 'characters':
                        $characterApi = new CharacterApi($this->jsonFiles['characters']);
                        $jsonData = $characterApi->getApiData();
                        break;
                    default:
                        // Read the contents of the JSON file
                        $jsonData = file_get_contents($jsonFile);
                        break;
                }
            }
        }
        return $jsonData;
    }

    private function setHeaders()
    {
        // Set the appropriate CORS headers
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET');
        header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');

        // Set the appropriate HTTP header
        header('Content-Type: application/json');
    }

    private function sendResponse($statusCode, $data)
    {
        // Set the appropriate HTTP response code
        http_response_code($statusCode);

        // Set the appropriate HTTP header
        header('Content-Type: application/json');

        // Output the JSON data
        echo json_encode($data);
    }
}
