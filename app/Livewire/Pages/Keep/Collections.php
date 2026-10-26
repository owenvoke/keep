<?php

declare(strict_types=1);

namespace App\Livewire\Pages\Keep;

use App\Models\Keep;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class Collections extends Component
{
    public Keep $keep;

    public function render(): View
    {
        return view('livewire.pages.keep.collections')
            ->title("Collections for {$this->keep->name}");
    }
}
