<?php

declare(strict_types=1);

namespace App\Livewire\Settings;

use App\Models\User;
use Flux\Flux;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Title('Privacy')]
class Privacy extends Component
{
    #[Locked]
    public User $user;

    #[Validate('boolean')]
    public bool $publicCollections;

    #[Validate('boolean')]
    public bool $publicVisits;

    public function mount(): void
    {
        $user = Auth::user();

        assert($user instanceof User);

        $this->user = $user;

        $this->publicCollections = $user->settings->publicCollections;
        $this->publicVisits = $user->settings->publicVisits;
    }

    public function updateSettings(): void
    {
        $this->validate();

        $this->user->settings->publicCollections = $this->publicCollections;
        $this->user->settings->publicVisits = $this->publicVisits;

        $this->user->update([
            'settings' => $this->user->settings,
        ]);

        Flux::toast(text: __('Settings updated.'), variant: 'success');
    }
}
