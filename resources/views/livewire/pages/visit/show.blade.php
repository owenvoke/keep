<div>
    <div class="relative mb-6 w-full">
        <flux:heading size="xl" level="1" class="mb-6">
            <flux:link :href="route('keep.show', ['keep' => $this->visit->keep])">{{ $this->visit->keep->name }}</flux:link>
        </flux:heading>
        <flux:heading size="md" level="2" class="mb-6 font-mono flex items-center gap-2"
                      title="{{ $this->visit->visited_at->diffForHumans() }}">
            <time datetime="{{ $this->visit->visited_at->toIso8601String() }}">
                {{ $this->visit->visited_at->isoFormat(App\Utils\DateFormat::STANDARD) }}
            </time>
        </flux:heading>
        <flux:separator variant="subtle"/>
    </div>

    @if($this->visit->user_id === auth()->id())
        <div class="flex flex-auto flex-row justify-end mb-6">
            <flux:link :href="route('visit.manage', ['keep' => $this->visit->keep, 'visit' => $this->visit])">
                <flux:button variant="outline">{{ __('Manage visit') }}</flux:button>
            </flux:link>
        </div>
    @endif

    @if($this->visit->comment)
        <flux:callout>
            <flux:text>{{ $this->visit->comment }}</flux:text>
        </flux:callout>
    @endif
</div>
