<?php

declare(strict_types=1);

namespace App\Livewire\Pages\Keep;

use App\DataObjects\Settings;
use App\Enums\Country;
use App\Enums\Region;
use App\Models\Keep;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    private Settings|null $settings = null;

    #[Url]
    public string $sortBy = 'name';

    #[Url]
    public string $sortDirection = 'asc';

    #[Url]
    public string $search = '';

    #[Url]
    public Country|null $country = null;

    #[Url]
    public Region|null $region = null;

    #[Url]
    public string $ownedBy = '';

    #[Url(as: 'visited')]
    public bool $onlyVisited = false;

    public function mount(): void
    {
        $user = auth()->user();

        assert($user !== null);

        $this->settings = $user->settings;
        $this->country ??= $user->country;
    }

    public function render(): View
    {
        return view('livewire.pages.keep.index');
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

    /** @return LengthAwarePaginator<int, Keep> */
    #[Computed]
    public function keeps(): LengthAwarePaginator
    {
        return Keep::query()
            ->tap(fn (Builder $query) => $this->sortBy ? $query->orderBy($this->sortBy, $this->sortDirection) : $query)
            ->tap(fn (Builder $query) => $this->search ? $query->whereLike('name', "%{$this->search}%") : $query)
            ->tap(fn (Builder $query) => $this->country ? $query->where('country', $this->country) : $query)
            ->tap(fn (Builder $query) => $this->region ? $query->where('region', $this->region) : $query)
            ->tap(fn (Builder $query) => $this->ownedBy ? $query->whereLike('owned_by', "%{$this->ownedBy}%") : $query)
            ->tap(fn (Builder $query) => $this->onlyVisited ? $query->whereHas('visits', fn (Builder $query) => $query->where('user_id', auth()->id())) : $query)
            ->tap(fn (Builder $query) => $this->settings?->hideFollies ? $query->whereNot('type', 'Folly') : $query)
            ->paginate(50);
    }
}
