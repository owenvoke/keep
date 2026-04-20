<?php

declare(strict_types=1);

namespace App\Livewire\Pages\Visit;

use App\Models\Visit;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class Share extends Component
{
    public Visit $visit;

    public function render(): View
    {
        return view('livewire.pages.visit.share')
            ->layout('layouts::share', ['title' => $this->visit->keep->name]);
    }
}
