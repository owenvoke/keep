<?php

declare(strict_types=1);

use App\Enums\Country;
use App\Livewire\Settings\Location;
use App\Models\User;
use Livewire\Livewire;

test('location settings page can be rendered', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('location.edit'))
        ->assertOk()
        ->assertSee('Location')
        ->assertSee('Country')
        ->assertSee('Coordinates');
});

test('country can be updated', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $response = Livewire::test(Location::class)
        ->set('country', 'GB')
        ->call('updateLocation');

    $response->assertHasNoErrors();

    expect($user->refresh()->country)->toBe(Country::GB);
});

test('home coordinates can be updated', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $response = Livewire::test(Location::class)
        ->set('coordinates', '-2.89479, 54.093409')
        ->call('updateLocation');

    $response->assertHasNoErrors();

    expect($user->refresh()->home_coordinates)
        ->latitude->toBe(-2.89479)
        ->longitude->toBe(54.093409);
});

test('home coordinates can be set to null', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $response = Livewire::test(Location::class)
        ->set('coordinates', '')
        ->call('updateLocation');

    $response->assertHasNoErrors();

    expect($user->refresh()->home_coordinates)->toBeNull();
});

test('home coordinates cannot be set to invalid coordinates', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $response = Livewire::test(Location::class)
        ->set('coordinates', 'invalid')
        ->call('updateLocation');

    $response->assertHasErrors(['coordinates']);
});
