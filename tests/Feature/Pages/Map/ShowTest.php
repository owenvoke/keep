<?php

declare(strict_types=1);

use App\DataObjects\Coordinates;
use App\Livewire\Pages\Map\Show as MapShow;
use App\Models\User;

test('map page can be rendered', function () {
    $user = User::factory()->create([
        'home_coordinates' => new Coordinates(54.093409, -2.89479),
    ]);

    $this->actingAs($user)
        ->get(route('map'))
        ->assertOk()
        ->assertSee('Map');
});

test('map page updates location from map events', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    Livewire::test(MapShow::class)
        ->call('handleLocationUpdated', 54.093409, -2.89479)
        ->assertSet('location', '54.093409, -2.89479');
});

test('map page can be rendered with location and distance query parameters', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $this->get(route('map', ['location' => '54.093409, -2.89479', 'distance' => 25]))
        ->assertOk()
        ->assertSee('Map');
});
