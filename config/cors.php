<?php
return [

    'paths' => ['api/*', 'sanctum/csrf-cookie', '/login', '/logout'],

    'allowed_methods' => ['*'],

    // En vez de '*', pon el host de tu front:
    'allowed_origins' => ['*'],

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    // Útil cuando envías cookies:
    'supports_credentials' => true,

];
