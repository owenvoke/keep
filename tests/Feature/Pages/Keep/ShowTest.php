<?php

declare(strict_types=1);

use App\Models\User;
use Database\Factories\KeepFactory;

test('keep page can be rendered', function () {
    $user = User::factory()->create();
    $keep = KeepFactory::new()->create();

    $this->actingAs($user)
        ->get(route('keep.show', ['keep' => $keep]))
        ->assertOk()
        ->assertSee($keep->name);
});
