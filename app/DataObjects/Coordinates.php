<?php

declare(strict_types=1);

namespace App\DataObjects;

use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;

class Coordinates extends Data
{
    public function __construct(
        #[MapName('lat')]
        public float $latitude,
        #[MapName('lng')]
        public float $longitude,
    ) {}

    public function __toString(): string
    {
        return "{$this->latitude}, {$this->longitude}";
    }

    public function link(): string
    {
        return "https://www.google.com/maps/place/{$this->latitude},{$this->longitude}";
    }
}
