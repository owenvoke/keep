<div>
    <div class="relative mb-6 w-full">
        <div class="flex flex-auto">
            <flux:heading size="xl" level="1" class="mb-6">{{ __('Keeps') }}</flux:heading>
        </div>
        <flux:separator variant="subtle"/>
    </div>

    <flux:container class="flex flex-col flex-wrap gap-2 h-min mb-4">
        <div class="space-y-2">
            <flux:input wire:model.live="search" :placeholder="__('Search...')" icon="magnifying-glass"/>
            <flux:input wire:model.live="ownedBy" :placeholder="__('Owned by...')" icon="magnifying-glass"/>
        </div>
        <div class="flex flex-row flex-wrap md:flex-nowrap align-middle gap-2">
            <div class="flex flex-auto flex-row h-min">
                <flux:select wire:model.live="country" wire:change="region = null">
                    <flux:select.option value=""
                                        :selected="$this->country === ''">{{ __('Any country') }}</flux:select.option>
                    @foreach(App\Enums\Country::casesWithKeeps() as $country)
                        <flux:select.option :value="$country->value">{{ __($country->label()) }}</flux:select.option>
                    @endforeach
                </flux:select>
            </div>
            <div class="flex flex-auto flex-row h-min">
                <flux:select wire:model.live="region">
                    <flux:select.option value=""
                                        :selected="$this->region === ''">{{ __('Any region') }}</flux:select.option>
                    @if($this->country?->regions())
                        @foreach($this->country->regions() as $region)
                            <flux:select.option :value="$region->value">{{ __($region->label()) }}</flux:select.option>
                        @endforeach
                    @endif
                </flux:select>
            </div>
            <div class="flex flex-row h-min gap-2">
                <flux:select wire:model.live="type">
                    <flux:select.option value=""
                                        :selected="$this->type === ''">{{ __('Any type') }}</flux:select.option>
                    @foreach(App\Enums\Type::cases() as $type)
                        <flux:select.option :value="$type->value">{{ __($type->label()) }}</flux:select.option>
                    @endforeach
                </flux:select>
                <flux:select wire:model.live="condition">
                    <flux:select.option value=""
                                        :selected="$this->condition === ''">{{ __('Any condition') }}</flux:select.option>
                    @foreach(App\Enums\Condition::cases() as $condition)
                        <flux:select.option
                            :value="$condition->value">{{ __($condition->label()) }}</flux:select.option>
                    @endforeach
                </flux:select>
                <div class="flex flex-col justify-center w-min">
                    <flux:checkbox class="align-middle" :label="__('Visited')" wire:model.live="onlyVisited"/>
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
            <flux:table.column sortable :sorted="$sortBy === 'country'" :direction="$sortDirection"
                               wire:click="sort('country')">{{ __('Country') }}
            </flux:table.column>
            @if($this->country?->regions())
                <flux:table.column sortable :sorted="$sortBy === 'region'" :direction="$sortDirection"
                                   wire:click="sort('region')">{{ __('Region') }}
                </flux:table.column>
            @endif
            <flux:table.column sortable :sorted="$sortBy === 'type'" :direction="$sortDirection"
                               wire:click="sort('type')">{{ __('Type') }}
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
                            <flux:text color="green" class="ml-4"
                                       :title="__(':type (Visited)', ['type' => $keep->type->label()])">
                                <flux:icon.check-circle/>
                            </flux:text>
                        @else
                            <flux:text class="ml-4" :color="$keep->type === App\Enums\Type::Folly ? 'amber' : null"
                                       :title="$keep->type->label()">
                                @if ($keep->type === App\Enums\Type::Palace)
                                    <flux:icon.castle/>
                                @else
                                    <flux:icon.chess-rook/>
                                @endif
                            </flux:text>
                        @endif
                    </flux:table.cell>
                    <flux:table.cell class="flex items-center gap-3">
                        <flux:link href="{{ route('keep.show', $keep->uuid) }}">{{ $keep->name }}</flux:link>
                    </flux:table.cell>
                    <flux:table.cell class="whitespace-nowrap">{{ __($keep->country->label()) }}</flux:table.cell>
                    @if($this->country?->regions())
                        <flux:table.cell class="whitespace-nowrap">{{ __($keep->region->label()) }}</flux:table.cell>
                    @endif
                    <flux:table.cell class="whitespace-nowrap">{{ $keep->type->label() }}</flux:table.cell>
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
