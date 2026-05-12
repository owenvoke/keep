<div>
    <div class="relative mb-6 w-full">
        <flux:heading size="xl" level="1" class="mb-6">
            @if($this->collection)
                <flux:link :href="route('collection.show', $this->collection)" wire:navigate>{{ $this->collection->name }}</flux:link>
            @else
                {{ __('Create collection') }}
            @endif
        </flux:heading>

        @if($this->collection)
            <flux:heading level="3" class="flex flex-row space-x-1 items-center-safe font-mono"
                          title="{{ $this->collection->created_at->diffForHumans() }}">
                <flux:text inline>
                    <flux:icon.clock class="size-5 inline"/>
                </flux:text>
                <flux:text size="sm" inline>
                    <time
                        datetime="{{ $this->collection->created_at->toIso8601String() }}"
                    >{{ $this->collection->created_at->isoFormat(App\Utils\DateFormat::STANDARD) }}</time>
                </flux:text>
            </flux:heading>
        @endif
    </div>

    <flux:separator variant="subtle" class="my-6"/>

    @if($this->collection)
        <div class="flex flex-auto flex-row justify-end space-x-4 mb-6">
            <div>
                <flux:modal.trigger name="delete-visit">
                    <flux:button variant="danger">{{ __('Delete') }}</flux:button>
                </flux:modal.trigger>

                <flux:modal name="delete-visit" class="min-w-88">
                    <div class="space-y-6">
                        <div>
                            <flux:heading size="lg">{{ __('Delete visit?') }}</flux:heading>
                            <flux:text class="mt-2">
                                {{ __('You are about to delete this visit.') }}<br>
                                {{ __('This action cannot be reversed.') }}
                            </flux:text>
                        </div>
                        <div class="flex gap-2">
                            <flux:spacer/>
                            <flux:modal.close>
                                <flux:button variant="ghost">{{ __('Cancel') }}</flux:button>
                            </flux:modal.close>
                            <flux:button type="submit" wire:click="delete"
                                         variant="danger">{{ __('Delete visit') }}</flux:button>
                        </div>
                    </div>
                </flux:modal>
            </div>
        </div>
    @endif

    <div class="flex flex-auto flex-col gap-4">
        <flux:input
            :label="__('Name')"
            wire:model="name"
            autocomplete="off"
        />
        <flux:textarea
            rows="10"
            :label="__('Description')"
            wire:model="description"
        />

        <div class="flex flex-auto flex-row justify-end space-x-4">
            <div class="flex flex-col justify-center">
                <flux:checkbox :label="__('Public')" wire:model="public"/>
            </div>
            <flux:button
                variant="primary"
                wire:click="save"
            >
                {{ __('Save') }}
            </flux:button>
        </div>
    </div>
</div>
