<section class="w-full">
    @include('partials.settings-heading')

    <flux:heading class="sr-only">{{ __('Home coordinates settings') }}</flux:heading>

    <x-settings.layout :heading="__('Home coordinates')" :subheading="__('Update the home coordinates for your account')">
        <form wire:submit="updateHomeCoordinates" class="my-6 w-full space-y-6">
            <flux:input wire:model="coordinates" :label="__('Coordinates')"/>

            <div class="flex items-center gap-4">
                <flux:button variant="primary" type="submit">{{ __('Save') }}</flux:button>
            </div>
        </form>
    </x-settings.layout>
</section>
