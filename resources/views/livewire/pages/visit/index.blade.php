<div>
    <div class="relative mb-6 w-full">
        <flux:heading size="xl" level="1" class="mb-6">{{ __('Visits') }}</flux:heading>
        <flux:separator variant="subtle" />
    </div>

    <flux:container class="flex flex-auto space-x-4 space-y-4 mb-4">
        <flux:input wire:model.live="search" :placeholder="__('Search by Keep...')" icon="magnifying-glass" />
    </flux:container>

    <flux:table :paginate="$this->visits">
        <flux:table.columns>
            <flux:table.column>{{ __('Keep') }}</flux:table.column>
            <flux:table.column sortable :sorted="$sortBy === 'visited_at'" :direction="$sortDirection"
                               wire:click="sort('visited_at')">{{ __('Visited') }}
            </flux:table.column>
            <flux:table.column>{{ __('Comment') }}</flux:table.column>
            <flux:table.column>{{ __('Visitor') }}</flux:table.column>
            <flux:table.column sortable :sorted="$sortBy === 'updated_at'" :direction="$sortDirection"
                               wire:click="sort('updated_at')">{{ __('Updated') }}
            </flux:table.column>
        </flux:table.columns>

        <flux:table.rows>
            @foreach ($this->visits as $visit)
                @php /** @var App\Models\Visit $visit */ @endphp
                <flux:table.row :key="$visit->uuid">
                    <flux:table.cell class="flex items-center gap-3">
                        <flux:link
                            href="{{ route('visit.show', $visit->uuid) }}">{{ $visit->keep->name }}</flux:link>
                    </flux:table.cell>
                    <flux:table.cell class="whitespace-nowrap">
                        <time datetime="{{ $visit->visited_at->toIso8601String() }}" title="{{ $visit->visited_at->isoFormat('dddd, MMMM Do YYYY, h:mm') }}">{{ $visit->visited_at->diffForHumans() }}</time>
                    </flux:table.cell>
                    <flux:table.cell class="whitespace-nowrap">
                        <flux:text>{{ Illuminate\Support\Str::limit($visit->comment) }}</flux:text>
                    </flux:table.cell>
                    <flux:table.cell class="whitespace-nowrap">
                        <flux:text>{{ $visit->user->name }}</flux:text>
                    </flux:table.cell>
                    <flux:table.cell class="whitespace-nowrap">
                        <time datetime="{{ $visit->updated_at->toIso8601String() }}" title="{{ $visit->updated_at->isoFormat('dddd, MMMM Do YYYY, h:mm') }}">{{ $visit->updated_at->diffForHumans() }}</time>
                    </flux:table.cell>
                </flux:table.row>
            @endforeach
        </flux:table.rows>
    </flux:table>
</div>
