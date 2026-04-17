<section class="w-full">
    @include('partials.settings-heading')

    <flux:heading class="sr-only">{{ __('Location settings') }}</flux:heading>

    <x-settings.layout :heading="__('Location')" :subheading="__('Update the country and home coordinates for your account')">
        <form wire:submit="updateLocation" class="my-6 w-full space-y-6">
                <flux:select wire:model="country" :label="__('Country')">
                    <flux:select.option value="">{{ __('None') }}</flux:select.option>
                    @foreach(App\Enums\Country::orderedCases() as $country)
                        <flux:select.option :value="$country->value">{{ $country->label(app()->getLocale()) }}</flux:select.option>
                    @endforeach
                </flux:select>
                <flux:input wire:model="coordinates" :label="__('Coordinates')"/>

                <div class="flex items-center gap-4">
                    <flux:button variant="primary" type="submit">{{ __('Save') }}</flux:button>
                </div>
        </form>
    </x-settings.layout>
</section>
