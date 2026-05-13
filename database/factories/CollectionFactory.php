<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Collection;
use App\Models\Keep;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Collection> */
class CollectionFactory extends Factory
{
    /** {@inheritdoc} */
    public function definition(): array
    {
        return [
            'name' => sprintf("%s's Collection", $this->faker->name()),
            'description' => $this->faker->sentence(),
            'user_id' => UserFactory::new(),
            'is_public' => false,
        ];
    }

    public function public(bool $public = true): self
    {
        return $this->state(fn (array $attributes) => [
            'is_public' => $public,
        ]);
    }

    public function withKeeps(Keep ...$keeps): self
    {
        return $this->afterCreating(
            fn (Collection $collection) => $collection->keeps()->attach($keeps)
        );
    }
}
