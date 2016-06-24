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
                'locale' => null,
                'region' => null,
                'useSsl' => false,
                'apiKey' => null,
            ],
            'google_maps_business' => [
                'client_id' => '',
                'private_key' => null,
                'locale' => null,
                'region' => null,
                'useSsl' => false,
            ],
            'yandex' => [
                'locale' => null,
                'toponym' => null,
            ],
            'maxmind' => [
                'apikey' => '',
                'service' => 'f',
                'useSsl' => false,
            ],
            'arcgis_online' => [
                'sourceCountry' => null,
                'useSsl' => false,
            ],
        ],
    ],
];