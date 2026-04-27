<?php

use Roots\Acorn\Support\Facades\Facade;
use Roots\Acorn\Support\Providers\ServiceProvider;

return [

    'name' => env('APP_NAME', 'Acorn'),

    'env' => defined('WP_ENV') ? WP_ENV : env('WP_ENV', 'production'),

    'debug' => WP_DEBUG && WP_DEBUG_DISPLAY,

    'url' => env('APP_URL', home_url()),

    'timezone' => 'UTC',

    'locale' => env('APP_LOCALE', get_locale()),

    'fallback_locale' => env('APP_FALLBACK_LOCALE', 'en'),

    'faker_locale' => env('APP_FAKER_LOCALE', 'en_US'),

    'cipher' => 'AES-256-CBC',

    'key' => env('APP_KEY'),

    'previous_keys' => [
        ...array_filter(
            explode(',', env('APP_PREVIOUS_KEYS', ''))
        ),
    ],

    'maintenance' => [
        'driver' => env('APP_MAINTENANCE_DRIVER', 'file'),
        'store' => env('APP_MAINTENANCE_STORE', 'database'),
    ],

    'providers' => ServiceProvider::defaultProviders()->merge([
        // Package Service Providers...
    ])->merge([
        // Application Service Providers...
        App\Providers\ThemeServiceProvider::class,
    ])->merge([
        // Added Service Providers (Do not remove this line)...
    ])->toArray(),

    'aliases' => Facade::defaultAliases()->merge([
        // 'Example' => App\Facades\Example::class,
    ])->toArray(),

];
