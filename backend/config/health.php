<?php

return [
    'reverb' => [
        'host' => env('REVERB_HEALTH_HOST', env('REVERB_HOST', 'reverb')),
        'port' => (int) env('REVERB_HEALTH_PORT', env('REVERB_SERVER_PORT', 8080)),
    ],
];
