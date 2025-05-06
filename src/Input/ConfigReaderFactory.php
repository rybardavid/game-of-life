<?php

namespace Life\Input;

use Life\InvalidInputException;

final class ConfigReaderFactory
{
    /**
     * @throws InvalidInputException
     */
    public static function create(string $filePath): ConfigReaderInterface
    {
        $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
        $format = ConfigFormat::fromExtension($extension);
        return match ($format) {
            ConfigFormat::XML => new XmlFileConfigReader($filePath),
            ConfigFormat::JSON => new JsonFileConfigReader($filePath),
        };
    }
}