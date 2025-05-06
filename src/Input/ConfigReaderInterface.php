<?php

namespace Life\Input;

use Life\Config\GameConfig;

interface ConfigReaderInterface
{
    /**
     * @throws InvalidInputException
     */
    public function load(): GameConfig;

    public static function getSupportedExtension(): ConfigFormat;
}