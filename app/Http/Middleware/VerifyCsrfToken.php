<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as BaseVerifier;

class VerifyCsrfToken extends BaseVerifier
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        'v1/login',
        'v1/passenger/signup',
        'v1/driver/signup',
        'v1/driver/add-license',
        'v1/merchant/signup',
        'v1/national_id',
        'v1/driver/license',
        'v1/merchant/add_driver',
        'v1/profile_image',
        'v1/gps_coordinate',
        'v1/trip-request',
        'v1/trip-request-response',
        'v1/*',
        'v2/*',
    ];
}
