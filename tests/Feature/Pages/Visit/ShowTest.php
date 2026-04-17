<?php

declare(strict_types=1);

use App\Models\User;
use Database\Factories\VisitFactory;

test('visit page can be rendered', function () {
    $user = User::factory()->create();
    $visit = VisitFactory::new()->create(['user_id' => $user->id]);

    $this->actingAs($user)
        ->get(route('visit.show', ['visit' => $visit]))
        ->assertOk()
        ->assertSee($visit->keep->name)
        ->assertSee('Manage visit');
});
