<div>
    @if($keep instanceof App\Models\Keep)
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
                    center: [{{ $this->keep->coordinates->longitude }}, {{ $this->keep->coordinates->latitude }}],
                    zoom: {{ $zoom }},
                    attributionControl: false,
                })

                new maplibregl.Marker().setLngLat(
                    [{{ $this->keep->coordinates->longitude }}, {{ $this->keep->coordinates->latitude }}]).
                    setPopup(new maplibregl.Popup().setText('{{ $this->keep->name }}')).
                    addTo({{ $id }})

                @foreach($this->additionalKeeps as $additionalKeep)
                new maplibregl.Marker({ color: '#bbb' }).setLngLat(
                    [{{ $additionalKeep->coordinates->longitude }}, {{ $additionalKeep->coordinates->latitude }}]).
                    setPopup(new maplibregl.Popup().setHTML(
                        '<a href="{{ route('keep.show', ['keep' => $additionalKeep]) }}">{{ $additionalKeep->name }}</a>')).
                    addTo({{ $id }})
                @endforeach
            </script>
        @endpush
    @endif
</div>
