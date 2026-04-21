<?php

declare(strict_types=1);

namespace App\DataObjects;

use Exception;
use Geocoder\Laravel\ProviderAndDumperAggregator;
use Geocoder\Model\Address;
use Illuminate\Support\Collection;
use League\Geotools\Coordinate\Coordinate as GeotoolsCoordinate;
use Spatie\LaravelData\Data;

class Coordinates extends Data
{
    public function __construct(
        public float $latitude,
        public float $longitude,
    ) {}

    public static function fromString(string|null $location, bool $requireCoordinates = true): self|null
    {
        if ($location === null || $location === '') {
            return null;
        }

        try {
            $coordinates = new GeotoolsCoordinate($location);

            return new self((float) $coordinates->getLatitude(), (float) $coordinates->getLongitude());
        } catch (Exception $exception) {
            if ($requireCoordinates) {
                throw $exception;
            }
        }

        /** @var Collection<int, Address> $results */
        $results = app(ProviderAndDumperAggregator::class)
            ->limit(1)
            ->geocode($location)
            ->get();

        foreach ($results as $result) {
            if ($result->getCoordinates() === null) {
                continue;
            }

            return new self($result->getCoordinates()->getLatitude(), $result->getCoordinates()->getLongitude());
        }

        return null;
    }

    public function __toString(): string
    {
        return "{$this->latitude}, {$this->longitude}";
    }

    public function link(): string
    {
        return route('map', ['location' => "{$this->latitude}, {$this->longitude}"]);
    }
}
