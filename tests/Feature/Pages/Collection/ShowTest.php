<?php

declare(strict_types=1);

use App\Models\User;
use Database\Factories\CollectionFactory;

test('collection page can be rendered', function () {
    $user = User::factory()->create();
    $collection = CollectionFactory::new()->for($user)->public()->create();

    $this->actingAs($user)
        ->get(route('collection.show', ['collection' => $collection]))
        ->assertOk()
        ->assertSee($collection->name)
        ->assertSee('Manage collection');
});

test('collection page can be rendered for owner when private', function () {
    $user = User::factory()->create();
    $collection = CollectionFactory::new()->for($user)->public(false)->create();

    $this->actingAs($user)
        ->get(route('collection.show', ['collection' => $collection]))
        ->assertOk()
        ->assertSee($collection->name)
        ->assertSee('Manage collection');
});

test('collection page can be rendered for another user when public', function () {
    $user = User::factory()->create();
    $collection = CollectionFactory::new()->public()->create();

    $this->actingAs($user)
        ->get(route('collection.show', ['collection' => $collection]))
        ->assertOk()
        ->assertSee($collection->name)
        ->assertDontSee('Manage collection');
});

test('collection page cannot be rendered for another user when private', function () {
    $user = User::factory()->create();
    $collection = CollectionFactory::new()->public(false)->create();

    $this->actingAs($user)
        ->get(route('collection.show', ['collection' => $collection]))
        ->assertForbidden();
});
