<?php

return [
    'default' => 'openai',
    'providers' => [
        'openai' => [
            'driver' => 'openai',
            'key' => env('OPENAI_API_KEY', env('OPENAI_KEY')),
        ],
    ],
];
