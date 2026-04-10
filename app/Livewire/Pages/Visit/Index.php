<?php

declare(strict_types=1);

namespace App\Livewire\Pages\Visit;

use App\Models\Visit;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    #[Url]
    public $sortBy = 'name';

    #[Url]
    public $sortDirection = 'asc';

    #[Url]
    public $search = '';

    public function render()
    {
        return view('livewire.pages.visit.index');
    }

    public function sort($column)
    {
        if ($this->sortBy === $column) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $column;
            $this->sortDirection = 'asc';
        }
    }

    #[Computed]
    public function visits()
    {
        return Visit::query()
            ->tap(fn (Builder $query) => $this->sortBy ? $query->orderBy($this->sortBy, $this->sortDirection) : $query)
            ->tap(fn (Builder $query) => $this->search ? $query->whereHas('keep', fn (Builder $query) => $query->whereLike('name', "%{$this->search}%")) : $query)
            ->paginate(50);
    }
}
