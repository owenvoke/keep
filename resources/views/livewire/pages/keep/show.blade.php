<div>
    <div class="relative mb-6 w-full">
        <flux:heading size="xl" level="1" class="mb-6">{{ $this->keep->name }}</flux:heading>
        <flux:separator variant="subtle"/>
    </div>

    <div class="flex flex-auto flex-row justify-end mb-6">
        <div class="flex flex-col justify-center mr-2">
            <flux:badge size="sm" color="teal">
                <span>{{ trans_choice(':count total visit|:count total visits', $this->keep->visits->count()) }}</span>
            </flux:badge>
        </div>
        @if(auth()->user()->hasVisited($keep))
            <div class="flex flex-col justify-center mr-2">
                <flux:text color="green" class="align-middle"
                           :title="__('You have already registered a visit to this keep.')">
                    <flux:icon.check-circle/>
                </flux:text>
            </div>
        @endif
        <flux:link :href="route('visit.manage', ['keep' => $this->keep])" wire:navigate>
            <flux:button variant="outline">{{ __('Register visit') }}</flux:button>
        </flux:link>
    </div>

    @if($this->keep->accessible === false)
        <flux:callout variant="warning" icon="exclamation-triangle">
            <flux:callout.heading>{{ __('Inaccessible') }}</flux:callout.heading>

            <flux:callout.text>
                {{ __('This keep has been marked as inaccessible to the public, please check before visiting.') }}
            </flux:callout.text>
        </flux:callout>
    @endif

    <div class="flex flex-auto">
        <dl>
            <div class="p-4 sm:grid sm:grid-cols-2 sm:gap-4 sm:px-0">
                <dt>
                    <flux:text variant="strong">{{ __('Region') }}</flux:text>
                </dt>
                <dd>
                    <flux:text>{{ __($this->keep->region->label()) }}</flux:text>
                </dd>
            </div>
            <div class="p-4 sm:grid sm:grid-cols-2 sm:gap-4 sm:px-0">
                <dt>
                    <flux:text variant="strong">{{ __('Coordinates') }}</flux:text>
                </dt>
                <dd>
                    <flux:link :href="$this->keep->coordinates->link()" external>
                        <flux:text inline>{{ $this->keep->coordinates }}</flux:text>
                    </flux:link>
                    <flux:modal.trigger name="map-modal" shortcut="cmd.m">
                        <flux:icon.map class="ml-1 inline-block cursor-pointer"></flux:icon.map>
                    </flux:modal.trigger>
                </dd>
            </div>
            <div class="p-4 sm:grid sm:grid-cols-2 sm:gap-4 sm:px-0">
                <dt>
                    <flux:text variant="strong">{{ __('Built') }}</flux:text>
                </dt>
                <dd>
                    <flux:text>{{ $this->keep->built }}</flux:text>
                </dd>
            </div>
            <div class="p-4 sm:grid sm:grid-cols-2 sm:gap-4 sm:px-0">
                <dt>
                    <flux:text variant="strong">{{ __('Condition') }}</flux:text>
                </dt>
                <dd>
                    <flux:text>{{ $this->keep->condition }}</flux:text>
                </dd>
            </div>
            <div class="p-4 sm:grid sm:grid-cols-2 sm:gap-4 sm:px-0">
                <dt>
                    <flux:text variant="strong">{{ __('Owner') }}</flux:text>
                </dt>
                <dd>
                    <flux:text>{{ $this->keep->owned_by }}</flux:text>
                </dd>
            </div>
            <div class="p-4 sm:grid sm:grid-cols-2 sm:gap-4 sm:px-0">
                <dt>
                    <flux:text variant="strong">{{ __('Type') }}</flux:text>
                </dt>
                <dd>
                    <flux:text>{{ __($this->keep->type) }}</flux:text>
                </dd>
            </div>
            @if($this->keep->alternative_names)
                <div class="p-4 sm:grid sm:grid-cols-2 sm:gap-4 sm:px-0">
                    <dt>
                        <flux:text variant="strong">{{ __('Alternative Names') }}</flux:text>
                    </dt>
                    <dd>
                        <ul>
                            @foreach($this->keep->alternative_names as $alternativeName)
                                <li>
                                    <flux:text>{{ $alternativeName }}</flux:text>
                                </li>
                            @endforeach
                        </ul>
                    </dd>
                </div>
            @endif
            @if($this->keep->homepage)
                <div class="p-4 sm:grid sm:grid-cols-2 sm:gap-4 sm:px-0">
                    <dt>
                        <flux:text variant="strong">{{ __('Homepage') }}</flux:text>
                    </dt>
                    <dd>
                        <flux:link :href="$this->keep->homepage" external>
                            <flux:text>{{ __('External') }}</flux:text>
                        </flux:link>
                    </dd>
                </div>
            @endif
        </dl>
    </div>

    <div>
        @if($this->keep->description)
            <flux:callout>
                <flux:callout.heading>{{ __('Description') }}</flux:callout.heading>
                <flux:callout.text>{{ $this->keep->description }}</flux:callout.text>
            </flux:callout>
        @endif
    </div>

    <flux:modal name="map-modal" class="w-lg" variant="floating">
        <div class="mt-8">
            <livewire:map
                class="w-full h-100 rounded-md"
                :label="$this->keep->name"
                :primary-keep="$this->keep"
                :keeps="$this->keep->nearestTo($this->keep->coordinates)->get()"/>
        </div>
    </flux:modal>

    @if($this->keep->visits->isNotEmpty())
        <flux:separator variant="subtle" class="my-6"/>

        <div>
            <flux:heading size="lg" level="3" class="mb-4">{{ __('Visits') }}</flux:heading>

            @foreach($this->keep->visits as $visit)
                <div class="p-3 sm:p-4 rounded-lg">
                    <div class="flex flex-row items-center gap-2">
                        <flux:avatar :name="$visit->user->name" :initials="$visit->user->initials()" size="xs"
                                     class="shrink-0"/>
                        <div class="flex flex-row gap-2 items-center">
                            <div class="flex items-center gap-2">
                                <flux:heading>{{ $visit->user->name }}</flux:heading>
                            </div>
                            <flux:text size="sm" variant="subtle">visited</flux:text>
                            <flux:text size="sm">
                                <time datetime="{{ $visit->visited_at->toIso8601String() }}"
                                      title="{{ $visit->visited_at->isoFormat(App\Utils\DateFormat::STANDARD) }}">
                                    {{ $visit->visited_at->diffForHumans() }}
                                </time>
                            </flux:text>
                            <flux:link :href="route('visit.show', ['visit' => $visit])" wire:navigate>
                                <flux:icon.link variant="micro"></flux:icon.link>
                            </flux:link>
                        </div>
                    </div>
                    <div class="min-h-2 sm:min-h-1"></div>
                    <div class="pl-8">
                        <flux:text variant="strong">{{ $visit->comment }}</flux:text>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
