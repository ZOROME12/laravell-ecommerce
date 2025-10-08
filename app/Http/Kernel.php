<?php

return [
    'web' => [
        \Illuminate\Cookie\Middleware\EncryptCookies::class,
        \Illuminate\Session\Middleware\StartSession::class,
        \Illuminate\Routing\Middleware\SubstituteBindings::class,
    ],

    'api' => [
        \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
        'throttle:api',
        \Illuminate\Routing\Middleware\SubstituteBindings::class,
    ],

    'aliases' => [ 
        'update-last-seen' => \App\Http\Middleware\UpdateLastSeen::class, 
    ],
    
];