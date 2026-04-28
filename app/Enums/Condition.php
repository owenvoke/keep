<?php

declare(strict_types=1);

namespace App\Enums;

enum Condition: string
{
    case Earthworks = 'Earthworks';
    case FragmentedRemains = 'Fragmented Remains';
    case Intact = 'Intact';
    case NoVisibleRemains = 'No Visible Remains';
    case PartiallyRestored = 'Partially Restored';
    case PartialRuins = 'Partial Ruins';
    case Rebuilt = 'Rebuilt';
    case Restored = 'Restored';
    case Ruins = 'Ruins';

    public function label(): string
    {
        return $this->value;
    }
}
