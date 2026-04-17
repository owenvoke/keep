<div>
    <div class="relative mb-6 w-full">
        <div class="flex flex-auto">
            <flux:heading size="xl" level="1" class="mb-6">{{ __('Keeps') }}</flux:heading>
        </div>
        <flux:separator variant="subtle"/>
    </div>

    <flux:container class="flex flex-auto flex-wrap space-x-4 space-y-4 mb-4">
        <flux:input class="mb-4" wire:model.live="search" :placeholder="__('Search...')" icon="magnifying-glass"/>
        <flux:input class="mb-4" wire:model.live="ownedBy" :placeholder="__('Owned by...')" icon="magnifying-glass"/>
        <div class="mb-4 flex flex-auto flex-row flex-wrap sm:flex-nowrap sm:pr-4 space-x-4">
            <flux:select class="mb-4 w-full" wire:model.live="country" wire:change="region = null">
                <flux:select.option value=""
                                    :selected="$this->country === ''">{{ __('Any country') }}</flux:select.option>
                @foreach(App\Enums\Country::cases() as $region)
                    <flux:select.option :value="$region->value">{{ __($region->label()) }}</flux:select.option>
                @endforeach
            </flux:select>
            <div class="flex flex-auto flex-row space-x-4">
                <flux:select class="mb-4 w-min" wire:model.live="region">
                    <flux:select.option value=""
                                        :selected="$this->region === ''">{{ __('Any region') }}</flux:select.option>
                    @if($this->country?->regions())
                        @foreach($this->country->regions() as $region)
                            <flux:select.option :value="$region->value">{{ __($region->label()) }}</flux:select.option>
                        @endforeach
                    @endif
                </flux:select>
                <div class="flex flex-auto w-min flex-col justify-center">
                    <flux:field class="mb-4" variant="inline">
                        <flux:checkbox wire:model.live="onlyVisited"/>
                        <flux:label>{{ __('Visited') }}</flux:label>
                    </flux:field>
                </div>
            </div>
        </div>
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
                                <flux:icon.check-circle/>
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
