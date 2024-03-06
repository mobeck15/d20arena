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

        $movement = [];
        if (preg_match_all('/([a-z]*) ?(\d+) ft. ?\(?([a-z]*)\)?/', $lines["speed"], $matches, PREG_SET_ORDER)) {
            //$movement = $matches;
            foreach ($matches as $match) {
                $usekey = "land";
                if ($match[1] <> "") {
                    //$match[1] = 'speed';
                    $usekey = $match[1];
                }
                $movement[$usekey]['speed'] = $match[2];
                if ($match[3] <> "")
                    $movement[$usekey]['agility'] = $match[3];
            }
        }

        // Construct stat block array
        $statBlock = [
            "name" => trim(explode("\n", $text)[0]),
            "size" => $size,
            "type" => $type,
            "hd" => $hd,
            "initiative" => intval($lines["initiative"]),
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
