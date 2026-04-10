<?php

declare(strict_types=1);

use App\Livewire\Pages\Keep\Index as KeepIndex;
use App\Livewire\Pages\Keep\Show as KeepShow;
use App\Livewire\Pages\Visit\Index as VisitIndex;
use App\Livewire\Pages\Visit\Manage as VisitManage;
use App\Livewire\Pages\Visit\Show as VisitShow;
use Illuminate\Routing\Router;

/** @var Router $router */
$router->middleware(['auth'])->group(function (Router $router) {
    $router->livewire('/', KeepIndex::class)->name('keep.index');
    $router->livewire('/keeps/{keep}', KeepShow::class)->name('keep.show');
    $router->livewire('/keeps/{keep}/visit/{visit?}', VisitManage::class)->name('visit.manage');
    $router->livewire('/visits', VisitIndex::class)->name('visit.index');
    $router->livewire('/visits/{visit}', VisitShow::class)->name('visit.show');

    require __DIR__.'/settings.php';
});
