<?php

declare(strict_types=1);

use App\Models\User;

test('appearance settings page can be rendered', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('appearance.edit'))
        ->assertOk()
        ->assertSee('Appearance');
});
