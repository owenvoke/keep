<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\Keep;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Livewire\Component;

class Map extends Component
{
    public Keep $keep;

    public int $zoom = 10;

    /** @var Collection<int, Keep> */
    public Collection $additionalKeeps;

    public function render(): View
    {
        return view('livewire.map');
    }
}
