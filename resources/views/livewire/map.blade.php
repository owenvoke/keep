@props([
    'coordinates' => null,
    'label' => null,
    'zoom' => 10,
])

<div>
    @if($coordinates instanceof App\DataObjects\Coordinates)
        @php $id = 'map_'.Illuminate\Support\Str::random(); @endphp
        <div wire:ignore id="{{ $id }}" {{ $attributes }}></div>

        @push('scripts')
            @once
                <script src='https://unpkg.com/maplibre-gl@latest/dist/maplibre-gl.js'></script>
                <link href='https://unpkg.com/maplibre-gl@latest/dist/maplibre-gl.css' rel='stylesheet'/>
            @endonce

            <script defer>
                const {{ $id }} = new maplibregl.Map({
                    container: '{{ $id }}',
                    style: '{{ config('services.map.source') }}',
                    center: [{{ $coordinates->longitude }}, {{ $coordinates->latitude }}],
                    zoom: {{ $zoom }},
                    attributionControl: false,
                })

                new maplibregl.Marker().setLngLat([{{ $coordinates->longitude }}, {{ $coordinates->latitude }}]).
                    setPopup(new maplibregl.Popup().setText('{{ $label }}')).
                    addTo({{ $id }})
            </script>
        @endpush
    @endif
</div>
