<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\Keep;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class Map extends Component
{
    /** @var Collection<int, Keep> */
    #[Reactive]
    public Collection $keeps;

    #[Locked]
    public Keep|null $primaryKeep;

    #[Locked]
    public int $zoom = 10;

    /** @var array{latitude: float, longitude: float}|null */
    #[Reactive]
    public array|null $center = null;

    public function render(): View
    {
        return view('livewire.map');
    }
}
