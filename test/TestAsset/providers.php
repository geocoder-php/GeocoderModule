<?php

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