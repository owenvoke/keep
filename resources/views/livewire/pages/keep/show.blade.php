<div>
    <div class="relative mb-6 w-full">
        <flux:heading size="xl" level="1" class="mb-6">{{ $this->keep->name }}</flux:heading>
        <flux:separator variant="subtle"/>
    </div>

    <div class="flex flex-auto flex-row justify-end mb-6">
        @if(auth()->user()->hasVisited($keep))
            <div class="flex flex-col justify-center mr-2">
                <flux:text color="green" class="align-middle" :title="__('You have already registered a visit to this keep.')">
                    <flux:icon.check-circle/>
                </flux:text>
            </div>
        @endif
        <flux:link :href="route('visit.manage', ['keep' => $this->keep])">
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
            <div class="px-4 py-4 sm:grid sm:grid-cols-2 sm:gap-4 sm:px-0">
                <dt>
                    <flux:text size="lg" variant="strong">{{ __('Region') }}</flux:text>
                </dt>
                <dd>
                    <flux:text>{{ __($this->keep->region->label()) }}</flux:text>
                </dd>
            </div>
            <div class="px-4 py-4 sm:grid sm:grid-cols-2 sm:gap-4 sm:px-0">
                <dt>
                    <flux:text size="lg" variant="strong">{{ __('Coordinates') }}</flux:text>
                </dt>
                <dd>
                    <flux:link :href="$this->keep->coordinates->link()">
                        <flux:text>{{ $this->keep->coordinates }}</flux:text>
                    </flux:link>
                </dd>
            </div>
            <div class="px-4 py-4 sm:grid sm:grid-cols-2 sm:gap-4 sm:px-0">
                <dt>
                    <flux:text size="lg" variant="strong">{{ __('Built') }}</flux:text>
                </dt>
                <dd>
                    <flux:text>{{ $this->keep->built }}</flux:text>
                </dd>
            </div>
            <div class="px-4 py-4 sm:grid sm:grid-cols-2 sm:gap-4 sm:px-0">
                <dt>
                    <flux:text size="lg" variant="strong">{{ __('Condition') }}</flux:text>
                </dt>
                <dd>
                    <flux:text>{{ $this->keep->condition }}</flux:text>
                </dd>
            </div>
            <div class="px-4 py-4 sm:grid sm:grid-cols-2 sm:gap-4 sm:px-0">
                <dt>
                    <flux:text size="lg" variant="strong">{{ __('Owner') }}</flux:text>
                </dt>
                <dd>
                    <flux:text>{{ $this->keep->owned_by }}</flux:text>
                </dd>
            </div>
            <div class="px-4 py-4 sm:grid sm:grid-cols-2 sm:gap-4 sm:px-0">
                <dt>
                    <flux:text size="lg" variant="strong">{{ __('Type') }}</flux:text>
                </dt>
                <dd>
                    <flux:text>{{ __($this->keep->type) }}</flux:text>
                </dd>
            </div>
            @if($this->keep->alternative_names)
                <div class="px-4 py-4 sm:grid sm:grid-cols-2 sm:gap-4 sm:px-0">
                    <dt>
                        <flux:text size="lg" variant="strong">{{ __('Alternative Names') }}</flux:text>
                    </dt>
                    <dd>
                        <ul>
                            @foreach($this->keep->alternative_names as $alternativeName)
                                <li>{{ $alternativeName }}</li>
                            @endforeach
                        </ul>
                    </dd>
                </div>
            @endif
            @if($this->keep->homepage)
                <div class="px-4 py-4 sm:grid sm:grid-cols-2 sm:gap-4 sm:px-0">
                    <dt>
                        <flux:text size="lg" variant="strong">{{ __('Homepage') }}</flux:text>
                    </dt>
                    <dd>
                        <flux:link :href="$this->keep->homepage">{{ $this->keep->homepage }}</flux:link>
                    </dd>
                </div>
            @endif
            @if($this->keep->description)
                <div class="px-4 py-4 sm:grid sm:grid-cols-2 sm:gap-4 sm:px-0">
                    <dt>
                        <flux:text size="lg" variant="strong">{{ __('Description') }}</flux:text>
                    </dt>
                    <dd>
                        <flux:text>{{ $this->keep->description }}</flux:text>
                    </dd>
                </div>
            @endif
        </dl>
    </div>

    @if($this->keep->visits->isNotEmpty())
        <flux:separator variant="subtle" class="my-6"/>

        <div>
            <flux:heading size="lg" level="3" class="mb-4">{{ __('Visits') }}</flux:heading>

            @foreach($this->keep->visits as $visit)
                <div class="p-3 sm:p-4 rounded-lg">
                    <div class="flex flex-row sm:items-center gap-2">
                        <flux:avatar :name="$visit->user->name" :initials="$visit->user->initials()" size="xs"
                                     class="shrink-0"/>
                        <div class="flex flex-col gap-0.5 sm:gap-2 sm:flex-row sm:items-center">
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
