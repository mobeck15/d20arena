<?php
require_once 'ApiProcessor.php';
require_once 'ClassesApi.php';
require_once 'utils.php';
class CharacterApi extends ApiProcessor
{
    private $classData;

    public function __construct($apiDataFile)
    {
        parent::__construct($apiDataFile);
        $classApi = new ClassesApi("../data/classes.json");
        $this->classData =  $classApi->getData("class");
    }

    protected function modifyApiNode($character)
    {
        $this->addTreasure($character)
            ->levelAdjustment($character)
            ->advancement($character)
            ->classInfo($character)
            ->stats($character)
            ->hitPoints($character)
            ->movement($character);

        return $character;
    }

    private function movement(&$character)
    {
        $movement = '';

        foreach ($character['movement'] as $key => $value) {
            // Extract speed and agility from each movement type
            $speed = $value['speed'];
            $agility = isset($value['agility']) ? $value['agility'] : '';

            // Append speed and agility to the movement text
            $movement .= $speed . " ft. (" . ($value['speed'] / 5) . " squares)";

            // Add agility in parentheses if available
            if (!empty($agility)) {
                $movement .= " ({$agility})";
            }

            // Add comma and space if it's not the last movement type
            if ($key !== array_key_last($character['movement'])) {
                $movement .= ', ';
            }
        }
        $character['movement']['text'] = $movement;
        return $this;
    }

    private function classInfo(&$character)
    {
        //TODO: type is not a key in classdata it is just a list. need to get the class to return an indexed list.
        $character['classInfo'] = $this->classData[$character['type']];

        return $this;
    }

    private function advancement(&$character)
    {
        // Set default value for maxHD to current CR value
        $maxHD = $character['cr'];

        // Check if 'advancement' property exists and is an array with elements
        if (isset($character['advancement']) && is_array($character['advancement']) && count($character['advancement']) > 0) {
            // Get the maximum highhd value from the 'advancement' array
            $maxHD = max(array_column($character['advancement'], 'highhd'));
        }

        $CrInterval = $this->classData[$character['type']]['crperhd'];
        // Calculate CRIncrease and CRAdvancement
        $CRIncrease = floor($maxHD / $CrInterval);
        $CRAdvancement = $character['cr'] + $CRIncrease;

        // Add 'CRAdvancement' property to the character
        $character['CRAdvancement'] = $CRAdvancement;

        return $this;
    }

    private function levelAdjustment(&$character)
    {
        // Check if 'leveladjust' property is missing or empty
        if (!isset($character['leveladjust']) || empty($character['leveladjust'])) {
            // Set 'leveladjust' property to "—"
            $character['leveladjust'] = "—";
        }

        return $this;
    }

    private function addTreasure(&$character)
    {
        // Check if 'treasure' property is missing or empty
        if (!isset($character['treasure']) || empty($character['treasure'])) {
            // Set 'treasure' property to "None"
            $character['treasure'] = "None";
        }

        return $this;
    }

    private function stats(&$character)
    {
        $stats = $character['stats'];
        foreach ($stats as $key => $value) {
            $stats[$key] = array(
                "score" => $value,
                "mod" => Utility::abilityModifier($value)
            );
        }
        $character['stats'] = $stats;
        return $this;
    }

    private function hitPoints(&$character)
    {
        // Rename 'hd' key to 'hp'
        $character = $this->renameKey($character, 'hd', 'hp');

        $hd = $character['hp'];
        $conMod = $character['stats']['con']['mod'];
        $hdSize = $this->classData[$character['type']]['hdsize'];

        $hpBonus = $hd * $conMod;
        $averageHp = ($hd * ($hdSize + 1) / 2) + ($hd * $conMod);

        // Build new 'hp' array
        $hp = array(
            'hd' => $hd,
            'hdSize' => $hdSize,
            'hpBonus' => $hpBonus,
            'averageHp' => $averageHp,
            'text' => "{$hd}d{$hdSize}+{$hpBonus} ({$averageHp} hp)"
        );

        // Replace 'hp' key in $character array
        $character['hp'] = $hp;

        return $this;
    }
}
