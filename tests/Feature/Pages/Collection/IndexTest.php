<?php

declare(strict_types=1);

use App\Enums\Privacy;
use App\Livewire\Pages\Collection\Index as CollectionIndex;
use App\Models\User;
use Database\Factories\CollectionFactory;

test('collection index page can be rendered', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('collection.index'))
        ->assertOk()
        ->assertSee('Collections');
});

test('collection index only shows collections created by the authenticated user', function () {
    $user = User::factory()->create();
    $anotherUser = User::factory()->create();
    $userCollection = CollectionFactory::new()->for($user)->create();
    $otherUserCollection = CollectionFactory::new()->for($anotherUser)->create();

    $this->actingAs($user);

    Livewire::test(CollectionIndex::class)
        ->assertSee($userCollection->name)
        ->assertDontSee($otherUserCollection->name);
});

test('collection index can filter by search text', function () {
    $user = User::factory()->create();
    $matchingCollection = CollectionFactory::new()->for($user)->create([
        'name' => 'Target Collection',
    ]);
    $otherCollection = CollectionFactory::new()->for($user)->create([
        'name' => 'Other Collection',
    ]);

    $this->actingAs($user);

    Livewire::test(CollectionIndex::class)
        ->set('search', 'Target')
        ->assertSee($matchingCollection->name)
        ->assertDontSee($otherCollection->name);
});

test('collection index can filter by privacy', function () {
    $user = User::factory()->create();
    $publicCollection = CollectionFactory::new()->public()->for($user)->create([
        'name' => 'Public Collection',
    ]);
    $privateCollection = CollectionFactory::new()->public(false)->for($user)->create([
        'name' => 'Private Collection',
    ]);

    $this->actingAs($user);

    Livewire::test(CollectionIndex::class)
        ->set('privacy', Privacy::Public)
        ->assertSee($publicCollection->name)
        ->assertDontSee($privateCollection->name);
});
