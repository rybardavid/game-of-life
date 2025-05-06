<?php

namespace Life\Input;

enum ConfigFormat: string
{
    case XML = 'xml';
    case JSON = 'json';

    /**
     * @throws InvalidInputException
     */
    public static function fromExtension(string $extension): self
    {
        return match (strtolower($extension)) {
            self::XML->value => self::XML,
            self::JSON->value => self::JSON,
            default => throw new InvalidInputException("Unsupported file format: $extension")
        };
    }

}
