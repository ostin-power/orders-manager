<?php

return [
    'supportsCredentials' => true,
    'allowedOrigins' => [env('FRONTEND_URL', 'http://localhost:8080')],
    'allowedOriginsPatterns' => [],
    'allowedHeaders' => ['Content-Type', 'X-Requested-With', 'Authorization'],
    'allowedMethods' => ['*'],
    'maxAge' => 0,
    'hosts' => [],
];
