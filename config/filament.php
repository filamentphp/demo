<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Broadcasting
    |--------------------------------------------------------------------------
    |
    | By uncommenting the Laravel Echo configuration, you may connect your
    | admin panel to any Pusher-compatible websockets server.
    |
    | This will allow your admin panel to receive real-time notifications.
    |
    */

    'broadcasting' => [

        'echo' => env('BROADCAST_CONNECTION') === 'reverb' ? [
            'broadcaster' => 'pusher',
            'key' => env('REVERB_APP_KEY'),
            'cluster' => 'mt1',
            'wsHost' => env('REVERB_PUBLIC_URL')
                ? parse_url(env('REVERB_PUBLIC_URL'), PHP_URL_HOST)
                : env('REVERB_HOST', '127.0.0.1'),
            'wsPort' => env('REVERB_PUBLIC_PORT', env('REVERB_PORT', 8080)),
            'wssPort' => env('REVERB_PUBLIC_PORT', env('REVERB_PORT', 8080)),
            'forceTLS' => env('REVERB_PUBLIC_SCHEME', env('REVERB_SCHEME', 'http')) === 'https',
            'enabledTransports' => ['ws', 'wss'],
        ] : null,

    ],

    /*
    |--------------------------------------------------------------------------
    | Default Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | This is the storage disk Filament will use to put media. You may use any
    | of the disks defined in the `config/filesystems.php`.
    |
    */

    'default_filesystem_disk' => env('FILAMENT_FILESYSTEM_DISK', 'public'),

];
