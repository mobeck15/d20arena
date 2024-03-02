<?php
class ApiProcessor
{
    protected $apiDataFile;
    protected $rawApiData;
    protected $decodedApiData;
    protected $calculatedApiData;

    public function __construct($apiDataFile)
    {
        $this->apiDataFile = $apiDataFile;
    }

    public function processApiData()
    {
        if ($this->rawApiData == null) {
            return null;
        }

        // Decode the JSON data into a PHP associative array
        $this->decodedApiData = json_decode($this->rawApiData, true);

        // Modify the data (e.g., add or remove elements, update values)
        foreach ($this->decodedApiData as &$node) {
            $node = $this->modifyApiNode($node);
        }

        // Encode the modified data back to JSON
        $this->calculatedApiData = json_encode($this->decodedApiData);
        return $this->calculatedApiData;
    }

    protected function modifyApiNode($node)
    {
        //Override this method to modify the data before returning it in the API.
        return $node;
    }

    public function getApiData()
    {
        $this->loadApi();
        return $this->processApiData();
    }

    public function loadApi()
    {
        // Load characters from JSON file
        if (file_exists($this->apiDataFile)) {
            $this->rawApiData = file_get_contents($this->apiDataFile);
        } else {
            $this->rawApiData = null;
        }
        return $this->rawApiData;
    }
}
