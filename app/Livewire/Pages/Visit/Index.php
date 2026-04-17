<?php

declare(strict_types=1);

namespace App\Livewire\Pages\Visit;

use App\Models\Visit;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Url;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    #[Url]
    public string $sortBy = 'name';

    #[Url]
    public string $sortDirection = 'asc';

    #[Url]
    public string $search = '';

    #[Validate('int')]
    public int|null $user = null;

    #[Validate('boolean')]
    public bool|null $allUsers = false;

    public function mount(): void
    {
        $this->user ??= (int) auth()->id();
    }

    public function render(): View
    {
        return view('livewire.pages.visit.index');
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

    public function filterUserFromVisit(Visit $visit): void
    {
        $this->fill([
            'allUsers' => false,
            'user' => $visit->user->id,
        ]);
    }

    /** @return LengthAwarePaginator<int, Visit> */
    #[Computed]
    public function visits(): LengthAwarePaginator
    {
        return Visit::query()
            ->tap(fn (Builder $query) => $this->sortBy ? $query->orderBy($this->sortBy, $this->sortDirection) : $query)
            ->tap(fn (Builder $query) => ! $this->allUsers && $this->user ? $query->where('user_id', $this->user) : $query)
            ->tap(fn (Builder $query) => $this->search ? $query->whereHas('keep', fn (Builder $query) => $query->whereLike('name', "%{$this->search}%")) : $query)
            ->paginate(50);
    }
}
