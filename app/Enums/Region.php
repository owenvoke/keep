<?php

declare(strict_types=1);

namespace App\Enums;

enum Region: string
{
    case England = 'GB-ENG';
    case NorthernIreland = 'GB-NIR';
    case Scotland = 'GB-SCT';
    case Wales = 'GB-WLS';

    public function label(): string
    {
        return match ($this) {
            self::England => 'England',
            self::NorthernIreland => 'Northern Ireland',
            self::Scotland => 'Scotland',
            self::Wales => 'Wales',
        };
    }
}
