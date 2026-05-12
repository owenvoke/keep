<div>
    <div class="relative mb-6 w-full">
        <div class="flex flex-auto">
            <flux:heading size="xl" level="1" class="mb-6">{{ __('Collections') }}</flux:heading>
        </div>
        <flux:separator variant="subtle"/>
    </div>

    <div class="flex flex-auto flex-row justify-end space-x-4 mb-6">
        <flux:link :href="route('collection.manage')" wire:navigate>
            <flux:button variant="outline">{{ __('New collection') }}</flux:button>
        </flux:link>
    </div>

    <flux:container class="flex flex-auto flex-row flex-wrap sm:flex-nowrap gap-2 h-min mb-4">
        <flux:input wire:model.live.debounce="search" :placeholder="__('Search...')" icon="magnifying-glass"/>
        <flux:select wire:model.live.debounce="privacy" class="w-full sm:w-min">
            <flux:select.option value=""
                                :selected="$this->privacy === ''">{{ __('Any privacy') }}</flux:select.option>
            <flux:select.option :value="App\Enums\Privacy::Public">{{ __('Public') }}</flux:select.option>
            <flux:select.option :value="App\Enums\Privacy::Private">{{ __('Private') }}</flux:select.option>
        </flux:select>
    </flux:container>

    <flux:table :paginate="$this->collections">
        <flux:table.columns>
            <flux:table.column sortable :sorted="$sortBy === 'name'" :direction="$sortDirection"
                               wire:click="sort('name')">{{ __('Name') }}</flux:table.column>
            <flux:table.column sortable :sorted="$sortBy === 'is_public'" :direction="$sortDirection"
                               wire:click="sort('is_public')">{{ __('Public') }}</flux:table.column>
            <flux:table.column>{{ __('Keeps') }}</flux:table.column>
            <flux:table.column sortable :sorted="$sortBy === 'updated_at'" :direction="$sortDirection"
                               wire:click="sort('updated_at')">{{ __('Updated') }}
            </flux:table.column>
            <flux:table.column sortable :sorted="$sortBy === 'created_at'" :direction="$sortDirection"
                               wire:click="sort('created_at')">{{ __('Created') }}
            </flux:table.column>
        </flux:table.columns>

        <flux:table.rows>
            @foreach ($this->collections as $collection)
                @php /** @var App\Models\Collection $collection */ @endphp
                <flux:table.row :key="$collection->uuid">
                    <flux:table.cell class="flex items-center gap-3">
                        <flux:link :href="route('collection.show', ['collection' => $collection])"
                                   wire:navigate>{{ $collection->name }}</flux:link>
                    </flux:table.cell>
                    <flux:table.cell class="whitespace-nowrap">
                        <div class="flex flex-row gap-1">
                            <flux:icon :name="$collection->is_public ? 'eye' : 'eye-slash'" class="h-5 w-5 inline"/>
                            <flux:text>{{ $collection->is_public ? __('Public') : __('Private') }}</flux:text>
                        </div>
                    </flux:table.cell>
                    <flux:table.cell class="whitespace-nowrap">{{ $collection->keeps->count() }}</flux:table.cell>
                    <flux:table.cell class="whitespace-nowrap">
                        <time datetime="{{ $collection->updated_at->toIso8601String() }}"
                              title="{{ $collection->updated_at->isoFormat(App\Utils\DateFormat::STANDARD) }}">{{ $collection->updated_at->diffForHumans() }}</time>
                    </flux:table.cell>
                    <flux:table.cell class="whitespace-nowrap">
                        <time datetime="{{ $collection->created_at->toIso8601String() }}"
                              title="{{ $collection->created_at->isoFormat(App\Utils\DateFormat::STANDARD) }}">{{ $collection->created_at->diffForHumans() }}</time>
                    </flux:table.cell>
                </flux:table.row>
            @endforeach
        </flux:table.rows>
    </flux:table>
</div>
