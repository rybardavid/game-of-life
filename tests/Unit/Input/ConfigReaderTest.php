<?php

namespace Tests\Life\Unit\Input;

use Life\Config\GameConfig;
use Life\Input\ConfigReaderFactory;
use PHPUnit\Framework\TestCase;

class ConfigReaderTest extends TestCase
{
    /**
     * @dataProvider jsonInputProvider
     */
    public function testJsonInput(string $jsonFile): void
    {
        $reader = ConfigReaderFactory::create($jsonFile);
        $config = $reader->load();

        self::assertEquals(8, $config->worldSize);
        self::assertEquals(3, $config->species);
        self::assertEquals(5, $config->iterationsCount);

        self::assertEquals(1, $config->cells[3][2]); // y=3, x=2
        self::assertEquals(1, $config->cells[4][2]); // y=4, x=2
        self::assertEquals(2, $config->cells[5][2]); // y=5, x=2
        self::assertNull($config->cells[0][0]);
    }

    public function jsonInputProvider(): array
    {
        return [
            'basic json scenario' => [__DIR__ . '/__fixtures__/input-1.json'],
        ];
    }

}