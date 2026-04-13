<?php

declare(strict_types=1);

namespace App\DataObjects;

use Geotools\Coordinate\Coordinate as GeotoolsCoordinate;
use Spatie\LaravelData\Data;

class Coordinates extends Data
{
    public function __construct(
        public float $latitude,
        public float $longitude,
    ) {}

    public static function fromString(string|null $coordinates): self|null
    {
        if ($coordinates === null || $coordinates === '') {
            return null;
        }

        $coordinates = new GeotoolsCoordinate($coordinates);

        return new self($coordinates->getLatitude(), $coordinates->getLongitude());
    }

    public function __toString(): string
    {
        return "{$this->latitude}, {$this->longitude}";
    }

    public function link(): string
    {
        return "https://www.google.com/maps/place/{$this->latitude},{$this->longitude}";
    }
}
