<div>
    <div class="relative w-full">
        <flux:heading size="xl" level="1">{{ $this->collection->name }}</flux:heading>
    </div>

    <flux:separator variant="subtle" class="my-6"/>

    @if($this->collection->user_id === auth()->id())
        <div class="flex flex-auto flex-row justify-end space-x-4 mb-6">
            <flux:link :href="route('collection.manage', ['collection' => $this->collection])" wire:navigate>
                <flux:button variant="primary">{{ __('Manage collection') }}</flux:button>
            </flux:link>
        </div>
    @endif

    <div class="flex flex-col gap-6">
        @if($this->collection->description)
            <flux:callout>
                <flux:text>{!! nl2br(e($this->collection->description)) !!}</flux:text>
            </flux:callout>
        @endif

        <flux:table :paginate="$this->keeps">
            <flux:table.columns>
                <flux:table.column class="w-0"></flux:table.column>
                <flux:table.column sortable :sorted="$sortBy === 'name'" :direction="$sortDirection"
                                   wire:click="sort('name')">{{ __('Keep') }}
                </flux:table.column>
                <flux:table.column sortable :sorted="$sortBy === 'country'" :direction="$sortDirection"
                                   wire:click="sort('country')">{{ __('Country') }}</flux:table.column>
                <flux:table.column sortable :sorted="$sortBy === 'type'" :direction="$sortDirection"
                                   wire:click="sort('type')">{{ __('Type') }}</flux:table.column>
                <flux:table.column sortable :sorted="$sortBy === 'built'" :direction="$sortDirection"
                                   wire:click="sort('built')">{{ __('Built') }}</flux:table.column>
                <flux:table.column>{{ __('Added') }}</flux:table.column>
                @if($this->collection->user_id === auth()->id())
                    <flux:table.column>{{ __('Actions') }}</flux:table.column>
                @endif
            </flux:table.columns>

            <flux:table.rows>
                @foreach ($this->keeps as $keep)
                    @php /** @var App\Models\Keep $keep */ @endphp
                    <flux:table.row :key="$keep->uuid">
                        <flux:table.cell>
                            <x-keep.visited-indicator :keep="$keep"/>
                        </flux:table.cell>
                        <flux:table.cell class="whitespace-nowrap">
                            <flux:link :href="route('keep.show', $keep)" wire:navigate>{{ $keep->name }}</flux:link>
                        </flux:table.cell>
                        <flux:table.cell class="whitespace-nowrap">{{ __($keep->country->label()) }}</flux:table.cell>
                        <flux:table.cell class="whitespace-nowrap">{{ $keep->type->label() }}</flux:table.cell>
                        <flux:table.cell class="whitespace-nowrap">{{ $keep->built }}</flux:table.cell>
                        <flux:table.cell class="whitespace-nowrap">
                            <time datetime="{{ $keep->pivot->created_at->toIso8601String() }}"
                                  title="{{ $keep->pivot->created_at->isoFormat(App\Utils\DateFormat::STANDARD) }}">{{ $keep->pivot->created_at->diffForHumans() }}</time>
                        </flux:table.cell>
                        @if($this->collection->user_id === auth()->id())
                            <flux:table.cell class="whitespace-nowrap">
                                <flux:modal.trigger :name="'remove-collected:'.$keep->uuid">
                                    <flux:button size="xs" variant="danger">{{ __('Remove') }}</flux:button>
                                </flux:modal.trigger>

                                <flux:modal :name="'remove-collected:'.$keep->uuid" class="min-w-88">
                                    <div class="space-y-6">
                                        <div>
                                            <flux:heading size="lg">{{ __('Remove Keep from collection?') }}</flux:heading>
                                            <flux:text class="mt-2">
                                                {{ __('You are about to remove this keep from the collection.') }}<br>
                                                {{ __('This action cannot be reversed.') }}
                                            </flux:text>
                                        </div>
                                        <div class="flex gap-2">
                                            <flux:spacer/>
                                            <flux:modal.close>
                                                <flux:button variant="ghost">{{ __('Cancel') }}</flux:button>
                                            </flux:modal.close>
                                            <flux:button type="submit" wire:click="removeFromCollection('{{ $keep->uuid }}')"
                                                         variant="danger">{{ __('Remove') }}</flux:button>
                                        </div>
                                    </div>
                                </flux:modal>
                            </flux:table.cell>
                        @endif
                    </flux:table.row>
                @endforeach
            </flux:table.rows>
        </flux:table>
    </div>
</div>
