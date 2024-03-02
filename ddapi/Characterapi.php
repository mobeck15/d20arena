<?php
require_once 'ApiProcessor.php';
require_once 'ClassesApi.php';
class CharacterApi extends ApiProcessor
{
    private $classData;

    protected function modifyApiNode($character)
    {
        $character = $this->addTreasure($character);
        $character = $this->levelAdjustment($character);
        $character = $this->advancement($character);
        $character = $this->classInfo($character);
        return $character;
    }

    private function classInfo($character)
    {
        //TODO: type is not a key in classdata it is just a list. need to get the class to return an indexed list.
        $character['classInfo'] = $this->classData[$character['type']];

        return $character;
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

        $CrInterval = $this->classData[$character['type']]['crperhd'];
        $CrInterval = 4;
        // Calculate CRIncrease and CRAdvancement
        $CRIncrease = floor($maxHD / $CrInterval);
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
}
