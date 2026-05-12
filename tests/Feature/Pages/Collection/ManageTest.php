<?php

declare(strict_types=1);

use App\Livewire\Pages\Collection\Manage as CollectionManage;
use App\Models\Collection;
use App\Models\User;
use Database\Factories\CollectionFactory;

test('collection manage can create a collection', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    Livewire::test(CollectionManage::class)
        ->set('name', 'New Collection')
        ->set('description', 'A collection of Keeps.')
        ->call('save')
        ->assertRedirect();

    $collection = Collection::query()
        ->where('user_id', $user->id)
        ->first();

    expect($collection)
        ->not->toBeNull()
        ->name->toBe('New Collection')
        ->description->toBe('A collection of Keeps.');
});

test('collection manage can update an existing collection', function () {
    $user = User::factory()->create();
    $collection = CollectionFactory::new()->create([
        'user_id' => $user->id,
        'name' => 'Old Name',
    ]);

    $this->actingAs($user);

    Livewire::test(CollectionManage::class, ['collection' => $collection])
        ->set('name', 'Updated name')
        ->call('save')
        ->assertHasNoErrors();

    expect($collection->refresh())
        ->name->toBe('Updated name');
});

test('collection manage prevents users from updating another users collection', function () {
    $user = User::factory()->create();
    $collection = CollectionFactory::new()->create();

    $this->actingAs($user);

    Livewire::test(CollectionManage::class, ['collection' => $collection])
        ->assertForbidden();
});

test('collection manage can delete an existing collection', function () {
    $user = User::factory()->create();
    $collection = CollectionFactory::new()->create([
        'user_id' => $user->id,
        'name' => 'Old name',
    ]);

    $this->actingAs($user);

    Livewire::test(CollectionManage::class, ['collection' => $collection])
        ->call('delete')
        ->assertHasNoErrors()
        ->assertDispatched(
            event: 'toast-show',
            slots: [
                'text' => __('Your collection has been deleted.'),
            ],
        );

    $this->assertModelMissing($collection);
});
