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

    public function processApiData($key = null)
    {
        if ($this->rawApiData == null) {
            return null;
        }

        // Decode the JSON data into a PHP associative array
        $this->decodedApiData = json_decode($this->rawApiData, true);

        $newData = null;
        $keyCounts = [];
        // Modify the data (e.g., add or remove elements, update values)
        foreach ($this->decodedApiData as $node) {
            $node = $this->modifyApiNode($node);

            if ($key != null) {
                $useKey = $node[$key];

                // Check if the key already exists in the counts array
                if (isset($keyCounts[$useKey])) {
                    // Increment the count for this key
                    $keyCounts[$useKey]++;

                    // Append the count to the key
                    $useKey = $useKey . '(' . $keyCounts[$useKey] . ')';
                } else {
                    $keyCounts[$useKey] = 1;
                }

                $newData[$useKey] = $node;
            } else {
                $newData[] = $node;
            }
        }
        $this->decodedApiData = $newData;
        return $newData;
    }

    protected function modifyApiNode($node)
    {
        //Override this method to modify the data before returning it in the API.
        return $node;
    }

    protected function renameKey($node, $oldKey, $newKey)
    {
        // Check if the old key exists in the array
        if (!array_key_exists($oldKey, $node)) {
            return $node; // Return the original array if the old key doesn't exist
        }

        // Create a new array to store the updated elements
        $newNode = [];

        // Iterate through the original array
        foreach ($node as $key => $value) {
            // If it's the old key, replace it with the new key
            if ($key === $oldKey) {
                $newNode[$newKey] = $value;
            } else {
                // Otherwise, keep the original key-value pair
                $newNode[$key] = $value;
            }
        }

        return $newNode;
    }


    public function getApiData($key = null)
    {
        $this->loadApi();

        // Encode the modified data back to JSON
        $this->calculatedApiData = json_encode($this->processApiData($key), JSON_PRETTY_PRINT);
        return $this->calculatedApiData;
    }

    public function getData($key = null)
    {
        $this->loadApi();
        return $this->processApiData($key);
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
