<meta charset="utf-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>

<title>
    {{ filled($title ?? null) ? $title.' - '.config('app.name') : config('app.name') }}
</title>

<link rel="icon" href="/favicon.svg" type="image/svg+xml">

<link rel="preconnect" href="https://fonts.bunny.net">
<link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet"/>

<link rel="preconnect" href="https://unpkg.com">
<link rel="preload" href="https://unpkg.com/maplibre-gl@latest/dist/maplibre-gl.js" as="script"/>
<link rel="preload" href="https://unpkg.com/maplibre-gl@latest/dist/maplibre-gl.css" as="style"/>

@vite(['resources/css/app.css', 'resources/js/app.js'])
@fluxAppearance

@if(request()->routeIs(['map', 'keep.show']))
    <script src="https://unpkg.com/maplibre-gl@latest/dist/maplibre-gl.js"></script>
    <link href="https://unpkg.com/maplibre-gl@latest/dist/maplibre-gl.css" rel="stylesheet"/>
@endif
