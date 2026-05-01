<?php

declare(strict_types=1);

namespace App\Livewire\Pages\Visit;

use App\Models\Visit;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class Show extends Component
{
    public Visit $visit;

    public function render(): View
    {
        // @phpstan-ignore return.type
        return view('livewire.pages.visit.show')
            ->title("Visit to {$this->visit->keep->name} by {$this->visit->user->name}");
    }
}
