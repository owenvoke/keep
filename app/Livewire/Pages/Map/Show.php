<?php

declare(strict_types=1);

namespace App\Livewire\Pages\Map;

use App\DataObjects\Coordinates;
use App\Models\Keep;
use App\Rules\ValidLocation;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Livewire\Attributes\Validate;
use Livewire\Component;

class Show extends Component
{
    #[Url, Validate('nullable'), Validate(new ValidLocation)]
    public string|null $location = null;

    #[Url, Validate('int'), Validate('in:10,25,50,100')]
    public int $distance = 50;

    private Coordinates|null $homeCoordinates = null;

    public function render(): View
    {
        return view('livewire.pages.map.show');
    }

    /** @var array{latitude: float, longitude: float}|null */
    #[Computed]
    public function center(): array|null
    {
        return $this->parsedLocation()?->toArray();
    }

    /** @return EloquentCollection<int, Keep> */
    #[Computed]
    public function keeps(): EloquentCollection
    {
        $this->validate();

        $coordinates = $this->parsedLocation();

        if ($coordinates === null) {
            return EloquentCollection::make();
        }

        return Keep::nearestTo(
            coordinates: $coordinates,
            distance: $this->distance,
            includeZero: true
        )->get();
    }

    #[On('map-geolocated')]
    public function handleMapGeoLocated(float $latitude, float $longitude): void
    {
        $this->location = "{$latitude}, {$longitude}";
    }

    private function parsedLocation(): Coordinates|null
    {
        $this->homeCoordinates ??= auth()->user()?->home_coordinates;

        return Coordinates::fromString($this->location ?? $this->homeCoordinates?->__toString());
    }
}
