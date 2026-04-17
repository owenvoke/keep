<?php

declare(strict_types=1);

namespace App\Livewire\Settings;

use App\DataObjects\Coordinates;
use App\Enums\Country;
use App\Rules\ValidLocation;
use Flux\Flux;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Title('Location')]
class Location extends Component
{
    public Country|null $country = null;

    #[Validate(new ValidLocation)]
    public string|null $coordinates = null;

    public function mount(): void
    {
        $this->country = Auth::user()?->country;
        $this->coordinates = Auth::user()?->home_coordinates?->__toString();
    }

    public function updateLocation(): void
    {
        $this->validate();

        $coordinates = Coordinates::fromString($this->coordinates);

        $user = Auth::user();

        assert($user !== null);

        $user->update([
            'country' => $this->country,
            'home_coordinates' => $coordinates,
        ]);

        Flux::toast(text: __('Location updated.'), variant: 'success');
    }
}
