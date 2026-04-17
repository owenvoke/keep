<?php

declare(strict_types=1);

use App\Models\User;
use Database\Factories\VisitFactory;

test('visit page can be rendered', function (User|null $user) {
    $visit = VisitFactory::new()->create();

    $user instanceof User ? $this->actingAs($user) : $this->actingAsGuest();

    $this->get(url()->signedRoute('visit.share', ['visit' => $visit]))
        ->assertOk()
        ->assertSee($visit->keep->name);
})->with([
    'authenticated user' => fn () => User::factory()->create(),
    'unauthenticated user' => fn () => null,
]);
