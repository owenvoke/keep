@php
    $id = 'map_'.$this->getId();

    // MapLibreGL expects coordinates in the format [`longitude`, `latitude`]
    $centerCoordinates = match (true) {
        isset($this->primaryKeep) => [$this->primaryKeep->coordinates->longitude, $this->primaryKeep->coordinates->latitude],
        isset($this->center) => [$this->center['longitude'], $this->center['latitude']],
        default => [-2.89479, 54.093409], // Center of UK
    };

    $keepMarkers = $this->keeps->isNotEmpty()
        ? $this->keeps->map(fn (App\Models\Keep $keep) => $keep->toJsonMarker())->values()->all()
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
        <script src='https://unpkg.com/maplibre-gl@latest/dist/maplibre-gl.js'></script>
        <link href='https://unpkg.com/maplibre-gl@latest/dist/maplibre-gl.css' rel='stylesheet'/>
    @endonce

    @script
    <script defer>
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

            const popupConfig = {
                closeButton: false,
                focusAfterOpen: false,
            }

            locator.on('geolocate', (event) => {
                $wire.dispatch('location:updated', [event.coords.latitude, event.coords.longitude])
            })

            const createMarkerInfo = (marker, includeLink = false) => {
                const info = document.createElement('div')

                const name = includeLink ? document.createElement('a') : document.createElement('p')
                if (includeLink) {
                    name.href = marker.url
                }
                name.className = 'font-semibold'
                name.textContent = marker.name

                const built = document.createElement('p')
                built.innerHTML = `<span class="font-medium">Built:</span> ${marker.built}`

                const type = document.createElement('p')
                type.innerHTML = `<span class="font-medium">Type:</span> ${marker.type}`

                const condition = document.createElement('p')
                condition.innerHTML = `<span class="font-medium">Condition:</span> ${marker.condition}`

                info.append(name, built, type, condition)

                return info
            }

            map.on('click', (event) => {
                if (event.type === 'contextmenu') {
                    $wire.dispatch('location:updated', [event.lngLat.lat, event.lngLat.lng])
                }
            });

            @isset($this->primaryKeep)
            new maplibregl.Marker().setLngLat(
                [{{ $this->primaryKeep->coordinates->longitude }}, {{ $this->primaryKeep->coordinates->latitude }}]
            ).setPopup(
                new maplibregl.Popup(popupConfig).setDOMContent(createMarkerInfo(@js($this->primaryKeep->toJsonMarker())))
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
                        return new maplibregl.Marker({ color: marker.color })
                            .setLngLat([marker.longitude, marker.latitude])
                            .setPopup(new maplibregl.Popup(popupConfig).setDOMContent(createMarkerInfo(marker, true)))
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
