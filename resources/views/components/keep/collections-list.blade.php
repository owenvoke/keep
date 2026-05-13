@props([
    'keep',
    'limit' => null,
])
@php /** @var App\Models\Keep $keep */ @endphp
@if($keep->collections->isNotEmpty())
    @php $collections = $limit !== null ? $keep->collections->take($limit) : $keep->collections; @endphp
    <div class="flex flex-col gap-4">
        <flux:text>
            {{ trans_choice(':keep is listed in :count collection.|:keep is listed in :count collections.', $keep->collections->count(), ['keep' => $keep->name]) }}
        </flux:text>

        <ul class="ml-4 list-disc">
            @foreach($collections as $collection)
                <li>
                    <flux:link :href="route('collection.show', ['collection' => $collection])" wire:navigate>
                        <flux:text class="inline">{{ $collection->name }}</flux:text>
                    </flux:link>
                </li>
            @endforeach
        </ul>

        @if($limit && $keep->collections->count() > $limit)
            <flux:link :href="route('keep.collections', ['keep' => $keep])" wire:navigate>
                <flux:text class="inline">{{ __('View more...') }}</flux:text>
            </flux:link>
        @endif
    </div>
@endif
