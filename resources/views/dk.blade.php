@extends(config('plot-lookup.layout', 'layouts.app'))

@section('title', 'Grunddata for ' . $plot->full_address)
@section('head')
    @if(config('plot-lookup.include_assets'))
        <link rel="stylesheet" href="{{ asset('/vendor/plot-lookup/dk.css') }}">
    @endif
    <style>
        html, body, * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        #plot-lookup-map {
            width: 100%;
            height: 80vh;
        }
        #plot-lookup-tooltip {
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
        #plot-lookup-tooltip p {
            margin: 0;
            padding: 2px 0;
        }
    </style>
    @if(config('plot-lookup.include_assets'))
        <script src="{{ asset('vendor/plot-lookup/dk.js') }}"></script>
    @endif
@stop

@section('content')
<div style="position: relative">
    <div id="plot-lookup-map"></div>
    <div id="plot-lookup-tooltip">
        <h2 id="tooltip-title"></h2>
        <p id="tooltip-area"></p>
        <p id="tooltip-roof"></p>
        <p id="tooltip-heating"></p>
    </div>
</div>
<script>
    var tooltip = document.getElementById('plot-lookup-tooltip')
    var map = new UFST.Map('plot-lookup-map', {
        view: {
            startupZoom: 2,
            grunddataKeys: [
                {landsejerlavskode: '{{ $plot->hoa_id }}', matrikelNr: '{{ $plot->cadastre }}'},
            ],
            markers: [
                @foreach($plot->buildings as $building)
                {
                    id: {{ $building->number }},
                    x: {{ $building->x }},
                    y: {{ $building->y }},
                    titel: '{{ $building->usage }}',
                    shortname: '{{ $building->number }}',
                    icon: '{{ $building->icon }}',
                    color: 'sikker',
                    roof: '{{ $building->roof_material }}',
                    built: '{{ $building->built_at }}',
                    area: '{{ $building->area_residential }}',
                    heating: '{{ $building->heating_device }}',
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
@stop
