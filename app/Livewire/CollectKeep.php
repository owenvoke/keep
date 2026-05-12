<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\Collection;
use App\Models\Keep;
use Flux\Flux;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Validate;
use Livewire\Component;

class CollectKeep extends Component
{
    public Keep $keep;

    /** @var string */
    #[Validate(['required', 'string', 'exists:collections,uuid'])]
    public $collection;

    public function render(): View
    {
        return view('livewire.collect-keep');
    }

    public function save(): void
    {
        $this->validate();

        $collection = Collection::query()->where('uuid', $this->collection)->firstOrFail();

        $this->authorize('update', $collection);

        $collection->keeps()->syncWithoutDetaching($this->keep);

        Flux::toast(__(':keep has been added to the collection ":collection".', [
            'keep' => $this->keep->name,
            'collection' => $collection->name,
        ]), variant: 'success');

        // @phpstan-ignore method.nonObject
        Flux::modals()->close('collect-modal');
    }

    /** @return EloquentCollection<int, Collection> */
    #[Computed]
    public function collections(): EloquentCollection
    {
        $user = auth()->user();

        assert($user !== null);

        return $user->collections;
    }
}
