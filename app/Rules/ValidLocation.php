<?php

declare(strict_types=1);

namespace App\Rules;

use App\DataObjects\Coordinates;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;
use League\Geotools\Exception\InvalidArgumentException;

readonly class ValidLocation implements ValidationRule
{
    /** @param  Closure(string, ?string=): PotentiallyTranslatedString  $fail */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        try {
            Coordinates::fromString($value);
        } catch (InvalidArgumentException) {
            $fail('The :attribute must be a valid coordinate format.')->translate();
        }
    }
}
