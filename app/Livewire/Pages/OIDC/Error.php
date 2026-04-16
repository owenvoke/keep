<?php

declare(strict_types=1);

namespace App\Livewire\Pages\OIDC;

use Illuminate\Contracts\View\View;
use Livewire\Component;

class Error extends Component
{
    public function render(): View
    {
        return view('livewire.pages.oidc.error')
            ->layout('layouts::auth', ['title' => __('OIDC Error')]);
    }
}
