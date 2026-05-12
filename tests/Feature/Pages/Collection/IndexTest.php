<?php

declare(strict_types=1);

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
