<?php

declare(strict_types=1);

namespace App\DataObjects;

use App\Enums\Region;
use Spatie\LaravelData\Attributes\MapOutputName;
use Spatie\LaravelData\Data;

class KeepEntry extends Data
{
    public function __construct(
        public string $id,
        public string $name,
        public Region $region,
        public Coordinates $coordinates,
        public string $built,
        public string $condition,
        #[MapOutputName('owned_by')]
        public string $ownedBy,
        public string $type,
        public bool $accessible = true,
        #[MapOutputName('alternative_names')]
        public array|null $alternativeNames = null,
        public string|null $description = null,
        public string|null $homepage = null,
    ) {}
}
