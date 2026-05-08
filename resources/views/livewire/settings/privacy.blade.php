<section class="w-full">
    @include('partials.settings-heading')

    <flux:heading class="sr-only">{{ __('Privacy settings') }}</flux:heading>

    <x-settings.layout :heading="__('Privacy')" :subheading="__('Update privacy settings for your account')">
        <form wire:submit="updateSettings" class="my-6 w-full space-y-6">
                <flux:checkbox wire:model.live.debounce="publicVisits"
                               :label="__('Public visits')"
                               :description="__('Visits will be publicly visible by default')"/>

                <div class="flex items-center gap-4">
                    <flux:button variant="primary" type="submit">{{ __('Save') }}</flux:button>
                </div>
        </form>
    </x-settings.layout>
</section>
