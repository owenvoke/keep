<?php

declare(strict_types=1);

namespace App\Rules;

use Closure;
use Geotools\Coordinate\Coordinate;
use Geotools\Exception\InvalidArgumentException;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;

readonly class ValidCoordinatesFormat implements ValidationRule
{
    /** @param  Closure(string, ?string=): PotentiallyTranslatedString  $fail */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        try {
            new Coordinate($value);
        } catch (InvalidArgumentException) {
            $fail('The :attribute must be a valid coordinate format.')->translate();
        }
    }
}
