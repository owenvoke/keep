<div>
    <div class="relative mb-6 w-full">
        <flux:heading size="xl" level="1" class="mb-6">
            <flux:link :href="route('keep.show', ['keep' => $this->keep])" wire:navigate>{{ $this->keep->name }}</flux:link>
        </flux:heading>
        @if($this->visit)
            <flux:heading level="3" class="flex flex-row space-x-1 items-center-safe font-mono"
                          title="{{ $this->visit->visited_at->diffForHumans() }}">
                <flux:text inline>
                    <flux:icon.clock class="size-5 inline" />
                </flux:text>
                <flux:text size="sm" inline>
                    <time
                        datetime="{{ $this->visit->visited_at->toIso8601String() }}"
                    >{{ $this->visit->visited_at->isoFormat(App\Utils\DateFormat::STANDARD) }}</time>
                </flux:text>
            </flux:heading>
        @endif
    </div>

    <flux:separator variant="subtle" class="my-6"/>

    @if($this->visit)
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
            icon="clock"
            type="datetime-local"
            :label="__('Visited')"
            wire:model="visited"
        />
        <flux:textarea
            rows="10"
            :label="__('Comment')"
            wire:model="comment"
        />

        <div class="flex flex-auto flex-row justify-end">
            <flux:button
                variant="primary"
                wire:click="save"
            >
                {{ __('Save') }}
            </flux:button>
        </div>
    </div>
</div>
