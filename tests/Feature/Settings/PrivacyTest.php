<?php

declare(strict_types=1);

use App\DataObjects\Settings;
use App\Livewire\Settings\Privacy;
use App\Models\User;
use Livewire\Livewire;

test('privacy settings page can be rendered', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('privacy.edit'))
        ->assertOk()
        ->assertSee('Privacy');
});

test('public visits setting can be set to true', function () {
    $user = User::factory()->withSettings(new Settings(publicVisits: false))->create();

    $this->actingAs($user);

    $response = Livewire::test(Privacy::class)
        ->set('publicVisits', true)
        ->call('updateSettings');

    $response->assertHasNoErrors();

    expect($user->refresh()->settings->publicVisits)->toBeTrue();
});

test('public visits setting can be set to false', function () {
    $user = User::factory()->withSettings(new Settings(publicVisits: true))->create();

    $this->actingAs($user);

    $response = Livewire::test(Privacy::class)
        ->set('publicVisits', false)
        ->call('updateSettings');

    $response->assertHasNoErrors();

    expect($user->refresh()->settings->publicVisits)->toBeFalse();
});

test('public collections setting can be set to true', function () {
    $user = User::factory()->withSettings(new Settings(publicCollections: false))->create();

    $this->actingAs($user);

    $response = Livewire::test(Privacy::class)
        ->set('publicCollections', true)
        ->call('updateSettings');

    $response->assertHasNoErrors();

    expect($user->refresh()->settings->publicCollections)->toBeTrue();
});

test('public collections setting can be set to false', function () {
    $user = User::factory()->withSettings(new Settings(publicCollections: true))->create();

    $this->actingAs($user);

    $response = Livewire::test(Privacy::class)
        ->set('publicCollections', false)
        ->call('updateSettings');

    $response->assertHasNoErrors();

    expect($user->refresh()->settings->publicCollections)->toBeFalse();
});
