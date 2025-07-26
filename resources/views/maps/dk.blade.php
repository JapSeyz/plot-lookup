<!DOCTYPE html>
<html lang="da">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="manifest" href="/site.webmanifest">
    <link href="https://fonts.bunny.net/css?family=Nunito:400,500,600,700&display=swap" rel="stylesheet">
    <link href="/standalone/bbr/map.css" rel="stylesheet">

    <style>
        html, body, * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        #map {
            width: 100%;
            height: 100vh;
        }
        #tooltip {
            position: absolute;
            top: 5px;
            left: 5px;
            z-index: 1000;
            background: white;
            padding: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
            display: none;
        }
    </style>
</head>

<body>
<div id="map"></div>
<div id="tooltip">
    <h2 id="tooltip-title"></h2>
    <p id="tooltip-area"></p>
    <p id="tooltip-roof"></p>
    <p id="tooltip-heating"></p>
</div>

<script src="/standalone/bbr/map.js"></script>
<script>
    var tooltip = document.getElementById('tooltip')
    var map = new UFST.Map('map', {
        view: {
            startupZoom: 2,
            grunddataKeys: [
                {landsejerlavskode: {{ $plot['hoa_id'] }}, matrikelNr: '{{ $plot['cadastre'] }}'},
            ],
            markers: [
                @foreach($plot['buildings'] as $building)
                {
                    id: {{ $building['number'] }},
                    x: {{ $building['x'] }},
                    y: {{ $building['y'] }},
                    titel: '{{ $building['usage'] }}',
                    shortname: '{{ $building['number'] }}',
                    icon: '{{ $building['icon'] }}',
                    color: 'sikker',
                    roof: '{{ $building['roof_material'] }}',
                    built: '{{ $building['built_at'] }}',
                    area: '{{ $building['area_residential'] }}',
                    heating: '{{ $building['heating_device'] }}',
                },
                @endforeach
            ],
            profile: "retbbr"
        },
        layers: [
            "default",
            new UFST.VectorBygningLayer()
        ],
        events: {
            onMarkerHovered: function (markers, e) {
                var marker = markers[0]
                var title = document.getElementById('tooltip-title')
                var area = document.getElementById('tooltip-area')
                var roof = document.getElementById('tooltip-roof')
                var heating = document.getElementById('tooltip-heating')
                title.innerHTML = marker.titel + ' <span style="color: #333">(' + marker.built + ')</span>'
                area.innerHTML = 'Samlet areal: <b>' + marker.area + ' m²</b>'
                roof.innerHTML = 'Tagmateriale: ' + '<b>' + marker.roof + '</b>'
                heating.innerHTML = 'Varmeinstallation: ' + '<b>' + marker.heating + '</b>'
                tooltip.style.display = 'block'
            }.bind(this),
            onMarkerUnHovered: function (markers, e) {
                tooltip.style.display = 'none'
            }.bind(this)
        }
    });
</script>
</body>
</html>
