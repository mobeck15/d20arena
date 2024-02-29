<?php

class CharacterApi
{
    private $apiDataFile;
    private $rawApiData;
    private $decodedApiData;
    private $calculatedApiData;

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
        foreach ($this->decodedApiData as &$character) {
            $character = $this->addTreasure($character);
            $character = $this->levelAdjustment($character);
            $character = $this->advancement($character);
        }

        // Encode the modified data back to JSON
        $this->calculatedApiData = json_encode($this->decodedApiData);
        return $this->calculatedApiData;
    }

    private function advancement($character)
    {
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

        return $character;
    }

    private function levelAdjustment($character)
    {
        // Check if 'leveladjust' property is missing or empty
        if (!isset($character['leveladjust']) || empty($character['leveladjust'])) {
            // Set 'leveladjust' property to "—"
            $character['leveladjust'] = "—";
        }

        return $character;
    }

    private function addTreasure($character)
    {
        // Check if 'treasure' property is missing or empty
        if (!isset($character['treasure']) || empty($character['treasure'])) {
            // Set 'treasure' property to "None"
            $character['treasure'] = "None";
        }

        return $character;
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
