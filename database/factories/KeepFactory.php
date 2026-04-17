<?php

declare(strict_types=1);

namespace Database\Factories;

use App\DataObjects\Coordinates;
use App\Enums\Country;
use App\Models\Keep;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Keep> */
class KeepFactory extends Factory
{
    /** {@inheritdoc} */
    public function definition(): array
    {
        /** @var Country $country */
        $country = $this->faker->randomElement(Country::cases());

        $regions = $country->regions();

        return [
            'name' => $this->faker->unique()->name(),
            'country' => $country,
            'region' => $regions !== [] ? $this->faker->randomElement($regions) : null,
            'coordinates' => new Coordinates(
                $this->faker->latitude(),
                $this->faker->longitude(),
            ),
            'built' => $this->faker->year(),
            'condition' => 'Intact',
            'owned_by' => 'Private',
            'type' => 'Motte and Bailey',
            'accessible' => $this->faker->boolean(),
        ];
    }
}
