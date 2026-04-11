<div>
    <div class="relative mb-6 w-full">
        <div class="flex flex-auto">
            <flux:heading size="xl" level="1" class="mb-6">{{ __('Map') }}</flux:heading>
        </div>
        <flux:separator variant="subtle"/>
    </div>

    <flux:container class="flex flex-auto space-x-4 space-y-4 mb-4">
        <flux:input wire:model="latitude" :placeholder="__('Latitude')" />
        <flux:input wire:model="longitude" :placeholder="__('Longitude')" />
        <flux:select wire:model="distance" :placeholder="__('Distance')">
            <flux:select.option value="10">{{ __('10 km') }}</flux:select.option>
            <flux:select.option value="25">{{ __('25 km') }}</flux:select.option>
            <flux:select.option value="50">{{ __('50 km') }}</flux:select.option>
            <flux:select.option value="100">{{ __('100 km') }}</flux:select.option>
        </flux:select>
        <flux:button>
            <flux:icon.magnifying-glass wire:click.debounce="$refresh && $wire.reload()" />
        </flux:button>
    </flux:container>

    <flux:container>
        <livewire:map class="w-full max-w-dvw h-dvh max-h-[60vh] xl:max-h-[70vh] mb-2 rounded-xl" :keeps="$this->keeps" zoom="8" :center="$this->coordinates->toArray()"/>
    </flux:container>
</div>
