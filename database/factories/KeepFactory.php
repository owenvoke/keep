<?php

declare(strict_types=1);

namespace Database\Factories;

use App\DataObjects\Coordinates;
use App\Enums\Condition;
use App\Enums\Country;
use App\Enums\Type;
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
            'condition' => $this->faker->randomElement(Condition::cases()),
            'owned_by' => 'Private',
            'type' => $this->faker->randomElement(Type::cases()),
            'accessible' => $this->faker->boolean(),
        ];
    }
}
