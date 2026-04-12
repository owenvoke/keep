<div>
    @php $id = 'map_'.Illuminate\Support\Str::random(); @endphp

    <div wire:ignore id="{{ $id }}" {{ $attributes }}></div>

    @php
        // MapLibreGL expects coordinates in the format [`longitude`, `latitude`]
        $centerCoordinates = match (true) {
            isset($this->primaryKeep) => [$this->primaryKeep->coordinates->longitude, $this->primaryKeep->coordinates->latitude],
            isset($this->center) => [$this->center['longitude'], $this->center['latitude']],
            default => [-2.89479, 54.093409], // Center of UK
        };
    @endphp

    @once
        <script data-navigate-once src='https://unpkg.com/maplibre-gl@latest/dist/maplibre-gl.js'></script>
        <link data-navigate-once href='https://unpkg.com/maplibre-gl@latest/dist/maplibre-gl.css' rel='stylesheet'/>
    @endonce

    <script defer>
        const {{ $id }} = new maplibregl.Map({
            container: '{{ $id }}',
            style: '{{ config('services.map.source') }}',
            @isset($centerCoordinates)
            center: {{ json_encode($centerCoordinates) }},
            @endisset
            zoom: {{ $zoom }},
            attributionControl: false,
        })

        @isset($this->primaryKeep)
        new maplibregl.Marker().setLngLat(
            [{{ $this->primaryKeep->coordinates->longitude }}, {{ $this->primaryKeep->coordinates->latitude }}]).
            setPopup(new maplibregl.Popup().setText('{{ $this->primaryKeep->name }}')).
            addTo({{ $id }})
        @endisset

        @isset($this->keeps)
        @foreach($this->keeps as $keep)
        new maplibregl.Marker({ color: '{{ auth()->user()->hasVisited($keep) ? 'green' : '#bbb' }}' }).setLngLat(
            [{{ $keep->coordinates->longitude }}, {{ $keep->coordinates->latitude }}]).
            setPopup(new maplibregl.Popup().setHTML(
                '<a href="{{ route('keep.show', ['keep' => $keep]) }}">{{ $keep->name }}</a>')).
            addTo({{ $id }})
        @endforeach
        @endisset
    </script>
</div>
