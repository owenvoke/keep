<div>
    <div class="relative mb-6 w-full">
        <flux:heading size="xl" level="1" class="mb-6">{{ __('Collections for :keep', ['keep' => $this->keep->name]) }}</flux:heading>
        <flux:separator variant="subtle"/>
    </div>

    <x-keep.collections-list :keep="$this->keep" />
</div>
