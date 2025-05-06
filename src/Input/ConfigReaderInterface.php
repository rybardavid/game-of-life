<?php

namespace Life\Input;

use Life\Config\GameConfig;
use Life\InvalidInputException;

interface ConfigReaderInterface
{
    /**
     * @throws InvalidInputException
     */
    public function load(): GameConfig;

    public static function getSupportedExtension(): ConfigFormat;
}