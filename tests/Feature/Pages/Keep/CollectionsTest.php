<?php

declare(strict_types=1);

use App\Models\User;
use Database\Factories\CollectionFactory;
use Database\Factories\KeepFactory;

test('keep page can be rendered', function () {
    $user = User::factory()->create();
    $keep = KeepFactory::new()->create();

    $this->actingAs($user)
        ->get(route('keep.collections', ['keep' => $keep]))
        ->assertOk()
        ->assertSee($keep->name);
});

test('keep page can show list of collections', function () {
    $user = User::factory()->create();
    $keep = KeepFactory::new()->create();
    $collection = CollectionFactory::new()->withKeeps($keep)->public()->create();

    $this->actingAs($user)
        ->get(route('keep.collections', ['keep' => $keep]))
        ->assertOk()
        ->assertSee($keep->name)
        ->assertSee($collection->name);
});
