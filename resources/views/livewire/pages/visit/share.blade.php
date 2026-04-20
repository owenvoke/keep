<div>
    <div class="flex flex-col space-y-1 relative mb-6 w-full">
        <flux:heading size="xl" level="1">{{ $this->visit->keep->name }}</flux:heading>
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

    @if($this->visit->comment)
        <flux:callout>
            <flux:text>{!! nl2br(e($this->visit->comment)) !!}</flux:text>
        </flux:callout>
    @endif
</div>
