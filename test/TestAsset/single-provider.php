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
        ],
    ],
];