<?php

declare(strict_types=1);

namespace App\Livewire\Pages\OIDC;

use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts::auth'), Title('OIDC Error')]
class Error extends Component
{
    public function render(): View
    {
        return view('livewire.pages.oidc.error');
    }
}
