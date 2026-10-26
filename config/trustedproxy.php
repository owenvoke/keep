<?php

declare(strict_types=1);

return [

    /*
    |--------------------------------------------------------------------------
    | Trusted Proxies
    |--------------------------------------------------------------------------
    |
    | A comma-separated list of IP addresses or CIDR ranges whose forwarded
    | headers should be trusted, or "*" to trust the calling proxy. This is
    | read by Laravel's TrustProxies middleware when no value is set in
    | bootstrap/app.php.
    |
    */

    'proxies' => env('TRUSTED_PROXIES'),

];
