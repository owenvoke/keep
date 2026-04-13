<?php

declare(strict_types=1);

namespace App\Livewire\Pages\Map;

use App\DataObjects\Coordinates;
use App\Models\Keep;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Js;
use Livewire\Attributes\Url;
use Livewire\Attributes\Validate;
use Livewire\Component;

class Show extends Component
{
    #[Url, Validate('nullable'), Validate('numeric')]
    public float|null $latitude = null;

    #[Url, Validate('nullable'), Validate('numeric')]
    public float|null $longitude = null;

    #[Url, Validate('int'), Validate('in:10,25,50,100')]
    public int $distance = 50;

    public function render(): View
    {
        return view('livewire.pages.map.show');
    }

    #[Computed]
    public function coordinates(): Coordinates|null
    {
        if (isset($this->latitude, $this->longitude)) {
            return new Coordinates($this->latitude, $this->longitude);
        }

        if ($coordinates = auth()->user()?->home_coordinates) {
            return new Coordinates($coordinates->latitude, $coordinates->longitude);
        }

        return null;
    }

    /** @return EloquentCollection<int, Keep> */
    #[Computed]
    public function keeps(): EloquentCollection
    {
        $this->validate();

        $coordinates = $this->coordinates();

        if ($coordinates === null) {
            return EloquentCollection::make();
        }

        return Keep::nearestTo(
            coordinates: $coordinates,
            distance: $this->distance,
            includeZero: true
        )->get();
    }

    #[Js]
    public function reload(): string
    {
        return <<<'JS'
            window.location.reload();
        JS;
    }
}
