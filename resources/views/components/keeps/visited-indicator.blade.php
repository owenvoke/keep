@props(['keep'])
@php /** @var App\Models\Keep $keep */ @endphp
@if(auth()->user()->hasVisited($keep))
    <flux:tooltip :content="__(':type (Visited)', ['type' => $keep->type->label()])">
        <flux:text color="green" class="ml-4">
            <flux:icon.check-circle/>
        </flux:text>
    </flux:tooltip>
@else
    <flux:tooltip :content="$keep->type->label()">
        <flux:text class="ml-4" :color="$keep->type === App\Enums\Type::Folly ? 'amber' : null">
            @if ($keep->type === App\Enums\Type::Palace)
                <flux:icon.castle/>
            @else
                <flux:icon.chess-rook/>
            @endif
        </flux:text>
    </flux:tooltip>
@endif
