<?php

declare(strict_types=1);

use App\Enums\Country;
use App\Enums\Region;
use App\Livewire\Pages\Keep\Index as KeepIndex;
use App\Models\User;
use Database\Factories\KeepFactory;
use Database\Factories\VisitFactory;

test('keep index can filter by search text', function () {
    $user = User::factory()->create(['country' => Country::GB]);
    $matchingKeep = KeepFactory::new()->create([
        'name' => 'Target Keep',
        'country' => Country::GB,
        'region' => Region::England,
    ]);
    $otherKeep = KeepFactory::new()->create([
        'name' => 'Other Keep',
        'country' => Country::GB,
        'region' => Region::England,
    ]);

    $this->actingAs($user);

    Livewire::test(KeepIndex::class)
        ->set('search', 'Target')
        ->assertSee($matchingKeep->name)
        ->assertDontSee($otherKeep->name);
});

test('keep index can filter to visited keeps only', function () {
    $user = User::factory()->create(['country' => Country::GB]);
    $visitedKeep = KeepFactory::new()->create([
        'name' => 'Visited Keep',
        'country' => Country::GB,
        'region' => Region::England,
    ]);
    $unvisitedKeep = KeepFactory::new()->create([
        'name' => 'Unvisited Keep',
        'country' => Country::GB,
        'region' => Region::England,
    ]);

    VisitFactory::new()->create([
        'keep_uuid' => $visitedKeep->uuid,
        'user_id' => $user->id,
    ]);

    $this->actingAs($user);

    Livewire::test(KeepIndex::class)
        ->set('onlyVisited', true)
        ->assertSee($visitedKeep->name)
        ->assertDontSee($unvisitedKeep->name);
});
