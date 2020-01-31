<?php

return [
    /**
     * Admin path
     */
    'path' => 'admin',

    /**
     * Middleware for admin dashboard
     */
    'middleware' => [
        'web',
        'auth',
    ]
];
