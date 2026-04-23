<?php

declare(strict_types=1);

namespace App\Enums;

enum Type: string
{
    case ArtilleryFort = 'Artillery Fort';
    case Concentric = 'Concentric';
    case Folly = 'Folly';
    case Fort = 'Fort';
    case FragmentedRemains = 'Fragmented Remains';
    case Keepless = 'Keepless';
    case MotteAndBailey = 'Motte and Bailey';
    case Palace = 'Palace';
    case RoundKeep = 'Round Keep';
    case ShellKeep = 'Shell Keep';
    case SquareKeep = 'Square Keep';

    public function label(): string
    {
        return $this->value;
    }
}
