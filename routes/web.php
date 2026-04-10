<?php

declare(strict_types=1);

use App\Livewire\Pages\Keep\Index as KeepIndex;
use App\Livewire\Pages\Keep\Show as KeepShow;
use App\Livewire\Pages\Visit\Index as VisitIndex;
use App\Livewire\Pages\Visit\Manage as VisitManage;
use App\Livewire\Pages\Visit\Show as VisitShow;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    Route::livewire('/', KeepIndex::class)->name('keep.index');
    Route::livewire('/keeps/{keep}', KeepShow::class)->name('keep.show');
    Route::livewire('/keeps/{keep}/visit/{visit?}', VisitManage::class)->name('visit.manage');
    Route::livewire('/visits', VisitIndex::class)->name('visit.index');
    Route::livewire('/visits/{visit}', VisitShow::class)->name('visit.show');

    require __DIR__.'/settings.php';
});
