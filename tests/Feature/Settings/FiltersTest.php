<?php

declare(strict_types=1);

use App\Livewire\Settings\Filters;
use App\Models\User;

test('filters settings page can be rendered', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('filters.edit'))
        ->assertOk()
        ->assertSee('Filters');
});

test('hide follies filter can be set to true', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $response = Livewire::test(Filters::class)
        ->set('hideFollies', true)
        ->call('updateSettings');

    $response->assertHasNoErrors();

    expect($user->refresh()->settings->hideFollies)->toBeTrue();
});

test('hide fortified manor houses filter can be set to true', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $response = Livewire::test(Filters::class)
        ->set('hideFortifiedManorHouses', true)
        ->call('updateSettings');

    $response->assertHasNoErrors();

    expect($user->refresh()->settings->hideFortifiedManorHouses)->toBeTrue();
});

test('hide tower houses filter can be set to true', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $response = Livewire::test(Filters::class)
        ->set('hideTowerHouses', true)
        ->call('updateSettings');

    $response->assertHasNoErrors();

    expect($user->refresh()->settings->hideTowerHouses)->toBeTrue();
});
