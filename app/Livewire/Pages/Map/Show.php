<?php

declare(strict_types=1);

namespace App\Livewire\Pages\Map;

use App\DataObjects\Coordinates;
use App\DataObjects\Settings;
use App\Enums\Type;
use App\Models\Keep;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Title('Map')]
class Show extends Component
{
    #[Url, Validate('nullable')]
    public string|null $location = null;

    #[Url, Validate('int'), Validate('in:10,25,50,100')]
    public int $distance = 50;

    private Settings|null $settings = null;

    private Coordinates|null $homeCoordinates = null;

    public function mount(): void
    {
        $user = auth()->user();

        assert($user !== null);

        $this->settings = $user->settings;
        $this->homeCoordinates ??= $user->home_coordinates;
        $this->location ??= $this->homeCoordinates?->__toString();
    }

    public function render(): View
    {
        return view('livewire.pages.map.show');
    }

    /** @return array{latitude: float, longitude: float}|null */
    #[Computed]
    public function center(): array|null
    {
        // @phpstan-ignore return.type
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
        )
            ->when($this->settings?->hideFollies, fn (Builder $query) => $query->whereNot('type', Type::Folly))
            ->when($this->settings?->hideFortifiedManorHouses, fn (Builder $query) => $query->whereNot('type', Type::FortifiedManorHouse))
            ->when($this->settings?->hideTowerHouses, fn (Builder $query) => $query->whereNot('type', Type::TowerHouse))
            ->get();
    }

    #[On('location:updated')]
    public function handleLocationUpdated(float $latitude, float $longitude): void
    {
        $this->location = "{$latitude}, {$longitude}";
    }

    private function parsedLocation(): Coordinates|null
    {
        $location = $this->location ?? $this->homeCoordinates?->__toString();

        return Coordinates::fromString($location, requireCoordinates: false);
    }
}
