<?php

declare(strict_types=1);

use App\Http\Controllers\Auth\OIDCController;
use App\Livewire\Pages\Keep\Index as KeepIndex;
use App\Livewire\Pages\Keep\Show as KeepShow;
use App\Livewire\Pages\Map\Show as MapShow;
use App\Livewire\Pages\OIDC\Error as OIDCError;
use App\Livewire\Pages\Visit\Index as VisitIndex;
use App\Livewire\Pages\Visit\Manage as VisitManage;
use App\Livewire\Pages\Visit\Share as VisitShare;
use App\Livewire\Pages\Visit\Show as VisitShow;
use Illuminate\Routing\Router;

/** @var Router $router */
$router->middleware(['auth', 'verified'])->group(function (Router $router) {
    $router->livewire('/', KeepIndex::class)->name('keep.index');
    $router->livewire('/keeps/{keep}', KeepShow::class)->name('keep.show');
    $router->livewire('/keeps/{keep}/visit/{visit?}', VisitManage::class)->name('visit.manage');
    $router->livewire('/visits', VisitIndex::class)->name('visit.index');
    $router->livewire('/visits/{visit}', VisitShow::class)->name('visit.show');
    $router->livewire('/map', MapShow::class)->name('map');

    require __DIR__.'/settings.php';
});

$router->get('/auth/oidc', [OIDCController::class, 'redirect'])->name('oidc.redirect');
$router->get('/auth/oidc/callback', [OIDCController::class, 'callback'])->name('oidc.callback');
$router->livewire('/auth/oidc/error', OIDCError::class)->name('oidc.error');

$router->livewire('/visits/{visit}/share', VisitShare::class)->name('visit.share');
