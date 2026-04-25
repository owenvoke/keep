<section class="w-full">
    @include('partials.settings-heading')

    <flux:heading class="sr-only">{{ __('Filter settings') }}</flux:heading>

    <x-settings.layout :heading="__('Filters')" :subheading="__('Update filter settings for your account')">
        <form wire:submit="updateSettings" class="my-6 w-full space-y-6">
                <flux:checkbox wire:model.live.debounce="hideFollies"
                               :label="__('Hide follies')"
                               :description="__('Exclude follies, sham, and mock castles')"/>
            <flux:checkbox wire:model.live.debounce="hideFortifiedManorHouses"
                           :label="__('Hide fortified manor houses')"
                           :description="__('Exclude fortified manor houses')"/>
            <flux:checkbox wire:model.live.debounce="hideTowerHouses"
                           :label="__('Hide tower houses')"
                           :description="__('Exclude tower houses (built for both defence and habitation)')"/>

                <div class="flex items-center gap-4">
                    <flux:button variant="primary" type="submit">{{ __('Save') }}</flux:button>
                </div>
        </form>
    </x-settings.layout>
</section>
