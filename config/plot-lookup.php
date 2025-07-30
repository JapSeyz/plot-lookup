<?php

return [
    'useragent' => env('PLOT_LOOKUP_USER_AGENT', 'PlotLookup/1.0'),
    'layout' => 'layouts.app',
    'include_assets' => true,
    'dk' => [
        'datafordeler' => [
            'username' => env('DATAFORDELER_USERNAME', ''),
            'password' => env('DATAFORDELER_PASSWORD', ''),
        ]
    ],
];
