<?php
/**
 * Geocoder (http://geocoder-php.org)
 *
 * @see       https://github.com/geocoder-php/GeocoderModule for the canonical source repository
 * @copyright Copyright (c) 2016, Julien Guittard <julien.guittard@me.com>
 * @license   https://github.com/geocoder-php/GeocoderModule/blob/master/LICENSE.md New BSD License
 */

return [
    'geocoder' => [
        'httpAdapter' => 'Foo\Bar\HttpAdapter',
        'providers' => [
            'google_maps' => [
                'httpAdapter' => 'Foo\Baz\HttpAdapter',
                'locale' => null,
                'region' => null,
                'useSsl' => false,
                'apiKey' => null,
            ],
        ],
    ],
];