<?php

return [

    'queue_connection' => env('SOCIAL_QUEUE', 'redis'),

    'utm' => [
        'enabled' => true,
        'source' => 'social',
        'medium' => 'revive',
    ],

    'ai' => [
        'enabled' => true,
        'provider' => 'openai',
        'model' => 'gpt-4o-mini',
        'api_key' => env('OPENAI_API_KEY'),
    ],

];
