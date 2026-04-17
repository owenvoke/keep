<div>
    <div class="relative mb-6 w-full">
        <flux:heading size="xl" level="1" class="mb-6">{{ $this->visit->keep->name }}</flux:heading>
        <flux:heading size="md" level="2" class="mb-6 font-mono flex items-center gap-2"
                      title="{{ $this->visit->visited_at->diffForHumans() }}">
            <time datetime="{{ $this->visit->visited_at->toIso8601String() }}">
                {{ $this->visit->visited_at->isoFormat(App\Utils\DateFormat::STANDARD) }}
            </time>
        </flux:heading>
    </div>

    @if($this->visit->comment)
        <flux:callout>
            <flux:text>{{ $this->visit->comment }}</flux:text>
        </flux:callout>
    @endif
</div>
