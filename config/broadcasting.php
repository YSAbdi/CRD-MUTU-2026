<?php

return [
    'default' => env('BROADCAST_CONNECTION', 'log'),
    'connections' => [
        'log' => ['driver' => 'log'],
        'null' => ['driver' => 'null'],
        'redis' => ['driver' => 'redis', 'connection' => 'default'],
        'pusher' => ['driver' => 'pusher', 'key' => env('PUSHER_APP_KEY'), 'secret' => env('PUSHER_APP_SECRET'), 'app_id' => env('PUSHER_APP_ID'), 'options' => ['cluster' => env('PUSHER_APP_CLUSTER'), 'useTLS' => true], 'server' => env('PUSHER_APP_SERVER')],
    ],
];
