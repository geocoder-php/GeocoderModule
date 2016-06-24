<?php
/**
 * Geocoder (http://geocoder-php.org)
 *
 * @see       https://github.com/geocoder-php/GeocoderModule for the canonical source repository
 * @copyright Copyright (c) 2016, Julien Guittard <julien.guittard@me.com>
 * @license   https://github.com/geocoder-php/GeocoderModule/blob/master/LICENSE.md New BSD License
 */

namespace ZF\Geocoder;

return [
    'service_manager' => [
        'abstract_factories' => [
            Factory\GeocoderAbstractFactory::class,
        ],
    ],
    'geocoder' => [

    ],
];