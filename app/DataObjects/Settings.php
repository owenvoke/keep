<?php

declare(strict_types=1);

namespace App\DataObjects;

use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;

class Settings extends Data
{
    public function __construct(
        #[MapName('hide_follies')]
        public bool $hideFollies = false,
        #[MapName('hide_fortified_manor_houses')]
        public bool $hideFortifiedManorHouses = false,
        #[MapName('hide_tower_houses')]
        public bool $hideTowerHouses = false,
    ) {}
}
