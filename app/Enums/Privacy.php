<?php

declare(strict_types=1);

namespace App\Enums;

enum Privacy: string
{
    case Public = 'public';
    case Private = 'private';
}
