<?php

declare(strict_types=1);

use App\Livewire\CollectKeep;
use App\Models\User;
use Database\Factories\CollectionFactory;
use Database\Factories\KeepFactory;

it('can add a Keep to a collection', function () {
    $user = User::factory()->create();
    $keep = KeepFactory::new()->create();
    $collection = CollectionFactory::new()->for($user)->create();

    $this->actingAs($user);

    $response = Livewire::test(CollectKeep::class, ['keep' => $keep])
        ->set('collection', $collection->uuid)
        ->call('save');

    $response->assertHasNoErrors();

    expect($collection)
        ->keeps->toHaveCount(1)
        ->keeps->first()->uuid->toBe($keep->uuid);
});

it('cannot add a Keep to a missing collection', function () {
    $user = User::factory()->create();
    $keep = KeepFactory::new()->create();

    $this->actingAs($user);

    Livewire::test(CollectKeep::class, ['keep' => $keep])
        ->call('save')
        ->assertHasErrors([
            'collection' => 'required',
        ]);
});

it('cannot add a Keep to another users collection', function () {
    $user = User::factory()->create();
    $keep = KeepFactory::new()->create();
    $collection = CollectionFactory::new()->create();

    $this->actingAs($user);

    Livewire::test(CollectKeep::class, ['keep' => $keep])
        ->set('collection', $collection->uuid)
        ->call('save')
        ->assertForbidden();

    expect($collection)
        ->keeps->toBeEmpty();
});
