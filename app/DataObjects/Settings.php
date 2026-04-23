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
    ) {}
}
