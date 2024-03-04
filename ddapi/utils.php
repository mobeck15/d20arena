<?php
class Utility
{
    public static function abilityModifier($stat)
    {
        return floor($stat / 2) - 5;
    }

    public static function textToArray($text, $json = true)
    {
        $lines = explode(PHP_EOL, $text);
        $parsedData = [];

        foreach ($lines as $line) {
            $parts = explode(':', $line, 2);
            if (count($parts) === 2) {
                $key = strtolower(trim($parts[0]));
                $value = trim($parts[1]);
                $parsedData[$key] = $value;
            }
        }

        if ($json)
            return json_encode($parsedData, JSON_PRETTY_PRINT);
        else
            return $parsedData;
    }

    public static function parseStatBlock($text, $json = true)
    {
        // Extract lines from text
        $lines = Utility::textToArray($text, false);

        // Extract hit dice
        $pattern = '/^(\d+)d/';
        $hd = "";
        if (isset($lines["hit dice"]) && preg_match($pattern, $lines["hit dice"], $matches)) {
            $hd = $matches[1];
        }

        // Extract size and type
        $sizetype = explode(" ", $lines["size/type"]);
        $size = $sizetype[0];
        $type = implode(" ", array_slice($sizetype, 1));

        // Extract speed and swim speed
        $speed = $swimSpeed = 0;
        if (preg_match('/(\d+)\s*ft\./', $lines["speed"], $matches)) {
            $speed = (int)$matches[1];
        }

        //TODO: this is not working yet! moovement array is not properly captureing all potential movement types.
        $movement = [];
        if (preg_match_all('/(\d+)\s*ft\.(\s*(?:\(\d+\s+squares\))?)?/', $lines["speed"], $matches, PREG_SET_ORDER)) {
            foreach ($matches as $match) {
                $value = (int)$match[1]; // Extract speed value
                if (isset($match[2]) && preg_match('/\((\d+)\s+squares\)/', $match[2], $squaresMatch)) {
                    // If squares information is available, use it as speed type
                    $type = 'speed';
                    $value = (int)$squaresMatch[1];
                } else {
                    // Otherwise, default to 'speed'
                    $type = 'speed';
                }
                $movement[$type] = $value; // Add speed value to movement array with the appropriate type
            }
        }

        // Construct stat block array
        $statBlock = [
            "name" => trim(explode("\n", $text)[0]),
            "size" => $size,
            "type" => $type,
            "hd" => $hd,
            "initiative" => intval($lines["initiative"]),
            "speed" => $speed,
            "movement" => $movement,
            "ac" => "",
            "ac_modifiers" => [],
            "baseattack" => "",
            "grapple" => "",
            "actions" => [],
            "space" => "",
            "reach" => "",
            "specialAttacks" => [],
            "qualities" => [],
            "saves" => ["fort" => "", "ref" => "", "will" => ""],
            "stats" => ["str" => "", "dex" => "", "con" => "", "int" => "", "wis" => "", "cha" => ""],
            "skills" => [],
            "feats" => [],
            "environment" => "",
            "organization" => [],
            "cr" => "",
            "treasure" => "",
            "alignment" => ["frequency" => "", "structure" => "", "moral" => ""],
            "advancement" => [["lowhd" => "", "highhd" => "", "size" => ""]]
        ];

        // Return JSON or array based on $json parameter
        if ($json) {
            return json_encode($statBlock, JSON_PRETTY_PRINT);
        } else {
            return $statBlock;
        }
    }
}
