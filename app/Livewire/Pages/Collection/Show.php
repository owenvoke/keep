<?php

declare(strict_types=1);

namespace App\Livewire\Pages\Collection;

use App\Models\Collection;
use App\Models\Keep;
use Flux\Flux;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Url;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithPagination;

class Show extends Component
{
    use WithPagination;

    #[Url]
    public string $sortBy = 'name';

    /** @var 'asc'|'desc' */
    #[Url, Validate('in:asc,desc')]
    public string $sortDirection = 'asc';

    public Collection $collection;

    public function render(): View
    {
        $this->authorize('view', $this->collection);

        // @phpstan-ignore return.type
        return view('livewire.pages.collection.show')
            ->title($this->collection->name);
    }

    public function sort(string $column): void
    {
        if ($this->sortBy === $column) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $column;
            $this->sortDirection = 'asc';
        }
    }

    /** @return LengthAwarePaginator<int, Keep&object{pivot: Pivot}> */
    #[Computed]
    public function keeps(): LengthAwarePaginator
    {
        return $this->collection->keeps()
            ->tap(fn (Builder $query) => $this->sortBy ? $query->orderBy($this->sortBy, $this->sortDirection) : $query)
            ->paginate(100);
    }

    public function removeFromCollection(string $uuid): void
    {
        $this->authorize('update', $this->collection);

        /** @var Keep $keep */
        $keep = Keep::find($uuid);

        $this->collection->keeps()->detachOrFail($keep);

        $this->collection->refresh();

        $this->closeRemoveCollectedModal($uuid);

        Flux::toast(__('Keep removed from collection.'), variant: 'success');
    }

    private function closeRemoveCollectedModal(string $uuid): void
    {
        // @phpstan-ignore method.nonObject
        $this->modal("remove-collected:{$uuid}")->close();
    }
}
