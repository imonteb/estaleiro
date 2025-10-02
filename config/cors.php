<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure your settings for cross-origin resource sharing
    | or "CORS". This determines what cross-origin operations may execute
    | in web browsers. You are free to adjust these settings as needed.
    |
    | To learn more: https://developer.mozilla.org/en-US/docs/Web/HTTP/CORS
    |
    */
// Ejemplo para producción:
// 'paths' => ['api/*', 'sanctum/csrf-cookie'],
// 'allowed_methods' => ['*'],
// 'allowed_origins' => ['https://tudominio.com'], // Solo tu frontend real
// 'allowed_origins_patterns' => [],
// 'allowed_headers' => ['*'],
// 'exposed_headers' => [],
// 'max_age' => 0,
// 'supports_credentials' => true, // Si usas cookies/autenticación
    'paths' => ['api/*', 'sanctum/csrf-cookie'],

    'allowed_methods' => ['*'],

    'allowed_origins' => ['https://8mz9z7lf-8000.uks1.devtunnels.ms'],

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => true,

];


