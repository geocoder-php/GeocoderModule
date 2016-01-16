<?php

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