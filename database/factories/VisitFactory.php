<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Visit;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Visit> */
class VisitFactory extends Factory
{
    /** {@inheritdoc} */
    public function definition(): array
    {
        return [
            'keep_uuid' => KeepFactory::new(),
            'user_id' => UserFactory::new(),
            'comment' => $this->faker->sentence(),
            'visited_at' => $this->faker->dateTimeBetween('-1 year'),
        ];
    }
}
