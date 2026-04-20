<div>
    <div class="flex flex-col space-y-1 relative w-full">
        <flux:heading size="xl" level="1" class="mb-6">
            <flux:link :href="route('keep.show', ['keep' => $this->visit->keep])" wire:navigate>{{ $this->visit->keep->name }}</flux:link>
        </flux:heading>
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
        <flux:heading level="4" class="flex flex-row space-x-1 items-center-safe font-mono">
            <flux:text inline>
                <flux:icon.user-circle class="size-5 inline" />
            </flux:text>
            <flux:text size="sm" inline>{{ $this->visit->user->name }}</flux:text>
        </flux:heading>
    </div>

    <flux:separator variant="subtle" class="my-6"/>

    @if($this->visit->user_id === auth()->id())
        <div class="flex flex-auto flex-row justify-end space-x-4 mb-6">
            <flux:link :href="url()->signedRoute('visit.share', ['visit' => $this->visit])" wire:navigate>
                <flux:button icon:leading="share" variant="outline">{{ __('Share') }}</flux:button>
            </flux:link>
            <flux:link :href="route('visit.manage', ['keep' => $this->visit->keep, 'visit' => $this->visit])" wire:navigate>
                <flux:button variant="primary">{{ __('Manage visit') }}</flux:button>
            </flux:link>
        </div>
    @endif

    @if($this->visit->comment)
        <flux:callout>
            <flux:text>{!! nl2br(e($this->visit->comment)) !!}</flux:text>
        </flux:callout>
    @endif
</div>
