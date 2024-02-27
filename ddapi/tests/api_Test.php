<?php

use PHPUnit\Framework\TestCase;

class CharacterTest extends TestCase
{
    public function testCharacterCRAdvancement()
    {
        // Mock data for testing
        $data = [
            [
                'cr' => 1,
                'advancement' => [
                    ['highhd' => 5],
                    ['highhd' => 7],
                ]
            ],
            [
                'cr' => 2,
                'advancement' => [
                    ['highhd' => 10],
                    ['highhd' => 12],
                ]
            ],
            // Add more test cases as needed
        ];

        // Include the PHP file with your character logic
        include '..\index.php';

        // Test each character's CRAdvancement
        foreach ($data as $character) {
            // Modify character data as needed
            $originalCR = $character['cr'];
            $maxHD = max(array_column($character['advancement'], 'highhd'));
            $expectedCRAdvancement = $originalCR + floor($maxHD / 4);

            // Call the logic to calculate CRAdvancement
            $this->assertArrayHasKey('CRAdvancement', $character);
            $this->assertEquals($expectedCRAdvancement, $character['CRAdvancement']);
        }
    }
}
