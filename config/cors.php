<?php

return [

    'paths' => ['api/*', 'sanctum/csrf-cookie'],
    'allowed_methods' => ['*'],
    'allowed_origins' => ['*'], // Adjust for production
    'allowed_headers' => ['*'],
    'supports_credentials' => true,
];
