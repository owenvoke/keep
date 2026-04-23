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

#[Title('Other')]
class Filters extends Component
{
    #[Locked]
    public User $user;

    #[Validate('boolean')]
    public bool $hideFollies;

    public function mount(): void
    {
        $user = Auth::user();

        assert($user instanceof User);

        $this->user = $user;

        $this->hideFollies = $user->settings->hideFollies;
    }

    public function updateSettings(): void
    {
        $this->validate();

        $this->user->settings->hideFollies = $this->hideFollies;

        $this->user->update([
            'settings' => $this->user->settings,
        ]);

        Flux::toast(text: __('Settings updated.'), variant: 'success');
    }
}
