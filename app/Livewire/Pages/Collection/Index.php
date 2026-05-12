<?php

declare(strict_types=1);

namespace App\Livewire\Pages\Collection;

use App\Models\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Collections')]
class Index extends Component
{
    use WithPagination;

    #[Url]
    public string $sortBy = 'name';

    /** @var 'asc'|'desc' */
    #[Url, Validate('in:asc,desc')]
    public string $sortDirection = 'asc';

    #[Url]
    public string $search = '';

    #[Url]
    public bool|null $public = null;

    public function render(): View
    {
        return view('livewire.pages.collection.index');
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

    /** @return LengthAwarePaginator<int, Collection> */
    #[Computed]
    public function collections(): LengthAwarePaginator
    {
        $user = auth()->user();

        assert($user !== null);

        return $user->collections()
            ->tap(fn (Builder $query) => $this->sortBy ? $query->orderBy($this->sortBy, $this->sortDirection) : $query)
            ->tap(fn (Builder $query) => $this->search ? $query->whereLike('name', "%{$this->search}%") : $query)
            ->tap(fn (Builder $query) => $this->public ? $query->where('is_public', $this->public) : $query)
            ->paginate(50);
    }
}
