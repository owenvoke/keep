@php
    $id = 'map_'.$this->getId();

    // MapLibreGL expects coordinates in the format [`longitude`, `latitude`]
    $centerCoordinates = match (true) {
        isset($this->primaryKeep) => [$this->primaryKeep->coordinates->longitude, $this->primaryKeep->coordinates->latitude],
        isset($this->center) => [$this->center['longitude'], $this->center['latitude']],
        default => [-2.89479, 54.093409], // Center of UK
    };

    $keepMarkers = $this->keeps->isNotEmpty()
        ? $this->keeps->map(fn (App\Models\Keep $keep) => [
            'longitude' => $keep->coordinates->longitude,
            'latitude' => $keep->coordinates->latitude,
            'name' => $keep->name,
            'url' => route('keep.show', ['keep' => $keep]),
            'color' => auth()->user()->hasVisited($keep) ? 'green' : '#bbb',
        ])->values()->all()
        : [];
@endphp

<div
    id="{{ $id }}"
    data-center='@json($this->center)'
    data-markers='@json($keepMarkers)'
    {{ $attributes }}
>
    <div wire:ignore id="{{ $id }}_canvas" class="h-full w-full"></div>

    @once
        <script data-navigate-once src='https://unpkg.com/maplibre-gl@latest/dist/maplibre-gl.js'></script>
        <link data-navigate-once href='https://unpkg.com/maplibre-gl@latest/dist/maplibre-gl.css' rel='stylesheet'/>
    @endonce

    @script
    <script>
        const mapElement = document.getElementById('{{ $id }}')

        if (! mapElement.__keepMap) {
            const map = new maplibregl.Map({
                container: '{{ $id }}_canvas',
                style: '{{ config('services.map.source') }}',
                center: @js($centerCoordinates),
                zoom: {{ $zoom }},
                attributionControl: false,
            })

            const locator = new maplibregl.GeolocateControl()
            map.addControl(locator)

            locator.on('geolocate', (event) => {
                $wire.dispatch('map-geolocated', [event.coords.latitude, event.coords.longitude])
            })

            @isset($this->primaryKeep)
            new maplibregl.Marker().setLngLat(
                [{{ $this->primaryKeep->coordinates->longitude }}, {{ $this->primaryKeep->coordinates->latitude }}]
            ).setPopup(
                new maplibregl.Popup().setText(@js($this->primaryKeep->name))
            ).addTo(map)
            @endisset

            mapElement.__keepMap = {
                map,
                keepMarkers: [],
                setCenter(center) {
                    if (!center) {
                        return
                    }

                    this.map.easeTo({
                        center: [center.longitude, center.latitude],
                        duration: 500,
                    })
                },
                setKeepMarkers(markers) {
                    this.keepMarkers.forEach((marker) => marker.remove())
                    this.keepMarkers = markers.map((marker) => {
                        const link = document.createElement('a')
                        link.href = marker.url
                        link.textContent = marker.name

                        return new maplibregl.Marker({ color: marker.color })
                            .setLngLat([marker.longitude, marker.latitude])
                            .setPopup(new maplibregl.Popup().setDOMContent(link))
                            .addTo(this.map)
                    })
                }
            }

            const applyState = () => {
                try {
                    const center = JSON.parse(mapElement.dataset.center ?? 'null')
                    const markers = JSON.parse(mapElement.dataset.markers ?? '[]')

                    mapElement.__keepMap.setCenter(center)
                    mapElement.__keepMap.setKeepMarkers(markers)
                } catch {
                    mapElement.__keepMap.setCenter(null)
                    mapElement.__keepMap.setKeepMarkers([])
                }
            }

            const observer = new MutationObserver(applyState)

            observer.observe(mapElement, { attributes: true, attributeFilter: ['data-center', 'data-markers'] })
            applyState()
        }
    </script>
    @endscript
</div>
