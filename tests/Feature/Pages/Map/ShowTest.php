<?php

declare(strict_types=1);

use App\DataObjects\Coordinates;
use App\Enums\Type;
use App\Livewire\Pages\Map\Show as MapShow;
use App\Models\User;
use Database\Factories\KeepFactory;

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

test('map page can filter by folly setting', function () {
    $user = User::factory()->create([
        'home_coordinates' => new Coordinates(54.093409, -2.89479),
        'settings->hideFollies' => true,
    ]);

    $realKeep = KeepFactory::new()->create([
        'name' => 'Visible Map Keep',
        'coordinates' => new Coordinates(54.12, -2.89),
        'type' => Type::Palace,
    ]);

    $follyKeep = KeepFactory::new()->create([
        'name' => 'Hidden Map Folly',
        'coordinates' => new Coordinates(54.06, -2.88),
        'type' => Type::Folly,
    ]);

    $this->actingAs($user);

    Livewire::test(MapShow::class)
        ->assertSee($realKeep->name)
        ->assertDontSee($follyKeep->name);
});
