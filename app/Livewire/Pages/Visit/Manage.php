<?php

declare(strict_types=1);

namespace App\Livewire\Pages\Visit;

use App\Models\Keep;
use App\Models\Visit;
use Flux\Flux;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Number;
use Livewire\Attributes\Validate;
use Livewire\Component;

class Manage extends Component
{
    public Keep $keep;

    public Visit|null $visit = null;

    /** @var string */
    #[Validate('string')]
    public $comment = '';

    /** @var string|null */
    #[Validate('date_format:Y-m-d\TH:i'), Validate('before_or_equal:now')]
    public $visited = null;

    #[Validate('bool')]
    public bool $public = false;

    public function mount(): void
    {
        $user = auth()->user();

        assert($user !== null);

        $this->comment = $this->visit->comment ?? '';
        $this->visited = $this->visit?->visited_at->isoFormat('YYYY-MM-DDTHH:mm');
        $this->public = $this->visit->is_public ?? $user->settings->publicVisits;

        if ($this->visit) {
            $this->authorize('update', $this->visit);
        }
    }

    public function render(): View
    {
        // @phpstan-ignore return.type
        return view('livewire.pages.visit.manage')
            ->title("Manage visit to {$this->keep->name}");
    }

    public function save(): void
    {
        $this->validate();

        if ($this->visit) {
            $this->authorize('update', $this->visit);

            $this->visit->update([
                'comment' => $this->comment,
                'visited_at' => $this->visited,
                'is_public' => $this->public,
            ]);

            Flux::toast(__('Your visit has been saved.'), variant: 'success');

            return;
        }

        $visit = Visit::query()->create([
            'keep_uuid' => $this->keep->uuid,
            'user_id' => auth()->id(),
            'comment' => $this->comment,
            'visited_at' => $this->visited,
            'is_public' => $this->public,
        ]);

        $this->congratulateUser();

        $this->redirectRoute('visit.manage', [
            'keep' => $this->keep,
            'visit' => $visit,
        ], navigate: true);
    }

    public function delete(): void
    {
        $this->validate();

        if ($this->visit === null) {
            return;
        }

        $this->authorize('delete', $this->visit);

        $this->visit->delete();

        Flux::toast(__('Your visit has been deleted.'), variant: 'success');

        $this->redirectRoute('visit.index', navigate: true);
    }

    private function congratulateUser(): void
    {
        $numberOfVisits = auth()->user()?->visits->count();

        if ($numberOfVisits === null) {
            return;
        }

        if ($numberOfVisits % 10 !== 0) {
            return;
        }

        Flux::toast(
            __('Congratulations! This is your :count visit.', ['count' => Number::ordinal($numberOfVisits)]),
            duration: 10000,
            variant: 'success'
        );
    }
}
