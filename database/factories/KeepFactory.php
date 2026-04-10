<?php

declare(strict_types=1);

namespace Database\Factories;

use App\DataObjects\Coordinates;
use App\Enums\Region;
use App\Models\Keep;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Keep> */
class KeepFactory extends Factory
{
    /** {@inheritdoc} */
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->name(),
            'region' => $this->faker->randomElement(Region::cases()),
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
