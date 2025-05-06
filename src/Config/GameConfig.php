<?php

namespace Life\Config;

final class GameConfig
{
    public function __construct(
        public readonly int $worldSize,
        public readonly int $species,
        public readonly int $iterationsCount,
        /** @var int[][]|null[][] */
        public readonly array $cells,
    ) {}
}