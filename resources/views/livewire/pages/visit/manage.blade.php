<div>
    <div class="relative mb-6 w-full">
        <flux:heading size="xl" level="1" class="mb-6">{{ $this->keep->name }}</flux:heading>
        @if($this->visit)
            <flux:heading size="md" level="2" class="mb-6 font-mono flex items-center gap-2"
                          title="{{ $this->visit->visited_at->diffForHumans() }}">
                <time datetime="{{ $this->visit->visited_at->toIso8601String() }}">
                    {{ $this->visit->visited_at->isoFormat('dddd, MMMM Do YYYY, h:mm') }}
                </time>
            </flux:heading>
        @endif
        <flux:separator variant="subtle"/>
    </div>

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
