<div>
    <div class="relative mb-6 w-full">
        <div class="flex flex-auto">
            <flux:heading size="xl" level="1" class="mb-6">{{ __('Keeps') }}</flux:heading>
        </div>
        <flux:separator variant="subtle"/>
    </div>

    <flux:container class="flex flex-auto space-x-4 space-y-4 mb-4">
        <flux:input class="mb-4" wire:model.live="search" :placeholder="__('Search...')" icon="magnifying-glass"/>
        <flux:select wire:model.live="region" :placeholder="__('Choose region...')">
            <flux:select.option value="" :selected="$this->region === ''">{{ __('Any') }}</flux:select.option>
            @foreach(App\Enums\Region::cases() as $region)
                <flux:select.option :value="$region->value">{{ __($region->label()) }}</flux:select.option>
            @endforeach
        </flux:select>
        <flux:input class="mb-4" wire:model.live="ownedBy" :placeholder="__('Owned by...')" icon="magnifying-glass"/>
    </flux:container>

    <flux:table :paginate="$this->keeps">
        <flux:table.columns>
            <flux:table.column class="w-0"></flux:table.column>
            <flux:table.column sortable :sorted="$sortBy === 'name'" :direction="$sortDirection"
                               wire:click="sort('name')">{{ __('Name') }}
            </flux:table.column>
            <flux:table.column sortable :sorted="$sortBy === 'region'" :direction="$sortDirection"
                               wire:click="sort('region')">{{ __('Region') }}
            </flux:table.column>
            <flux:table.column sortable :sorted="$sortBy === 'built'" :direction="$sortDirection"
                               wire:click="sort('built')">{{ __('Built') }}
            </flux:table.column>
            <flux:table.column>{{ __('Coordinates') }}</flux:table.column>
            <flux:table.column sortable :sorted="$sortBy === 'owned_by'" :direction="$sortDirection"
                               wire:click="sort('owned_by')">{{ __('Owned By') }}
            </flux:table.column>
        </flux:table.columns>

        <flux:table.rows>
            @foreach ($this->keeps as $keep)
                @php /** @var App\Models\Keep $keep */ @endphp
                <flux:table.row :key="$keep->uuid">
                    <flux:table.cell>
                        @if(auth()->user()->hasVisited($keep))
                            <flux:text color="green" class="ml-4">
                                <flux:icon.check-circle />
                            </flux:text>
                        @endif
                    </flux:table.cell>
                    <flux:table.cell class="flex items-center gap-3">
                        <flux:link href="{{ route('keep.show', $keep->uuid) }}">{{ $keep->name }}</flux:link>
                    </flux:table.cell>
                    <flux:table.cell class="whitespace-nowrap">{{ __($keep->region->label()) }}</flux:table.cell>
                    <flux:table.cell class="whitespace-nowrap">{{ $keep->built }}</flux:table.cell>
                    <flux:table.cell class="whitespace-nowrap">
                        <flux:link href="{{ $keep->coordinates->link() }}" external>{{ $keep->coordinates }}</flux:link>
                    </flux:table.cell>
                    <flux:table.cell class="whitespace-nowrap">{{ $keep->owned_by }}</flux:table.cell>
                </flux:table.row>
            @endforeach
        </flux:table.rows>
    </flux:table>
</div>
