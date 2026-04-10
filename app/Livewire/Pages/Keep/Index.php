<?php

declare(strict_types=1);

namespace App\Livewire\Pages\Keep;

use App\Models\Keep;
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

    #[Url]
    public $region = '';

    #[Url]
    public $ownedBy = '';

    public function render()
    {
        return view('livewire.pages.keep.index');
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
    public function keeps()
    {
        return Keep::query()
            ->tap(fn (Builder $query) => $this->sortBy ? $query->orderBy($this->sortBy, $this->sortDirection) : $query)
            ->tap(fn (Builder $query) => $this->search ? $query->whereLike('name', "%{$this->search}%") : $query)
            ->tap(fn (Builder $query) => $this->region ? $query->where('region', $this->region) : $query)
            ->tap(fn (Builder $query) => $this->ownedBy ? $query->whereLike('owned_by', "%{$this->ownedBy}%") : $query)
            ->paginate(50);
    }
}
