<?php

declare(strict_types=1);

namespace App\Livewire\Pages\Visit;

use App\Models\Keep;
use App\Models\Visit;
use Flux\Flux;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Validate;
use Livewire\Component;

class Manage extends Component
{
    public Keep $keep;

    public Visit|null $visit = null;

    /** @var string */
    public $comment = '';

    /** @var string|null */
    #[Validate('date_format:Y-m-d\TH:i')]
    #[Validate('before_or_equal:now')]
    public $visited = null;

    public function mount(): void
    {
        $this->comment = $this->visit->comment ?? '';
        $this->visited = $this->visit?->visited_at->isoFormat('YYYY-MM-DDTHH:mm');
    }

    public function render(): View
    {
        return view('livewire.pages.visit.manage');
    }

    public function save(): void
    {
        $this->validate();

        if ($this->visit) {
            $this->authorize('update', $this->visit);

            $this->visit->update([
                'comment' => $this->comment,
                'visited_at' => $this->visited,
            ]);

            Flux::toast('Your visit has been saved.');

            return;
        }

        $visit = Visit::query()->create([
            'keep_uuid' => $this->keep->uuid,
            'user_id' => auth()->id(),
            'comment' => $this->comment,
            'visited_at' => $this->visited,
        ]);

        $this->redirectRoute('visit.manage', [
            'keep' => $this->keep,
            'visit' => $visit,
        ]);
    }
}
