<?php

declare(strict_types=1);

use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\HomeCoordinates;
use App\Livewire\Settings\Profile;
use App\Livewire\Settings\Security;
use Illuminate\Routing\Router;
use Laravel\Fortify\Features;

/** @var Router $router */
$router->middleware(['auth'])->group(function (Router $router) {
    $router->redirect('settings', 'settings/profile');

    $router->livewire('settings/profile', Profile::class)->name('profile.edit');
});

$router->middleware(['auth', 'verified'])->group(function (Router $router) {
    $router->livewire('settings/appearance', Appearance::class)->name('appearance.edit');
    $router->livewire('settings/coordinates', HomeCoordinates::class)->name('coordinates.edit');

    $router->livewire('settings/security', Security::class)
        ->middleware(
            when(
                Features::canManageTwoFactorAuthentication()
                && Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword'),
                ['password.confirm'],
                [],
            ),
        )
        ->name('security.edit');
});
