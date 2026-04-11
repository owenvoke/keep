<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\Keep;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Livewire\Component;

class Map extends Component
{
    /** @var Collection<int, Keep> */
    public Collection $keeps;

    public Keep|null $primaryKeep;

    public int $zoom = 10;

    /** @var array{lat: float, lng: float}|null */
    public array|null $center = null;

    public function render(): View
    {
        return view('livewire.map');
    }
}
