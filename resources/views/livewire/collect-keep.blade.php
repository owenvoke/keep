<div class="flex flex-col gap-4">
    <flux:heading size="lg">{{ __('Add :keep to a collection', ['keep' => $this->keep->name]) }}</flux:heading>

    <flux:select wire:model="collection">
        <flux:select.option value="">{{ __('Select a collection') }}</flux:select.option>
        @forelse($this->collections as $collection)
            @php /** @var App\Models\Collection $collection */ @endphp
            <flux:select.option :value="$collection->uuid">{{ $collection->name }}</flux:select.option>
        @empty
            <flux:select.option>{{ __('No collections found') }}</flux:select.option>
        @endforelse
    </flux:select>

    <div class="flex flex-auto flex-row justify-end space-x-4">
        <flux:button
            variant="primary"
            wire:click="save"
        >
            {{ __('Save') }}
        </flux:button>
    </div>
</div>
