<?php

declare(strict_types=1);

namespace App\Livewire\Settings;

use App\DataObjects\Coordinates;
use App\Rules\ValidLocation;
use Flux\Flux;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Title('Home coordinates')]
class HomeCoordinates extends Component
{
    #[Validate(new ValidLocation)]
    public string|null $coordinates = null;

    public function mount(): void
    {
        $this->coordinates = Auth::user()?->home_coordinates?->__toString();
    }

    public function updateHomeCoordinates(): void
    {
        $this->validate();

        $coordinates = Coordinates::fromString($this->coordinates);

        $user = Auth::user();

        assert($user !== null);

        $user->update([
            'home_coordinates' => $coordinates,
        ]);

        Flux::toast(text: __('Home coordinates updated.'), variant: 'success');
    }
}
