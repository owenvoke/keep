<?php

declare(strict_types=1);

namespace App\Livewire\Pages\Collection;

use App\Models\Collection;
use Flux\Flux;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Validate;
use Livewire\Component;

class Manage extends Component
{
    public Collection|null $collection = null;

    /** @var string */
    #[Validate(['required', 'string', 'max:255'])]
    public $name = '';

    /** @var string */
    #[Validate('string')]
    public $description = '';

    #[Validate('bool')]
    public bool $public;

    public function mount(): void
    {
        $user = auth()->user();

        assert($user !== null);

        $this->name = $this->collection->name ?? '';
        $this->description = $this->collection->description ?? '';
        $this->public = $this->collection->is_public ?? $user->settings->publicCollections;

        if ($this->collection) {
            $this->authorize('update', $this->collection);
        }
    }

    public function render(): View
    {
        // @phpstan-ignore return.type
        return view('livewire.pages.collection.manage')
            ->title($this->collection ? "Manage collection named {$this->collection->name}" : 'Manage collection');
    }

    public function save(): void
    {
        $this->validate();

        if ($this->collection) {
            $this->authorize('update', $this->collection);

            $this->collection->update([
                'name' => $this->name,
                'description' => $this->description,
                'is_public' => $this->public,
            ]);

            Flux::toast(__('Your collection has been saved.'), variant: 'success');

            return;
        }

        $collection = Collection::query()->create([
            'user_id' => auth()->id(),
            'name' => $this->name,
            'description' => $this->description,
            'is_public' => $this->public,
        ]);

        $this->redirectRoute('collection.manage', [
            'collection' => $collection,
        ], navigate: true);
    }

    public function delete(): void
    {
        $this->validate();

        if ($this->collection === null) {
            return;
        }

        $this->authorize('delete', $this->collection);

        $this->collection->delete();

        Flux::toast(__('Your collection has been deleted.'), variant: 'success');

        $this->redirectRoute('collection.index', navigate: true);
    }
}
