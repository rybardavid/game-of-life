<?php

namespace Life\Input;

use Life\Config\GameConfig;
use Life\InvalidInputException;

final class JsonFileConfigReader implements ConfigReaderInterface
{
    public function __construct(private readonly string $filePath)
    {
    }

    public static function getSupportedExtension(): ConfigFormat
    {
        return ConfigFormat::JSON;
    }

    public function load(): GameConfig
    {
        if (!file_exists($this->filePath)) {
            throw new InvalidInputException("Unable to read nonexistent file");
        }

        $content = file_get_contents($this->filePath);
        if ($content === false) {
            throw new InvalidInputException("Cannot read file");
        }

        $data = json_decode($content, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new InvalidInputException("Invalid JSON file");
        }

        $this->validateJson($data);

        $worldSize = (int) ($data['world']['cells'] ?? 0);
        $speciesCount = (int) ($data['world']['species'] ?? 0);
        $iterationsCount = (int) ($data['world']['iterations'] ?? 0);

        if ($worldSize <= 0) {
            throw new InvalidInputException("Value of 'cells' must be positive number");
        }
        if ($speciesCount <= 0) {
            throw new InvalidInputException("Value of 'species' must be positive number");
        }
        if ($iterationsCount < 0) {
            throw new InvalidInputException("Value of 'iterations' must be zero or positive number");
        }

        return new GameConfig(
            $worldSize,
            $speciesCount,
            $iterationsCount,
            $this->readCells($data['organisms'] ?? [], $worldSize, $speciesCount),
        );
    }

    private function validateJson(array $data): void
    {
        if (!isset($data['world'])) {
            throw new InvalidInputException("Missing 'world' object");
        }
        if (!isset($data['world']['iterations'])) {
            throw new InvalidInputException("Missing 'iterations' field");
        }
        if (!isset($data['world']['cells'])) {
            throw new InvalidInputException("Missing 'cells' field");
        }
        if (!isset($data['world']['species'])) {
            throw new InvalidInputException("Missing 'species' field");
        }
        if (!isset($data['organisms']) || !is_array($data['organisms'])) {
            throw new InvalidInputException("Missing 'organisms' array");
        }
    }

    private function readCells(array $organisms, int $worldSize, int $speciesCount): array
    {
        $cells = [];

        foreach ($organisms as $organism) {
            if (!isset($organism['x_pos'], $organism['y_pos'], $organism['species'])) {
                throw new InvalidInputException("Each organism must have x_pos, y_pos, and species");
            }

            $x = (int) $organism['x_pos'];
            $y = (int) $organism['y_pos'];
            $species = (int) $organism['species'];

            if ($x < 0 || $x >= $worldSize) {
                throw new InvalidInputException("Value of x_pos must be between 0 and number of cells");
            }
            if ($y < 0 || $y >= $worldSize) {
                throw new InvalidInputException("Value of y_pos must be between 0 and number of cells");
            }
            if ($species < 0 || $species >= $speciesCount) {
                throw new InvalidInputException("Value of species must be between 0 and maximal number of species");
            }

            $cells[$y] ??= [];
            $finalSpecies = $species;
            if (isset($cells[$y][$x])) {
                $existingCell = $cells[$y][$x];
                $availableSpecies = [$existingCell, $species];
                $finalSpecies = $availableSpecies[array_rand($availableSpecies)];
            }
            $cells[$y][$x] = $finalSpecies;
        }

        // Fill empty cells with null
        for ($y = 0; $y < $worldSize; $y++) {
            $cells[$y] ??= [];
            for ($x = 0; $x < $worldSize; $x++) {
                if (!isset($cells[$y][$x])) {
                    $cells[$y][$x] = null;
                }
            }
        }

        return $cells;
    }

}