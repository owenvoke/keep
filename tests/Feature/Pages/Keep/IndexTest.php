<?php

declare(strict_types=1);

use App\Enums\Condition;
use App\Enums\Country;
use App\Enums\Region;
use App\Enums\Type;
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

test('keep index can filter by folly setting', function () {
    $user = User::factory()->create([
        'settings->hideFollies' => true,
    ]);
    $realKeep = KeepFactory::new()->create([
        'name' => 'Visited Keep',
        'type' => Type::Palace,
    ]);
    $follyKeep = KeepFactory::new()->create([
        'name' => 'Unvisited Keep',
        'type' => Type::Folly,
    ]);

    $this->actingAs($user);

    Livewire::test(KeepIndex::class)
        ->assertSee($realKeep->name)
        ->assertDontSee($follyKeep->name);
});

test('keep index can filter by type', function () {
    $user = User::factory()->create(['country' => Country::GB]);
    $matchingKeep = KeepFactory::new()->create([
        'name' => 'Round Keep Match',
        'country' => Country::GB,
        'region' => Region::England,
        'type' => Type::RoundKeep,
    ]);
    $otherKeep = KeepFactory::new()->create([
        'name' => 'Fort Keep Other',
        'country' => Country::GB,
        'region' => Region::England,
        'type' => Type::Fort,
    ]);

    $this->actingAs($user);

    Livewire::test(KeepIndex::class)
        ->set('type', Type::RoundKeep)
        ->assertSee($matchingKeep->name)
        ->assertDontSee($otherKeep->name);
});

test('keep index can filter by condition', function () {
    $user = User::factory()->create(['country' => Country::GB]);
    $matchingKeep = KeepFactory::new()->create([
        'name' => 'Intact Keep Match',
        'country' => Country::GB,
        'region' => Region::England,
        'condition' => Condition::Intact,
    ]);
    $otherKeep = KeepFactory::new()->create([
        'name' => 'Ruins Keep Other',
        'country' => Country::GB,
        'region' => Region::England,
        'condition' => Condition::Ruins,
    ]);

    $this->actingAs($user);

    Livewire::test(KeepIndex::class)
        ->set('condition', Condition::Intact)
        ->assertSee($matchingKeep->name)
        ->assertDontSee($otherKeep->name);
});
